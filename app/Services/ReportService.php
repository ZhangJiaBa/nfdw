<?php
/**
 * Created by PhpStorm.
 * User: Administrato
 * Date: 2018/6/19
 * Time: 19:25
 */
namespace App\Services;
use Illuminate\Support\Facades\DB;

class ReportService
{
    protected $report;
    protected $departments;
    protected $time_list;

    public function __construct(\stdClass $report, array $time_list, array $departments)
    {
        $this->report = $report;
        $this->time_list = $time_list;
        $this->departments = $departments;
    }

    public function getEmployee($params=null)
    {
        $employees = DB::table('employee')->select('employee.name', 'employee.department')
            ->when($params, function ($query) use ($params)
            {return $query->where('department', $params);})
            ->get()->toArray();
        return $employees;
    }

    public function get_work_time_detail($time, $employee, $department)
    {
        foreach ($time['time_range'] as $k => $v) {
            //十二点之前是上半天
            if(date('a',$v) == 'am') {
                $employee_log = $this->getEmpLogWithAm($v, $employee, $department);
                if (!empty($this->getEmpLogWithAm($v, $employee, $department))) {
                    $employee_log = array_values($employee_log);
                    $result[] = $employee_log[0]->time > $v ? '迟到' : '正常上班';
                } else {
                    $result[] = '旷工';
                }
            }
            else{
                $employee_log = $this->getEmpLogWithPm($v, $employee, $department);
                if (!empty($this->getEmpLogWithPm($v,$employee, $department))) {
                    $employee_log = array_values($employee_log);
                    $result[] = $employee_log[0]->time >= $v? '正常下班' : '早退';
                }
                else
                {
                    $result[] = '旷工';
                }
            }
        }
        //array_unshift($result, $department);
        return $result;
    }

    public function getEmpLogWithAm($time, $params, $department)
    {
        //早上六点到是十二点
        $employee_log = DB::table('people_log')
            ->select('name', 'door', 'time')
            ->where('door', 'like', '%入口%')
            ->where('department', $department)
            ->whereBetween('time', [$time-60*60-3.5, $time +60*60*2.5])->get();
            $result = $this->filterLogWithEmp($employee_log, $params);
            return $result;
    }

    public function getEmpLogWithPm($time, $params, $department)
    {
        $employee_log = DB::table('people_log')
            ->select('name', 'door', 'time')
            ->where('department', $department)
            ->where('door', 'like', '%出口%')
            ->whereBetween('time', [$time-60*60*6, $time +60*60*6])->get();
            $result = $this->filterPmLogWithEmp($employee_log, $params);
            return $result;
    }

    public function filterLogWithEmp($employee_log, $employee)
    {
        $detail = $employee_log->where('name', $employee)->sortBy('time')->take(1)->toArray();
        return $detail;
    }

    public function filterPmLogWithEmp($employee_log, $employee)
    {
        $detail = $employee_log->where('name', $employee)->sortByDesc('time')->take(1)->toArray();
        return $detail;
    }

    public function addCountData($spreadsheet, $indexes, $i, $name)
    {
        $count_title = ['月末统计','迟到', '旷工', '早退'];
        $spreadsheet->getSheetByName($name)->fromArray($count_title, NULL, 'A'.($i+1));
        foreach ($indexes as $num => $index)
        {
            $spreadsheet->getSheetByName($name)->setCellValue('A'.($i+$num+2),'=A'.$index);
            $pValue_late = $this->getPValue($index, '"迟到"');
            $pValue_not_coming = $this->getPValue($index, '"旷工"');
            $pValue_leave_early = $this->getPValue($index, '"提前下班"');;
            $this->setCellValueWithPValue($spreadsheet, 'B'.($i+$num+2), $pValue_late, $name);
            $this->setCellValueWithPValue($spreadsheet, 'C'.($i+$num+2), $pValue_not_coming, $name);
            $this->setCellValueWithPValue($spreadsheet, 'D'.($i+$num+2), $pValue_leave_early, $name);
        }
        return $spreadsheet;
    }

    public function getPValue($index, $rule)
    {
        return '=COUNTIF(B'.$index.':BI'.$index.','.$rule.')';
    }

    public function setCellValueWithPValue($spreadsheet, $index, $pValue, $name)
    {
        return $spreadsheet->getSheetByName($name)->setCellValue($index,$pValue);
    }

    public function FileExit($report)
    {
       return response()->download(storage_path($report), $this->report->name.'.xlsx');
    }

    public function countFromEmp()
    {
        if (!empty($this->report->report))
        {
            return $this->FileExit($this->report->report);
        }
        else
        {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $spreadsheet = $this->makeWorkSheet($this->departments, $spreadsheet, $this->time_list);
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $file_name = time();
            $writer->save(storage_path("data/files/$file_name.xlsx"));
            DB::table('reports')->where('id', $this->report->id)->update(['report' => "data/files/$file_name.xlsx"]);
            return response()->download(storage_path("data/files/$file_name.xlsx"),  $this->report->name.'.xlsx');
        }
    }

    public function makeExcelWithDep($time, $log, $name, $spreadsheet)
    {
       /* array_unshift($time,'部门');*/
        array_unshift($time,'名称/时间');
        $spreadsheet->getSheetByName($name)->fromArray($time, NULL, 'A1');
        $i = 2;
        foreach ($log as $key => $value)
        {
            $result = [];
            foreach ($value as $k => $v)
            {
                $result[] = $v;
            }
            array_unshift($result,$key);
            $spreadsheet->getSheetByName($name)->fromArray($result, NULL, 'A'.$i);
            $indexes[] = $i;
            $i++;

        }
        //添加统计数据
        $spreadsheet = $this->addCountData($spreadsheet, $indexes, $i, $name);
        return $spreadsheet;
    }

    public function makeWorkSheet($departments, $spreadsheet, $time_began_range)
    {
        $employee = $this->getEmployee();
        $result = $this->getData($employee, $time_began_range);
        $spreadsheet = $this->makeExcelWithDep($result['times'], $result['log'], 'Worksheet',$spreadsheet);
        foreach ($departments as $d => $department)
        {
           /* $spreadsheet2 = new Spreadsheet();
            $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet2, $department);
            $spreadsheet->addSheet($myWorkSheet);*/
            $spreadsheet->createSheet();
            $employee = $this->getEmployee($department);
            //遍历查找每个员工的考勤情况;
            $result = $this->getData($employee, $time_began_range);
            $name = 'Worksheet '.($d+1);
            $spreadsheet = $this->makeExcelWithDep($result['times'], $result['log'], $name,$spreadsheet);
        }
        return $spreadsheet;
    }

    public function getData($employee, $time_began_range)
    {
        $log = [];
        foreach ($employee as $key => $value) {
            $emp_come = [];
            $emp_come = $this->get_work_time_detail($time_began_range, $value->name, $value->department);
            $log[$value->department.$value->name] = $emp_come;
            $times = array_map(function ($v) {return date('Y-m-d a', $v);}, $time_began_range['time_range']);
        }
        $result['log'] = $log;
        $result['times'] = $times;
        return $result;
    }
}
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
    public function count($id)
    {
        $report = DB::table('reports')->where('id', $id)->first();
        if (!empty($report->report))
        {
           return $this->FileExit($report->report);
        }
        else
        {
            $time_began_range = $this->getTime($id, $report);
            $employee = $this->getEmployee();
            //遍历查找每个员工的考勤情况;
            foreach ($employee as $key => $value) {
                $emp_come = [];
                $emp_come = $this->get_work_time_detail($time_began_range, $value->name, $value->department);
                $log[$value->name] = $emp_come;
                $times = array_map(function ($v) {return date('Y-m-d a', $v);}, $time_began_range['time_range']);
            }
            $file_name = $this->makeExcel($times, $log);
            DB::table('reports')->where('id', $id)->update(['report' => "data/files/$file_name.xlsx"]);
            return response()->download(storage_path("data/files/$file_name.xlsx"), $report->name.'.xlsx');
        }
    }

    public function makeExcel($time, $log)
    {
        array_unshift($time,'部门');
        array_unshift($time,'名称/时间');
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $spreadsheet->getActiveSheet()->fromArray($time, NULL, 'A1');
        $i = 2;
        foreach ($log as $key => $value)
        {
            $result = [];
            foreach ($value as $k => $v)
            {
                $result[] = $v;
            }
            array_unshift($result,$key);
            $spreadsheet->getActiveSheet()->fromArray($result, NULL, 'A'.$i);
            $indexes[] = $i;
            $i++;
        }
        //添加统计数据
        $spreadsheet = $this->addCountData($spreadsheet, $indexes, $i);
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $file_name = time();
        $writer->save(storage_path("data/files/$file_name.xlsx"));
        return $file_name;
    }

    public function getTime($id, $report)
    {
        //拿到所有时间段
        $began_time = strtotime($report->began_time)+8.5*60*60;
        $end_time = strtotime($report->end_time)+8.5*60*60;
        for ($began_time; $began_time <= $end_time; $began_time+=60*60*24)
        {
            $time['time_range'][]= intval($began_time);
            $time['time_range'][]= intval($began_time+9*60*60);
        }
        return $time;
    }

    public function getEmployee()
    {
        return DB::table('employee')->select('employee.name', 'employee.department')->get()->toArray();
    }

    public function get_work_time_detail($time, $employee, $department)
    {
        foreach ($time['time_range'] as $k => $v) {
            //十二点之前是上半天
            if(date('a',$v) == 'am') {
                $employee_log = $this->getEmpLogWithAm($v, $employee);
                if (!empty($this->getEmpLogWithAm($v, $employee))) {
                    $employee_log = array_values($employee_log);
                    $result[] = $employee_log[0]->time > $v ? '迟到' : '正常上班';
                } else {
                    $result[] = '旷工';
                }
            }
            else{
                $employee_log = $this->getEmpLogWithPm($v,$employee);
                if (!empty($this->getEmpLogWithPm($v,$employee))) {
                    $employee_log = array_values($employee_log);
                    $result[] = $employee_log[0]->time >= $v? '正常下班' : '提前下班';
                }
                else
                {
                    $result[] = '旷工';
                }
            }
        }
        array_unshift($result, $department);
        return $result;
    }

    public function getEmpLogWithAm($time, $params)
    {
        //早上六点到是十二点
        $employee_log = DB::table('people_log')->select('name', 'door', 'time')->where('door', 'like', '%入口%')
            ->whereBetween('time', [$time-60*60-3.5, $time +60*60*2.5])->get();
        if (isset($params))
        {
            $result = $this->filterLogWithEmp($employee_log, $params);
        }
        return $result;
    }

    public function getEmpLogWithPm($time, $params)
    {
        $employee_log = DB::table('people_log')->select('name', 'door', 'time')
            ->whereBetween('time', [$time-60*60*6, $time +60*60*6])->where('door', 'like', '%出口%')->get();
        if (isset($params))
        {
            $result = $this->filterPmLogWithEmp($employee_log, $params);
        }
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

    public function addCountData($spreadsheet, $indexes, $i)
    {
        $count_title = ['月末统计','迟到', '旷工', '早退'];
        $spreadsheet->getActiveSheet()->fromArray($count_title, NULL, 'A'.($i+1));
        foreach ($indexes as $num => $index)
        {
            $spreadsheet->getActiveSheet()->setCellValue('A'.($i+$num+2),'=A'.$index);
            $pValue_late = $this->getPValue($index, '"迟到"');
            $pValue_not_coming = $this->getPValue($index, '"旷工"');
            $pValue_leave_early = $this->getPValue($index, '"提前下班"');;
            $this->setCellValueWithPValue($spreadsheet, 'B'.($i+$num+2), $pValue_late);
            $this->setCellValueWithPValue($spreadsheet, 'C'.($i+$num+2), $pValue_not_coming);
            $this->setCellValueWithPValue($spreadsheet, 'D'.($i+$num+2), $pValue_leave_early);
        }
        return $spreadsheet;
    }

    public function getPValue($index, $rule)
    {
        return '=COUNTIF(B'.$index.':BI'.$index.','.$rule.')';
    }

    public function setCellValueWithPValue($spreadsheet, $index, $pValue)
    {
        return $spreadsheet->getActiveSheet()->setCellValue($index,$pValue);
    }

    public function FileExit($report)
    {
       return response()->download(storage_path($report));
    }
}
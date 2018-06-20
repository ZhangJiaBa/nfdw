<?php
/**
 * Created by PhpStorm.
 * User: Administrato
 * Date: 2018/6/19
 * Time: 19:25
 */
namespace App\Services;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ReportService
{
    public function count($id)
    {
        $employee = $this->getEmployee();
        $time_began_range = $this->getTime($id);
        //遍历查找每个员工的考勤情况;
        foreach ($employee as $key => $value) {
            $emp_come = [];
            $emp_come = $this->get_work_time_detail($time_began_range, $value->name);
            $log[$value->name] = $emp_come;
            $times = array_map(function ($v) {return date('Y-m-d a', $v);}, $time_began_range['time_range']);
        }
        return $this->makeExcel($times, $log);
    }

    public function makeExcel($time, $log)
    {
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
            $i++;

        }
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $file_name = time();
        $writer->save(storage_path("data/files/$file_name.xlsx"));
        return response()->download(storage_path("data/files/$file_name.xlsx"));
    }

    public function getTime($id)
    {
        //拿到所有时间段
        $report = DB::table('reports')->where('id', $id)->first();
        $began_time = strtotime($report->began_time)+9.5*60*60;
        $end_time = strtotime($report->end_time)+9.5*60*60;
        for ($began_time; $began_time <= $end_time; $began_time+=60*60*24)
        {
            $time['time_range'][]= intval($began_time);
            $time['time_range'][]= intval($began_time+8.5*60*60);
        }
        return $time;
    }

    public function getEmployee()
    {
        return DB::table('employee')->select('employee.name')->get()->toArray();
    }

    public function get_work_time_detail($time, $employee)
    {
        foreach ($time['time_range'] as $k => $v) {
            //十二点之前是上半天
            if(date('a',$v) == 'am') {
                $employee_log = $this->getEmpLogWithAm($v, $employee);
                if ($this->getEmpLogWithAm($v, $employee)) {
                    $employee_log = array_values($employee_log);
                    $result[] = $employee_log[0]->time > $v ? '迟到' : '正常上班';
                } else {
                    $result[] = '旷工';
                }
            }
            else{
                $employee_log = $this->getEmpLogWithPm($v,$employee);
                if (!empty($this->getEmpLogWithAm($v,$employee))) {
                    $employee_log = array_values($employee_log);
                    $result[] = $employee_log[0]->time >= $v? '正常下班' : '提前下班';
                }
                else
                {
                    $result[] = '旷工';
                }
            }
        }
        return $result;
    }

    public function getEmpLogWithAm($time, $params)
    {
        //早上六点到是十二点
        $employee_log = DB::table('people_log')->select('name', 'door', 'time')
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
            ->whereBetween('time', [$time-60*60*6, $time +60*60*6])->get();
        if (isset($params))
        {
            $result = $this->filterPmLogWithEmp($employee_log, $params);
        }
        return $result;
    }

    public function filterLogWithEmp($employee_log, $employee)
    {
        $detail = $employee_log->where('name', $employee)->sort('time')->take(1)->toArray();
        return $detail;
    }

    public function filterPmLogWithEmp($employee_log, $employee)
    {
        $detail = $employee_log->where('name', $employee)->sortByDesc('time')->take(1)->toArray();
        return $detail;
    }
}
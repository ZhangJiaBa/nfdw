<?php

namespace App\Http\Controllers\Backend\Index;

use App\Models\Reports;
use App\Http\Controllers\Controller;
use Encore\Admin\Layout\Content;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    use ModelForm;

    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('考勤统计列表');
            $content->body($this->grid()->render());
        });
    }

    public function grid()
    {
        return Reports::grid(function(Grid $grid) {
            $grid->name('报表名称');
            $grid->actions(function ($actions) {
                $key = $actions->getKey();
                $actions->prepend("<a href='/read/" . $key . "' style='margin-right:10%;' >读取数据</a>");
                $actions->prepend("<a href='/count/" . $key . "' style='margin-right:10%;' >生成报表</a>");
        });
    });
    }

    public function create()
    {
        return Admin::content(function (Content $content) {
            $content->header('合同');
            $content->description(trans('创建'));
            $content->body($this->form());
        });
    }

    public function form()
    {
       return  Reports::form(function (Form $form) {
            $form->text('name', "报表名称");
            $form->date('began_time', '开始时间');
            $form->date('end_time', '结束时间');
            $form->file('car', '车辆基本表')->move('/files', time().'peo.xlsx');
            $form->file('employee', "人员基本表")->move('/files', time().'emp.xlsx');
            $form->file('car_log', '车辆进出记录表')->move('/files', time().'car.xlsx');
            $form->file('people_log', '人员进出记录表')->move('/files', time().'peo.xlsx');
            $form->save();
        });
    }

    public function read($id)
    {
        $result = DB::table('reports')->where('id', $id)->select('car','employee', 'car_log','people_log', 'update_log')->get()->toArray();
        foreach ($result[0] as $key => $value)
        {
            switch ($key)
            {
                case 'employee':
                    $this->insertToEmployee($value);
                    break;
                case 'car':
                    $this->insertToCar($value);
                    break;
                case 'people_log':
                    $this->insertToPeopleLog($value);
                    break;
                case 'car_log':
                    $this->insertToCarLog($value);
                    break;
            }
        }
        return redirect('/index');
    }

    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header("部门管理");
            $content->description("编辑");
            $content->body($this->form()->edit($id));
        });
    }

    public function insertToPeopleLog($file_name)
    {
        $params = $this->loadFile($file_name);
        unset($params[0]);
        $insert = [];
        foreach ($params as $key => $value)
        {
            $value[0] = ($value[0]-70*365-19)*86400-8*3600;
            $insert[$key]['time'] = $value[0];
            $insert[$key]['card'] = $value[1];
            $insert[$key]['door'] = $value[3];
            $insert[$key]['name'] = $value[4];
            $insert[$key]['department'] = $value[7];
        }
        DB::table('people_log')->insert($insert);
    }

    public function insertToCarLog($file_name)
    {
        $params = $this->loadFile($file_name);
        unset($params[0]);
        $insert = [];
        foreach ($params as $key => $value)
        {
            $employee = DB::table('car')->where('car_num', $value[0])->first();
            $value[7] = strtotime($value[7]);
            $value[3] = $value[3] == '入场'?'入口':'出口';
            $insert[$key]['time'] = $value[7];
            $insert[$key]['door'] = $value[3];
            $insert[$key]['name'] = $employee->name;
            $insert[$key]['department'] = $employee->department;
        }
        DB::table('people_log')->insert($insert);
    }

    public function insertToEmployee($file_name)
    {
        $params = $this->loadFile($file_name);
        unset($params[0], $params[1]);
        $insert = [];
        foreach ($params as $key => $value)
        {
            $insert[$key]['name'] = $value[1];
            $insert[$key]['department'] = $value[2];
        }
        DB::table('employee')->insert($insert);
    }

    public function insertToCar($file_name)
    {
        $params = $this->loadFile($file_name);
        unset($params[0]);
        $insert = [];
        foreach ($params as $key => $value)
        {
            $insert[$key]['car_num'] = $value[2];
            $insert[$key]['name'] = $value[3];
            $insert[$key]['department'] = $value[1];
        }
        DB::table('car')->insert($insert);
    }

    public function loadFile($file_name)
    {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadDataOnly(true);
        $reader = $reader->load(storage_path('data/'.$file_name));
        return $reader->getActiveSheet()->toArray();
    }

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
       $this->makeExcel($times, $log);
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
        $file_name = time().'count';
        $writer->save(storage_path("data/files/$file_name.xlsx"));
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

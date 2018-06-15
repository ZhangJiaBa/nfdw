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
                $actions->prepend("<a href='/read/" . $key . "' style='margin-right:10%;' >生成报表</a>");
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
           $form->file('car', '车辆基本表')->move('/files', time().'peo.xlsx');
            $form->file('employee', "人员基本表")->move('/files', time().'emp.xlsx');
            $form->file('car_log', '车辆进出记录表')->move('/files', time().'car.xlsx');
            $form->file('people_log', '人员进出记录表')->move('/files', time().'peo.xlsx');
            $form->save();
        });
    }

    public function read($id)
    {
        $result = DB::table('reports')->where('id', $id)->select('car','employee', 'car_log','people_log', 'update_log')->first();
        foreach ($result as $key => $value)
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


}

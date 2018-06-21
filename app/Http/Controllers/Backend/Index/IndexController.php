<?php

namespace App\Http\Controllers\Backend\Index;

use App\Models\Reports;
use App\Http\Controllers\Controller;
use App\Services\ReportService;
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
                $hasRead = DB::table('reports')->where('id', $key)->first()->has_read;
                if ($hasRead == 1)
                {
                   /* $actions->prepend("<a href='/count/" . $key . "' style='margin-right:10%;' >生成报表</a>");*/
                    $actions->prepend("<a href='/count_from_dep/" . $key . "' style='margin-right:10%;' >生成部门报表</a>");
                }else
                {
                    $actions->prepend("<a href='/read/" . $key . "' style='margin-right:10%;' >读取数据</a>");
                }
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
            $form->hidden('has_read')->value(0);
            $form->save();
        });
    }

    public function read($id)
    {
        DB::table('car')->delete();
        DB::table('people_log')->delete();
        DB::table('employee')->delete();
        $result = DB::table('reports')->where('id', $id)->select('car','employee', 'car_log','people_log', 'update_log', 'id')
            ->get()->toArray();
        $ReadExcelService = app()->make('ReadExcelService');
        $ReadExcelService->ReadExcel($result[0]);
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

    public function getTime($id)
    {
        $report = DB::table('reports')->where('id', $id)->first();
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

    public function countWithDep($id)
    {
        $report = DB::table('reports')->where('id', $id)->first();
        $time_list = $this->getTime($id);
        $departments = $this->getDepartment();
        $ReportService = new ReportService($report, $time_list, $departments);
        return $ReportService->countFromEmp();
    }

    public function getDepartment()
    {
        $departments = DB::table('employee')->select('department')->get();
        foreach ($departments->groupBy('department')->toArray() as $department => $value)
        {
            $result[] = $department;
        }
        return $result;
    }
}

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
        $result = DB::table('reports')->where('id', $id)->select('car','employee', 'car_log','people_log', 'update_log')
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

    public function count($id)
    {
        $ReportService = app()->make('ReportService');
        $ReportService->count($id);
    }
}

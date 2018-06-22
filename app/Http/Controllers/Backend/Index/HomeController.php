<?php

namespace App\Http\Controllers\Backend\Index;

use App\Http\Controllers\Controller;
use Encore\Admin\Auth\Permission;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\InfoBox;

class HomeController extends Controller
{
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header("快捷面板");
            $content->description("");

            $content->row(function ($row) {
                /*$row->column(3, new InfoBox('用户管理', 'users', 'aqua', '/users', '用户管理'));
                $row->column(3, new InfoBox('角色管理', 'shopping-cart', 'green', '/roles', '角色管理'));
                $row->column(3, new InfoBox('权限管理', 'book', 'yellow', '/permissions', '权限管理'));
                $row->column(3, new InfoBox('操作记录', 'file', 'red', '/logs', '操作记录'));*/
                $row->column(3, new InfoBox('考勤', 'users', 'aqua', '/index', '考勤'));
            });

        });
    }
}

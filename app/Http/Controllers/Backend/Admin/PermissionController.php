<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Models\Admin\Menu;
use App\Models\Admin\Permission;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PermissionController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header(trans('admin.permissions'));
            $content->description(trans('admin.list'));
            $content->body($this->grid()->render());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     *
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header(trans('admin.permissions'));
            $content->description(trans('admin.edit'));
            $content->body($this->formEdit()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {
            $content->header(trans('admin.permissions'));
            $content->description(trans('admin.create'));
            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Permission::class, function (Grid $grid) {
            $grid->id('ID')->sortable();
            $grid->slug(trans('admin.slug'));
            $grid->name(trans('admin.name'));

            $grid->http_path(trans('admin.route'))->display(function ($path) {
                return collect(explode("\r\n", $path))->map(function ($path) {
                    $method = $this->http_method ?: ['ANY'];

                    if (Str::contains($path, ':')) {
                        list($method, $path) = explode(':', $path);
                        $method = explode(',', $method);
                    }

                    $method = collect($method)->map(function ($name) {
                        return strtoupper($name);
                    })->map(function ($name) {
                        return "<span class='label label-primary'>{$name}</span>";
                    })->implode('&nbsp;');

                    $path = '/' . trim(config('admin.route.prefix'), '/') . $path;

                    return "<div style='margin-bottom: 5px;'>$method<code>$path</code></div>";
                })->implode('');
            });

            $grid->created_at(trans('admin.created_at'));
            $grid->updated_at(trans('admin.updated_at'));

            $grid->tools(function (Grid\Tools $tools) {
                $tools->batch(function (Grid\Tools\BatchActions $actions) {
                    $actions->disableDelete();
                });
            });
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        return Admin::form(Permission::class, function (Form $form) {
            $form->display('id', 'ID');

            $form->text('slug', "标识")->rules('required');
            $form->text('name', "权限名称")->rules('required');

            $form->multipleSelect('http_method', "操作方法")
                ->options($this->getHttpMethodsOptions())
                ->help('不选则允许全部操作');

            $form->listbox('http_path', '允许访问地址')->options(function () {
                return Menu::all()->pluck('title', 'uri');
            });

            $form->display('created_at', "创建时间");
            $form->display('updated_at', "更新时间");
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function formEdit()
    {
        return Admin::form(Permission::class, function (Form $form) {
            $form->display('id', 'ID');

            $form->text('slug', "标识")->rules('required');
            $form->text('name', "权限名称")->rules('required');

            $form->multipleSelect('http_method', "操作方法")
                ->options($this->getHttpMethodsOptions())
                ->help('不选则允许全部操作');

            $form->listbox('http_path', '允许访问地址')->options(function () {
                return Menu::all()->pluck('title', 'uri');
            });

            $form->display('created_at', "创建时间");
            $form->display('updated_at', "更新时间");
        });
    }

    /**
     * Get options of HTTP methods select field.
     *
     * @return array
     */
    protected function getHttpMethodsOptions()
    {
        $option = [
            '查看', '创建', '修改', '删除', 'PATCH', 'OPTIONS', 'HEAD',
        ];
        return array_combine(Permission::$httpMethods, $option);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $params = $request->only('slug', 'name', 'http_method', 'http_path');
        if (!empty($params['http_path'])) {
            $params['http_method'] = implode(",", $params['http_method']);
            $params['http_path'] = implode("\r\n", $params['http_path']);
        }
        $insert_rel = DB::table('permissions')->insert($params);
        if ($insert_rel) {
            admin_toastr('新增完成');
            return redirect('permissions');
        } else {
            admin_toastr('新增失败');
            return redirect('permissions');
        }
    }

    public function update(Request $request, $id)
    {
        $params = $request->only('slug', 'name', 'http_method', 'http_path');
        if (!empty($params['http_path'])) {
            $params['http_method'] = implode(",", $params['http_method']);
            $params['http_path'] = implode("\r\n", $params['http_path']);
        }
        $insert_rel = DB::table('permissions')->where('id', $id)->update($params);
        if ($insert_rel) {
            admin_toastr('更新完成');
            return redirect('permissions');
        } else {
            admin_toastr('更新失败');
            return redirect('permissions');
        }
    }
}

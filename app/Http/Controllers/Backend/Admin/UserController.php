<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Models\Admin\Administrator;
use App\Models\Admin\Permission;
use App\Models\Admin\Role;
use App\Models\Department\Department;
use App\Models\Workflow\User;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class UserController extends Controller
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
            $content->header(trans('admin.administrator'));
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
            $content->header(trans('admin.administrator'));
            $content->description(trans('admin.edit'));
            $content->body($this->form()->edit($id));
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
            $content->header(trans('admin.administrator'));
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
        return Administrator::grid(function (Grid $grid) {
            $grid->id('ID')->sortable();
            $grid->username(trans('admin.username'));
            $grid->name(trans('admin.name'));
            $grid->roles(trans('admin.roles'))->pluck('name')->label();
            $grid->created_at(trans('admin.created_at'));
            $grid->updated_at(trans('admin.updated_at'));

            $grid->actions(function (Grid\Displayers\Actions $actions) {
                if ($actions->getKey() == 1) {
                    $actions->disableDelete();
                }
            });

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
        return Administrator::form(function (Form $form) {
            $form->display('id', 'ID');
            $form->select('dp_id', '所属部门')->options(function () {
                return Department::all()->pluck('name', 'id');
            })->rules('required');
            $form->text('username', "用户账号")->rules('required');
            $form->text('name', "用户名")->rules('required');
            $form->image('avatar', "用户头像");
            $form->hidden('wf_username', trans('admin.username'));
            $form->hidden('wf_password', trans('admin.name'));
            $form->hidden('wf_usr_id', trans('admin.name'));
            $form->image('sign_img', "用户签名");
            $form->password('password', trans('admin.password'))->rules('required|confirmed');
            $form->password('password_confirmation', trans('admin.password_confirmation'))->rules('required')
                ->default(function ($form) {
                    return $form->model()->password;
                });

            $form->ignore(['password_confirmation']);

            $form->multipleSelect('roles', trans('admin.roles'))->options(Role::all()->pluck('name', 'id'));
            $form->multipleSelect('permissions', trans('admin.permissions'))->options(Permission::all()->pluck('name', 'id'));

            $form->display('created_at', trans('admin.created_at'));
            $form->display('updated_at', trans('admin.updated_at'));
            $form->saving(function (Form $form) {
                if ($form->password && $form->model()->password != $form->password) {
                    $form->password = bcrypt($form->password);
                }
            });
            $form->saved(function (Form $form) {
                $this->createWfUser($form->model());
            });
        });
    }

    public function createWfUser($user)
    {
        if (empty($user->wf_username)){
            $wfuser = new User();
            try{
                $rel = $wfuser->create($user->username, "420889102@qq.com", '2020-12-31',
                    'ACTIVE', 'PROCESSMAKER_OPERATOR',$user->username, $user->username);
                if ($rel['USR_UID']) {
                    $params['wf_username'] = $user->username;
                    $params['wf_password'] = $user->username;
                    $params['wf_usr_id'] = $rel['USR_UID'];
                    $group = new \App\Models\Workflow\Group();
                    $groupId = Department::where('id',$user->dp_id)->value('wf_group_id');
                    $group->assign($groupId, $rel['USR_UID']);
                    DB::table('users')->where('id',$user->id)->update($params);
                }
            }catch (\Exception $exception){
                DB::table('users')->where('id',$user->id)->delete();
                admin_toastr('新增失败');
                return redirect('users');
            }

        }
    }
}

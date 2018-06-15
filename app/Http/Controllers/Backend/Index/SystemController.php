<?php

namespace App\Http\Controllers\Backend\Index;

use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Encore\Admin\Widgets\Box;
use Encore\Admin\Widgets\Form;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SystemController extends Controller
{
    /**
     * Display a listing of the resource.
     *$form->text('system','系统名称')->default($envs['system']);
    $form->text('company','公司名称')->default($envs['company']);
    $form->text('address', '地址')->default($envs['address']);
    $form->text('stamp', '邮编')->default($envs['stamp']);
    $form->text('telephone', '电话')->default($envs['telephone']);
    $form->text('tax', '传真')->default($envs['tax']);
    $form->email('e-mail', '邮箱')->default($envs['e-mail']);
    $form->url('url', '公司官网')->default($envs['url']);
    $form->text('powered', '版权')->default($envs['powered']);
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = config('system');
            $envs = [
                ['name' => '系统名称',             'value' => $data['system']],
                ['name' => '公司名称',            'value' => $data['company']],

                ['name' => '地址',      'value' => $data['address']],
                ['name' => '邮编',    'value' => $data['stamp']],
                ['name' => '电话',      'value' => $data['telephone']],

                ['name' => '传真',          'value' => $data['tax']],
                ['name' => '邮箱',            'value' => $data['e-mail']],
                ['name' => '公司官网',               'value' => $data['url']],
                ['name' => '版权',               'value' => $data['powered']],
            ];

            return view('system', compact('envs'));

    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $params = $request->only('system','company','address','stamp','telephone','tax','e-mail','url','powered');
        foreach ($params as $key => $value){
            config(['system.'.$key => $value]);
        }
        admin_toastr("设置成功");
        return redirect('system');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $envs = config('system');
        return Admin::content(function (Content $content) use($envs){
            $content->header("系统信息");
            $content->description("编辑");
            $content->body($this->form($envs)->render());
        });
    }


    public function form($envs)
    {
        $form = new Form();
        $form->method('post');
        $form->action(url('system/store'));
        $form->text('system','系统名称')->default($envs['system']);
        $form->text('company','公司名称')->default($envs['company']);
        $form->text('address', '地址')->default($envs['address']);
        $form->text('stamp', '邮编')->default($envs['stamp']);
        $form->text('telephone', '电话')->default($envs['telephone']);
        $form->text('tax', '传真')->default($envs['tax']);
        $form->email('e-mail', '邮箱')->default($envs['e-mail']);
        $form->url('url', '公司官网')->default($envs['url']);
        $form->text('powered', '版权')->default($envs['powered']);
        return $form;
    }

}

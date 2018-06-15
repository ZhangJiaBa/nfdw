@extends('layouts.default')

@section('title','403')

@section('pageHeader','权限错误')

@section('content')

    <div class="callout callout-danger">
        <h4>异常警告：403错误 权限不足</h4>
        <p>您没有权限访问当前页面，请联系超级管理员或者访问其它页面节点！</p>
        <p><a href="/">&gt;&gt;&gt;点此快速跳转回首页&lt;&lt;&lt;</a></p>
    </div>

@endsection

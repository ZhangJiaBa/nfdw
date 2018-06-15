@extends('layouts.default')

@section('title','404')

@section('pageHeader','错误')

@section('pageDesc','页面找不到')

@section('content')

    <div class="callout callout-danger">
        <h4>异常警告：404错误 找不到页面</h4>
        <p>404错误，未找到该页面，请访问其它页面节点！</p>
        <p><a href="/">&gt;&gt;&gt;点此快速跳转回首页&lt;&lt;&lt;</a></p>
    </div>

@endsection
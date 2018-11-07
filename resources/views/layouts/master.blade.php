@extends('layouts.admin')

@section('content-header')
<h1>Workflow</h1>
<ol class="breadcrumb">
    <li><a href="/admin/"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active"><a href="/workflow/admin/workflows">Workflow</a></li>
</ol>
@endsection

@section('content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">工作流</h3>
            <div class="box-tools">
                <a href="{{ route('workflow.admin.workflows.index') }}" class="btn btn-default btn-sm">返回</a>
            </div>
        </div>
        <div class="box-body">
            @yield('content')
        </div>
        <div class="box-footer"></div>
    </div>
@overwrite

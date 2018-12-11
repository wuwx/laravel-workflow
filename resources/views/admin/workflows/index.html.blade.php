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
        <h3 class="box-title">
            工作流
        </h3>
        <div class="box-tools">
            <a href="{{ route('workflow.admin.workflows.create') }}" class="btn btn-default btn-sm">新增</a>
        </div>
    </div>
    <div class="box-body">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th width="20">ID</th>
                    <th width="120">Name</th>
                    <th>标题</th>
                    <th width="10"></th>
                    <th width="10"></th>
                </tr>
            </thead>
            @foreach($workflows as $workflow)
            <tr>
                <td>#{{ $workflow->id }}</td>
                <td>{{ $workflow->name }}</td>
                <td>
                    <a href="{{ route('workflow.admin.workflows.show', $workflow->name) }}">{{ $workflow->title }}</a>
                </td>
                <td>
                    <a href="{{ route('workflow.admin.workflows.show', ['workflow' => $workflow->name, '_format' => 'png']) }}" class="btn btn-default btn-sm">PNG</a>
                </td>
                <td>
                    <a href="{{ route('workflow.admin.workflows.edit', $workflow) }}" class="btn btn-default btn-sm">编辑</a>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
    <div class="box-footer"></div>
</div>
@endsection

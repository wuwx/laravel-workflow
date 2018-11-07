@extends('workflow::layouts.master')

@section('content')
<div class="box">
    <div class="box-header with-border">
        <div class="box-title">
            流程
        </div>
        <div class="box-tools">
            <a href="{{ route('workflow.admin.workflows.versions.processes.create', [$workflow, $version]) }}" class="btn btn-default btn-sm">新建</a>
        </div>
    </div>
    <div class="box-body">
        <table class="table table-bordered table-striped table-hover">
            <tr>
                <th width="10">ID</th>
                <th width="60">类型</th>
                <th width="120">名称</th>
                <th>标题</th>
                <th width="10"></th>
            </tr>
            @foreach($processes as $process)
            <tr>
                <td>#{{ $process->id }}</td>
                <td>{{ $process->type }}</td>
                <td>
                    <a href="{{ route("workflow.admin.workflows.versions.processes.show", [$workflow, $version, $process]) }}">{{ $process->name }}</a>
                </td>
                <td>
                    <a href="{{ route("workflow.admin.workflows.versions.processes.show", [$workflow, $version, $process]) }}">{{ $process->title }}</a>
                </td>
                <td>
                    <a href="{{ route('workflow.admin.workflows.versions.processes.edit', [$workflow, $version, $process]) }}" class="btn btn-default btn-sm">编辑</a>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection

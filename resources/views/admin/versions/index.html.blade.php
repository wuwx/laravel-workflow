@extends('workflow::layouts.master')

@section('content')
<div class="box">
    <div class="box-header with-border">
        <div class="box-title">
            版本
        </div>
        <div class="box-tools">
            <a href="{{ route('workflow.admin.workflows.versions.create', [$workflow]) }}" class="btn btn-default btn-sm">新建</a>
        </div>
    </div>
    <div class="box-body">
        <table class="table table-bordered table-striped table-hover">
            <tr>
                <th width="10">ID</th>
                <th width="60">状态</th>
                <th>名称</th>
                <th width="10"></th>
            </tr>
            @foreach($versions as $version)
            <tr>
                <td>#{{ $version->id }}</td>
                <td>{{ $version->status }}</td>
                <td>
                    <a href="{{ route('workflow.admin.workflows.versions.processes.index', [$workflow, $version]) }}">
                        {{ $version->name }}
                    </a>
                </td>
                <td>
                    <a href="{{ route('workflow.admin.workflows.versions.edit', [$workflow, $version]) }}" class="btn btn-default btn-sm">编辑</a>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection

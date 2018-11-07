@extends('workflow::layouts.master')

@section('content')
<div class="box">
    <div class="box-header with-border">
        <div class="box-title">
            {{ $version->name }}
        </div>
        <div class="box-tools">
            <a href="{{ route('workflow.admin.workflows.versions.edit', [$workflow, $version]) }}" class="btn btn-default btn-sm">编辑</a>
        </div>
    </div>
    <div class="box-body">
        <table class="table table-bordered table-striped table-hover">
            <tr>
                <td>
                    <a href="{{ route('workflow.admin.workflows.versions.processes.index', [$workflow, $version]) }}">Processes</a>
                </td>
            </tr>
        </table>
    </div>
</div>
@endsection

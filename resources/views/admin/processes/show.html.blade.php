@extends('workflow::layouts.master')

@section('content')
<div class="box">
    <div class="box-header with-border">
        <div class="box-title">
            流程
        </div>
        <div class="box-tools">

        </div>
    </div>
    <div class="box-body">
        <table class="table table-bordered table-striped table-hover">
            <tr>
                <td>
                    <a href="{{ route('workflow.admin.workflows.versions.processes.places.index', [$workflow, $version, $process]) }}">Places</a>
                </td>
            </tr>
            <tr>
                <td>
                    <a href="{{ route('workflow.admin.workflows.versions.processes.transitions.index', [$workflow, $version, $process]) }}">Transitions</a>
                </td>
            </tr>
            <tr>
                <td>
                    <a href="{{ route('workflow.admin.workflows.versions.processes.subjects.index', [$workflow, $version, $process]) }}">Subjects</a>
                </td>
            </tr>
        </table>
    </div>
</div>
@endsection

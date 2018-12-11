@extends('workflow::layouts.master')

@section('content')
<div class="box">
    <div class="box-header with-border">
        <div class="box-title">
            {{ array_get($workflow->getMetadataStore()->getWorkflowMetadata(), 'title') }}
        </div>
        <div class="box-tools">

        </div>
    </div>
    <div class="box-body">
        <table class="table table-bordered table-striped table-hover">
            <tr>
                <td>
                    <a href="{{ route('workflow.admin.workflows.places.index', [$workflow->getName()]) }}">Places</a>
                </td>
            </tr>
            <tr>
                <td>
                    <a href="{{ route('workflow.admin.workflows.transitions.index', [$workflow->getName()]) }}">Transitions</a>
                </td>
            </tr>
            <tr>
                <td>
                    <a href="{{ route('workflow.admin.workflows.subjects.index', [$workflow->getName()]) }}">Subjects</a>
                </td>
            </tr>
        </table>
    </div>
</div>
@endsection

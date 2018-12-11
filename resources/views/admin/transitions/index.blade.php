@extends('workflow::layouts.master')

@section('content')
<div class="box">
    <div class="box-header with-border">
        <div class="box-title">
            迁移
        </div>
        <div class="box-tools">
            <a href="{{ route('workflow.admin.workflows.transitions.create', [$workflow->getName()]) }}" class="btn btn-default btn-sm">新建</a>
        </div>
    </div>
    <div class="box-body">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>名称</th>
                    <th>标题</th>
                    <th>描述</th>
                    <th>Froms</th>
                    <th>Tos</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($transitions as $transition)
                <tr>
                    <td>{{ $transition->getName() }}</td>
                    <td>{{ array_get($workflow->getMetadataStore()->getTransitionMetadata($transition), 'title') }}</td>
                    <td>{{ array_get($workflow->getMetadataStore()->getTransitionMetadata($transition), 'description') }}</td>
                    <td width="120">{{-- Wuwx\LaravelWorkflow\Entities\Place::whereIn('name', $transition->froms)->pluck('title')->implode(", ") --}}</td>
                    <td width="120">{{-- Wuwx\LaravelWorkflow\Entities\Place::whereIn('name', $transition->tos)->pluck('title')->implode(", ") --}}</td>
                    <td width="10">
                        <a href="{{ route('workflow.admin.workflows.transitions.edit', [$workflow->getName(), $transition->getName()]) }}" class="btn btn-default btn-sm">编辑</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@extends('workflow::layouts.master')

@section('content')
<div class="box">
    <div class="box-header with-border">
        <div class="box-title">
            Places
        </div>
        <div class="box-tools">
            <a href="{{ route('workflow.admin.workflows.places.create', [$workflow->getName()]) }}" class="btn btn-default btn-sm">新建</a>
        </div>
    </div>
    <div class="box-body">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th width="120">名称</th>
                    <th>标题</th>
                    <th>备注</th>
                    <th>Icon</th>
                    <th width="80">属性数</th>
                    <th width="10"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($places as $place)
                <tr>
                    <td>{{ $place }}</td>
                    <td>{{ array_get($workflow->getMetadataStore()->getPlaceMetadata($place), 'title') }}</td>
                    <td>{{ array_get($workflow->getMetadataStore()->getPlaceMetadata($place), 'description') }}</td>
                    <td class="icon">@fa('{{ @$workflow->getMetadataStore()->getPlaceMetadata($place)["icon"] }} bg-{{ @$workflow->getMetadataStore()->getPlaceMetadata($place)["color"] }}')</td>
                    <td>{{ count(array_get($workflow->getMetadataStore()->getPlaceMetadata($place), "attributes", [])) }}</td>
                    <td>
                        <a href="{{ route('workflow.admin.workflows.places.edit', [$workflow->getName(), $place]) }}" class="btn btn-default btn-sm">编辑</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('styles')
<style>
    td.icon i {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        line-height: 20px;
        text-align: center;
    }
</style>
@endpush

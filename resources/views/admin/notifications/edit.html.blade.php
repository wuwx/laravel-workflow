@extends('workflow::layouts.master')

@section('content')
<div class="box">
    <div class="box-header with-border">
        <div class="box-title">
            编辑
        </div>
        <div class="box-tools">
            {!! Form::open(['route' => ['workflow.admin.notifications.destroy', $notification], 'method' => 'DELETE']) !!}
            {!! Form::submit('删除', ['class' => 'btn btn-default btn-sm', 'onclick' => "javascript:return confirm('确定要删除吗？');"]); !!}
            {!! Form::close() !!}
        </div>
    </div>
    <div class="box-body">
        {!! form($form) !!}
    </div>
</div>
@endsection

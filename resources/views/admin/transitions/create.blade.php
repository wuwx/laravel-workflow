@extends('workflow::layouts.master')

@section('content')
<div class="box">
    <div class="box-header with-border">
        <div class="box-title">
            新增
        </div>
    </div>
    <div class="box-body">
        {!! form($form) !!}
    </div>
</div>
@endsection

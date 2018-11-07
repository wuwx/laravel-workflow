@extends('workflow::layouts.master')

@section('content')
<div class="box">
    <div class="box-header with-border">
        <div class="box-title">
            创建新版本
        </div>
        <div class="box-tools">

        </div>
    </div>
    <div class="box-body">
        {!! form($form) !!}
    </div>
</div>
@endsection

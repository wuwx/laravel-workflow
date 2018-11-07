@extends('workflow::layouts.master')

@section('content')
<div class="box">
    <div class="box-header">
        <div class="box-title">
            {{ $version->name }}
        </div>
        <div class="box-tools">

        </div>
    </div>
    <div class="box-body">
        {!! form($form) !!}
    </div>
</div>
@endsection

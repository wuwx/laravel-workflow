@extends('workflow::layouts.master')

@section('content')
<table class="table table-bordered table-striped table-hover">
    <tr>
        <th></th>
        <th></th>
        <th></th>
    </tr>
    @foreach($notifications as $notification)
    <tr>
        <td>{{ $notification->id }}</td>
        <td>{{ $notification->name }}</td>
        <td width="80">
            <a href="{{ route('workflow.admin.notifications.edit', $notification) }}" class="btn btn-default btn-sm">编辑</a>
        </td>
    </tr>
    @endforeach
</table>
@endsection

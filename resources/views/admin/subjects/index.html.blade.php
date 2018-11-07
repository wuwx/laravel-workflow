@extends('workflow::layouts.master')

@section('content')
<div class="box">
    <div class="box-header with-border">
        <div class="box-title">
            Subjects
        </div>
        <div class="box-tools">

        </div>
    </div>
    <div class="box-body">
        <table class="table table-bordered table-striped table-hover">
            <tr>
                <th>Entity ID</th>
                <th>Entity Type</th>
                <th>Marking</th>
                <th width="150">Updated At</th>
                <th width="150">Created At</th>
            </tr>
            @foreach($subjects as $subject)
            <tr>
                <td>{{ $subject->entity_id }}</td>
                <td>{{ $subject->entity_type }}</td>
                <td>{{ $subject->marking }}</td>
                <td>{{ $subject->updated_at }}</td>
                <td>{{ $subject->created_at }}</td>
            </tr>
            @endforeach
        </table>
    </div>
    <div class="box-footer">
        {{ $subjects->links() }}
    </div>
</div>
@endsection

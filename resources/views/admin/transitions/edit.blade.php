@extends('workflow::layouts.master')

@section('content')
<div class="box">
    <div class="box-header with-border">
        <div class="box-title">
            编辑
        </div>
        <div class="box-tools">
            {!! Form::open(['route' => ['workflow.admin.workflows.transitions.destroy', $workflow, $transition], 'method' => 'DELETE']) !!}
            {!! Form::submit('删除', ['class' => 'btn btn-default btn-sm', 'onclick' => "javascript:return confirm('确定要删除吗？');"]); !!}
            {!! Form::close() !!}
        </div>
    </div>
    <div class="box-body">
        {!! form_start($form) !!}
        {!! form_until($form, 'description') !!}
        <div class="row">
            {!! form_until($form, 'tos') !!}
        </div>
        {!! form_until($form, 'automatic') !!}

        <div class="box">
            <div class="box-header">
                <div class="box-title">
                    属性字段
                </div>
                <div class="box-tools">
                    <a class="btn btn-primary" href="{{ route('workflow.admin.attributes.create') }}" data-remote="true" data-disable-with="等待">添加</a>
                </div>
            </div>
            <div class="box-body" id="attributes">
                @foreach($transition->attributes as $attribute)
                    <?php
                    $attribute_form = FormBuilder::create('Wuwx\LaravelWorkflow\Forms\AttributeForm', [
                        'model' => $attribute,
                        'name' => "attributes[{$attribute->id}]"
                    ]);
                    foreach ($attribute_form->getFields() as $name => $field) {
                        $field->setValue(array_get($attribute_form->getModel(), $name));
                    }
                    ?>
                    @include('workflow::admin.attributes._form', ['form' => $attribute_form])
                @endforeach
            </div>
        </div>

        <div class="box">
            <div class="box-header">
                <div class="box-title">
                    消息通知
                </div>
                <div class="box-tools">
                    <a class="btn btn-primary" href="{{ route('workflow.admin.notifications.create') }}" data-remote="true" data-disable-with="等待">添加</a>
                </div>
            </div>
            <div class="box-body" id="notifications">
                @foreach($transition->notifications as $notification)
                    <?php
                    $notification_form = FormBuilder::create('Wuwx\LaravelWorkflow\Forms\NotificationForm', [
                        'model' => $notification,
                        'name' => "notifications[{$notification->id}]"
                    ]);
                    foreach ($notification_form->getFields() as $name => $field) {
                        $field->setValue(array_get($notification_form->getModel(), $name));
                    }
                    ?>
                    @include('workflow::admin.notifications._form', ['form' => $notification_form])
                @endforeach
            </div>
        </div>

        {!! form_end($form, true) !!}
    </div>
</div>
@endsection

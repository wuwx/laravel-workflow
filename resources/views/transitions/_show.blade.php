<div class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            {!! Form::open(['route' => ['workflow.transitions.update', $transition, 'subject_id' => Request::input('subject_id'), 'subject_type' => Request::input('subject_type')], 'method' => 'PUT']) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">流程</h4>
                </div>
                <div class="modal-body">
                    @foreach($transition->attributes as $attribute)
                        {!! form_row($form->{$attribute->name}) !!}
                    @endforeach
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="transition" value="{{ $transition->name }}">&nbsp;&nbsp;
                    {!! Form::submit("确认", ['class' => 'btn btn-primary', 'data-disable-with' => '操作中...']) !!}
                </div>
            {!! Form::close() !!}
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<style>
.cancel-off-png, .cancel-on-png, .star-half-png, .star-off-png, .star-on-png {
    font-size: 1.2em;
    color: #F90;
}
</style>

<script>
$(function(){
    $('.score').raty({
        starType : 'i'
    });
});
</script>

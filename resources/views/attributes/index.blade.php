@foreach($attributes as $attribute)
    {!! form_row($form->{$attribute->name}) !!}
@endforeach

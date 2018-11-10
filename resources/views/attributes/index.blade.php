@foreach($attributes as $attribute)
    {!! form_row($form->{array_get($attribute, 'name')}) !!}
@endforeach

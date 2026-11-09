@if(data_get($demo, 'group_registration.enabled', true))
@include('demo.group-registration-fields')
@else
@include('demo.single-registration-fields')
@endif

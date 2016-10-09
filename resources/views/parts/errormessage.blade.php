@if (isset($errors) && count($errors) > 0)
<ul>
    @foreach ($errors->all() as $error)
    <li>{{ $error }}</li>
    @endforeach
</ul>
@endif

@if(Session::has('flash_message'))
<ul class="parts_errormessage">
    <li>{{Session::get('flash_message')}}</li>
</ul>
@endif

@if (isset($error_message))
<ul>
    <li>{{$error_message}}</li>
</ul>
@endif

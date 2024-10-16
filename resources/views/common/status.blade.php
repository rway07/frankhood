@if(session('status'))
    <input id='status' type='hidden' value='{{ session('status') }}'>
@else
    <input id='status' type='hidden' value=''>
@endif

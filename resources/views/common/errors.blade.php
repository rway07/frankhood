@if (count($errors) > 0)
<!-- Form Error List -->
<div class="alert alert-danger">
    <strong>Si è verificato un errore.</strong>
    <br><br>
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<div id="error_div">

</div>

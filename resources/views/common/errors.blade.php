@if (count($errors) > 0)
<!-- Form Error List -->
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Si è verificato un errore.</strong>
    <br><br>
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
<div id="error_div">

</div>

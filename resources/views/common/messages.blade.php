@if (count($messages) > 0)
<div class="alert alert-info">
    <strong>Messaggio</strong>

    <br><br>

    <ul>
        @foreach ($messages->all() as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
</div>
@endif
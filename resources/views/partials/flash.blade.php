@if (session('status'))
    <div class="alert alert-success border-0 shadow-sm">
        {{ session('status') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger border-0 shadow-sm">
        <strong>Please fix the following:</strong>
        <ul class="mb-0 mt-2 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

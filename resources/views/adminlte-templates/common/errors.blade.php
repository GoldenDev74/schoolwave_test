@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (Session::has('flash_message'))
    <div class="alert alert-{{ Session::get('flash_type', 'info') }}">
        {{ Session::get('flash_message') }}
    </div>
@endif

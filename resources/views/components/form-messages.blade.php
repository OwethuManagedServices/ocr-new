@if ($errors->any())
    <div class="bg-red text-light p-3">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if(session()->has('message'))
    <div class="bg-lightgrey text-dark p-3">
        {{ session()->get('message') }}
    </div>
@endif
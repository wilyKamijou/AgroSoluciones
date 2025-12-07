@if(session('success'))
<div style="text-align: center;" class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div style="text-align: center;" class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
</div>
@endif

@if ($errors->any())
<div class="alert alert-warning">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
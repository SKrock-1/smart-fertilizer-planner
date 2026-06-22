@if (session('success'))
    <div class="mb-4 rounded border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-800">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="mb-4 rounded border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
        <div class="font-semibold">Please fix the following:</div>
        <ul class="mt-2 list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif











@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Market Offer</h2>

    <form action="{{ url('/m/'.$markets->id) }}" method="POST">
        @csrf
        @method('PATCH')

        <div class="mb-3 pt-4">
            <label class="form-label">{{$markets->chemical->chemical_name}}</label>
            {{-- <div name="chemical_id" id="chemical_id" class="form-control" value="$markets->chemical->id"> --}}
                {{-- @foreach ($chemicals as $chemical) --}}
                    {{-- <option value="{{ $chemical->id }}">{{ $chemical->chemical_name }}</option> --}}
                {{-- @endforeach --}}
            {{-- </div> --}}
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <input id="description"
                            type="text"
                            class="form-control @error('description') is-invalid @enderror"
                            name="description"
                            value="{{ old('description') ?? $markets->description }}"
                            required autocomplete="description" autofocus>
                    @error('description')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" step="0.01" name="price" id="price" class="form-control" value="{{ old('price') ?? $markets->price }}" required>
        </div>

        <div class="mb-3">
            <label for="stock" class="form-label">Available Quantity</label>
            <input type="number" name="stock" id="stock" class="form-control" value="{{ old('stock') ?? $markets->stock }}" required>
        </div>

        <button type="submit" class="btn btn-success">Edit Offer</button>
    </form>
</div>

{{-- <script>
    function toggleCustomInput(select) {
        let customInput = document.getElementById('description');
        if (select.value === "desc") {
            customInput.classList.remove('d-none');
            customInput.required = true;
        } else {
            customInput.classList.add('d-none');
            customInput.required = false;
        }
    }
</script> --}}
@endsection

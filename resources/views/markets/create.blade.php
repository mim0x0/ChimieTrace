









@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add Market Offer</h2>

    <form action="{{ url('/m/store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="chemical_id" class="form-label">Select Chemical</label>
            <select name="chemical_id" id="chemical_id" class="form-control">
                @foreach ($chemicals as $chemical)
                    <option value="{{ $chemical->id }}">{{ $chemical->chemical_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            {{-- <select name="inventory_id" id="inventory_id" class="form-control" onchange="toggleCustomInput(this)">
                <option value="">-- Select Descripton --</option>
                @foreach ($inventories as $inventory)
                    <option value="{{ $inventory->id }}">{{ $inventory->id }}</option>
                @endforeach
                <option value="desc">Other (Enter manually)</option>
            </select> --}}
            {{-- <input id="description" type="text" class="form-control mt-2 d-none" name="description" placeholder="Enter custom inventory"> --}}
            <input id="description"
                            type="text"
                            class="form-control @error('description') is-invalid @enderror"
                            name="description"
                            value="{{ old('description') }}"
                            required autocomplete="description" autofocus>
                    @error('description')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" step="0.01" name="price" id="price" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="stock" class="form-label">Available Quantity</label>
            <input type="number" name="stock" id="stock" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Add Offer</button>
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

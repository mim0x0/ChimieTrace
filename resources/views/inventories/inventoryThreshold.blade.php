



{{-- @extends('layouts.app')

@section('content')
<div class="container">
    <form action="{{ route('inventory.threshold.store', $inventory) }}" method="POST">
        @csrf
        @method('PATCH')

        <div class="form-group">
            <label for="chemical">Chemical</label> --}}
            {{-- <select name="chemical_id" id="chemical-select" class="form-control" required> --}}
                {{-- <option value="">Select Chemical</option>
                @foreach($chemicals as $chemical) --}}
                    {{-- <option value="{{ $inventory->chemical->id }}">{{ $inventory->chemical->chemical_name }}</option> --}}
                {{-- @endforeach --}}
            {{-- </select> --}}
        {{-- </div>

        <div class="form-group mt-3">
            <label for="inventory">Inventory</label> --}}
            {{-- <select name="id" id="inventory-select" class="form-control" required> --}}
                {{-- <option value="{{ $inventory->id }}">{{ $inventory->description }}</option> --}}
            {{-- </select> --}}
        {{-- </div>

        <div class="form-group mt-3">
            <label for="inventory">Inventory</label> --}}
            {{-- <select name="id" id="inventory-select" class="form-control" required> --}}
                {{-- <option>{{ $inventory->serial_number }}</option> --}}
            {{-- </select> --}}
        {{-- </div>

        <div class="form-group mt-3">
            <label for="min_quantity">Threshold</label>
            <input type="number" name="min_quantity" class="form-control" placeholder="Enter threshold" value="{{ old('min_quantity') ?? $inventory->min_quantity }}" required>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Set Threshold</button>
    </form>

</div> --}}

{{-- <script>
    document.getElementById('chemical-select').addEventListener('change', function () {
        const chemicalId = this.value;
        const inventorySelect = document.getElementById('inventory-select');
        inventorySelect.innerHTML = '<option>Loading...</option>';

        fetch(`/i/threshold/${chemicalId}`)
            .then(response => response.json())
            .then(data => {
                inventorySelect.innerHTML = '<option value="">Select Inventory</option>';
                data.forEach(inventory => {
                    inventorySelect.innerHTML += `<option value="${inventory.id}">${inventory.serial_number} (${inventory.quantity} ${inventory.unit})</option>`;
                });
            })
            .catch(() => {
                inventorySelect.innerHTML = '<option>Error loading inventories</option>';
            });
    });
</script> --}}


{{-- <script>
    $(document).ready(function() {
        $('#chemical-select').on('change', function() {
            var chemicalId = $(this).val();

            if (chemicalId) {
                $.ajax({
                    url: '/i/threshold/' + chemicalId,
                    type: 'GET',
                    success: function(data) {
                        $('#inventory-select').empty();
                        $('#inventory-select').append('<option value="">Select Inventory</option>');

                        $.each(data, function(key, inventory) {
                            $('#inventory-select').append('<option value="' + inventory.id + '">' + inventory.serial_number + '</option>');
                        });
                    }
                });
            } else {
                $('#inventory-select').empty().append('<option value="">Select Inventory</option>');
            }
        });
    });
</script> --}}


{{-- @endsection --}}


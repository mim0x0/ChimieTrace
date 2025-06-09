@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-body">
                    <h3 class="card-title mb-4"><i class="bi bi-envelope-check me-2"></i>Create a New Request</h3>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ url('/request') }}">
                        @csrf

                        {{-- Type --}}
                        <div class="mb-3">
                            <label for="type" class="form-label fw-semibold">Request Type</label>
                            <select id="type" name="type" class="form-select @error('type') is-invalid @enderror">
                                <option value="">-- Select Type --</option>
                                <option value="chemical" {{ old('type') == 'chemical' ? 'selected' : '' }}>Chemical</option>
                                <option value="inventory" {{ old('type') == 'inventory' ? 'selected' : '' }}>Inventory</option>
                                <option value="market" {{ old('type') == 'market' ? 'selected' : '' }}>Market</option>
                                <option value="user" {{ old('type') == 'user' ? 'selected' : '' }}>User</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Item --}}
                        <div class="mb-3">
                            <label for="item_id" class="form-label fw-semibold">Select Item</label>
                            <select id="item_id" name="item_id" class="form-select @error('item_id') is-invalid @enderror">
                                <option value="">-- Select item --</option>
                            </select>
                            @error('item_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Request Text --}}
                        <div class="mb-3">
                            <label for="request" class="form-label fw-semibold">Request Details</label>
                            <textarea name="request" id="request" class="form-control @error('request') is-invalid @enderror" rows="4" placeholder="Describe your request...">{{ old('request') }}</textarea>
                            @error('request')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Submit --}}
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-send me-1"></i> Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- jQuery for AJAX --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    const oldItemId = "{{ old('item_id') }}";

    $(document).ready(function () {
        const oldType = "{{ old('type') }}";
        if (oldType) {
            $('#type').val(oldType).trigger('change');
        }
    });

    $('#type').on('change', function () {
        const type = $(this).val();
        $('#item_id').html('<option value="">Loading...</option>');

        if (!type) {
            $('#item_id').html('<option value="">-- Select item --</option>');
            return;
        }

        $.get(`/request/${type}`, function (data) {
            let options = '<option value="">-- Select item --</option>';
            options += `<option value="-1">-- Not Exist in System --</option>`;
            data.forEach(function (item) {
                const selected = item.id == oldItemId ? 'selected' : '';
                options += `<option value="${item.id}">${item.label}</option>`;
            });
            $('#item_id').html(options);
        });
    });
</script>
@endsection







@if($chemicals->count() > 0)

    {{-- <table class="table table-bordered" >
        <thead>
            <tr>
                <th class="col-chemical_name">Chemical Name</th>
                <th class="col-CAS_number">CAS Number</th>
                <th class="col-empirical_formula">Empirical Formula</th>
                <th class="col-ec_number">EC Number</th>
                <th class="col-molecular_weight">Molecular Weight</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($chemicals as $chemical)
                <tr onclick="window.location='{{ url('/i/' . $chemical->id) }}';" style="cursor: pointer;">
                    <td class="col-chemical_name">{{ $chemical->chemical_name }}</td>
                    <td class="col-CAS_number">{{ $chemical->CAS_number }}</td>
                    <td class="col-empirical_formula">{{ $chemical->empirical_formula }}</td>
                    <td class="col-ec_number">{{ $chemical->ec_number }}</td>
                    <td class="col-molecular_weight">{{ $chemical->molecular_weight }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-3">
        {{ $chemicals->appends(request()->except('page'))->links() }}
    </div> --}}

    <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="col-chemical_name text-center">Chemical Name</th>
                            <th class="col-CAS_number text-center">CAS Number</th>
                            <th class="col-empirical_formula text-center">Formula</th>
                            {{-- <th class="col-ec_number">EC Number</th>
                            <th class="col-molecular_weight">Mol. Weight</th> --}}
                            <th class="always-visible text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($chemicals as $chem)
                            <tr>
                                <td class="col-chemical_name text-center">{{ $chem->chemical_name }}</td>
                                <td class="col-CAS_number text-center">{{ $chem->CAS_number }}</td>
                                <td class="col-empirical_formula text-center">{{ $chem->empirical_formula }}</td>
                                {{-- <td class="col-ec_number">{{ $chem->ec_number }}</td>
                                <td class="col-molecular_weight">{{ $chem->molecular_weight }}</td> --}}
                                <td class="always-visible text-center">
                                    <a href="{{ url('/i/' . $chem->id) }}" class="btn btn-sm btn-outline-secondary">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-3">
                {{-- {{ $chemicals->withQueryString()->links() }} --}}
                {{ $chemicals->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
            </div>
        </div>

@else
    <p>No results found</p>
@endif

<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Alert;
use App\Models\Brand;
use GuzzleHttp\Client;
use App\Models\Chemical;
use App\Models\Inventory;
use Faker\Provider\Image;
use App\Models\UserRequest;
use App\Models\SerialNumber;
use Illuminate\Http\Request;
use App\Models\InventoryUsage;
use Illuminate\Validation\Rule;
use App\Models\ChemicalProperty;
use App\Models\RegisteredChemical;

class InventoriesController extends Controller
{
    public function __construct() {
        $this->middleware(['auth', 'banned']);
    }

    public function createChemical() {
        // $chemicals = Chemical::all();
        $this->authorize('create', Chemical::class);
        return view('inventories.createChemical');
    }

    public function storeChemical() {
        $this->authorize('create', Chemical::class);

        $data = request()->validate([
            // 'chemical_id' => ['required', 'exists:chemicals,id'],
            'chemical_name' => ['required', 'string'],
            'CAS_number' => ['required', 'string', 'unique:chemicals,CAS_number'],
            'empirical_formula' => ['required', 'string'],
            // 'molecular_weight' => ['required', 'numeric'],
            // 'ec_number' => ['required', 'string'],
            // 'image' => ['required', 'image'],
            'chemical_structure' => ['required', 'image'],
            'SDS_file' => ['required', 'mimes:pdf'],
            // 'reg_by' => ['required', 'string'],

            // 'location' => ['nullable', 'string'],
            // 'quantity' => ['nullable', 'numeric'],
            // 'acq_at' => ['nullable', 'date'],
            // 'exp_at' => ['nullable', 'date'],
        ]);

        $chemical = Chemical::where('CAS_number', request('CAS_number'))->first();

        if ($chemical) {
            return redirect()->back()->with('success', 'Chemical already registered');
        } else {
            // $imagePath = request('image')->store('uploads', 'public');
            $structurePath = request('chemical_structure')->store('uploads', 'public');
            $SDSPath = request('SDS_file')->store('uploads', 'public');

            // $image = Image::make(public_path("storage/{$imagePath}"))->fit(1200, 800);
            // $image->save();

            // auth()->user()->inventories()->create([
            //     'chemical_name' => $data['chemical_name'],
            //     'image' => $imagePath,
            // ]);

            auth()->user()->chemicals()->create([
                'chemical_name' => $data['chemical_name'],
                'CAS_number' => $data['CAS_number'],
                // 'ec_number' => $data['ec_number'],
                'empirical_formula' => $data['empirical_formula'],
                // 'molecular_weight' => $data['molecular_weight'],
                // 'image' => $imagePath,
                'chemical_structure' => $structurePath,
                'SDS_file' => $SDSPath,
                'reg_by' => auth()->user()->name,
            ]);

            // auth()->user()->inventories()->create(array_merge(
            //     $data,

            // ));
        }

        // auth()->user()->inventories()->create([
        //     'user_id' => auth()->id(),
        //     'chemical_id' => $chemical->id,
        //     'location' => $data['location'],
        //     'quantity' => $data['quantity'],
        //     'acq_at' => $data['acq_at'],
        //     'exp_at' => $data['exp_at'],
        // ]);
        // activity('chemical')
        //     ->withProperties()
        //     ->log(auth()->user()->name . ' has created ' . $data['chemical_name'] . '(' . $data['CAS_number'] . ')');

        return redirect('/inventory')->with('success', 'Chemical added successfully');
    }

    public function editChemical(Chemical $chemical){
        // dd($chemical);
        $this->authorize('update', $chemical);
        return view('inventories.editChemical', compact('chemical'));
    }

    public function updateChemical(Request $request, Chemical $chemical){
        $this->authorize('update', $chemical);

        $data = $request->validate([
            'chemical_name' => ['required', 'string'],
            'CAS_number' => ['required', 'string', Rule::unique('chemicals')->ignore($chemical->id)],
            'empirical_formula' => ['required', 'string'],
            // 'molecular_weight' => ['required', 'numeric'],
            // 'ec_number' => ['required', 'string'],
            // 'image' => ['nullable', 'image'],
            'chemical_structure' => ['nullable', 'image'],
            'SDS_file' => ['nullable', 'mimes:pdf'],
        ]);

        // if ($request->hasFile('image')) {
        //     $data['image'] = $request->file('image')->store('image', 'public');
        // } else {
        //     $data['image'] = $chemical->image;
        // }

        if ($request->hasFile('chemical_structure')) {
            $data['chemical_structure'] = $request->file('chemical_structure')->store('chemical_structure', 'public');
        } else {
            $data['chemical_structure'] = $chemical->chemical_structure;
        }

        if ($request->hasFile('SDS_file')) {
            $data['SDS_file'] = $request->file('SDS_file')->store('SDS_file', 'public');
        } else {
            $data['SDS_file'] = $chemical->SDS_file;
        }

        $chemical->update($data);

        return redirect('/i/'.$chemical->id)->with('success', 'Chemical updated successfully');
    }

    public function editChemicalProperty(Chemical $chemical){
        $this->authorize('update', $chemical);
        $property = $chemical->properties ?? new ChemicalProperty();
        return view('inventories.editChemicalProperty', compact('chemical', 'property'));
    }

    public function updateChemicalProperty(Request $request, Chemical $chemical){
        $this->authorize('update', $chemical);

        $data = $request->validate([
            'color' => 'nullable|string|max:255',
            'physical_state' => 'nullable|string|max:255',
            'melting_point' => 'nullable|numeric',
            'boiling_point' => 'nullable|numeric',
            'flammability' => 'nullable|string|max:255',
            'other_details' => 'nullable|string',
        ]);

        $chemical->properties()->updateOrCreate([],$data);

        return redirect('/i/'.$chemical->id)->with('success', 'Chemical Property updated successfully');
    }

    public function deleteChemical(Chemical $chemical){
        $this->authorize('delete', $chemical);
        // dd($chemical);
        $chemical->delete();

        return redirect('/inventory')->with('success', 'Offer deleted successfully');
    }

    public function createInventory(Chemical $chemical) {
        $this->authorize('create', Inventory::class);
        // $chemicals = Chemical::all();
        $users = User:: where('role', '=', config('roles.supplier'))->get();
        $brands = Brand::orderBy('name')->get();
        return view('inventories.createInventory', compact('chemical', 'users', 'brands'));
    }

    public function storeInventory(Chemical $chemical) {
        $this->authorize('create', Inventory::class);

        $data = request()->validate([
            'chemical_id' => ['required', 'exists:chemicals,id'],
            // 'chemical_name' => ['required', 'required_without:chemical_id'],
            // 'description' => ['required', 'string'],
            'serial_number' => ['required', 'string', 'unique:serial_numbers,serial_number'],
            'notes' => ['nullable', 'string'],
            'brand' => ['nullable', 'string'],
            // 'chemical_structure' => ['required', 'image'],
            // 'SDS_file' => ['required', 'mimes:pdf'],

            'location' => ['required', 'string'],
            'packaging_type' => ['required', 'string'],
            'quantity' => ['required', 'numeric'],
            'unit' => ['required', 'string'],
            'acq_at' => ['required', 'date'],
            'exp_at' => ['required', 'date'],
            'container_count' => ['required', 'integer', 'min:1'],
            'min_quantity' => ['required', 'numeric'],
            // 'add_by' => ['required', 'string'],
        ]);

        // dd(request()->all());

        // if (request()->chemical_id) {
        //     $chemical = Chemical::find(request()->chemical_id);
        // } else {
        //     $imagePath = request('image')->store('uploads', 'public');
        //     $structurePath = request('chemical_structure')->store('uploads', 'public');
        //     $SDSPath = request('SDS_file')->store('uploads', 'public');

        //     // $image = Image::make(public_path("storage/{$imagePath}"))->fit(1200, 800);
        //     // $image->save();

        //     // auth()->user()->inventories()->create([
        //     //     'chemical_name' => $data['chemical_name'],
        //     //     'image' => $imagePath,
        //     // ]);

        //     auth()->user()->chemicals()->create([
        //         'chemical_name' => $data['chemical_name'],
        //         'CAS_number' => $data['CAS_number'],
        //         'serial_number' => $data['serial_number'],
        //         'SKU' => $data['SKU'],
        //         ['image' => $imagePath],
        //         ['chemical_structure' => $structurePath],
        //         ['SDS_file' => $SDSPath],
        //     ]);

        //     // auth()->user()->inventories()->create(array_merge(
        //     //     $data,

        //     // ));
        // }

        for ($i = 0; $i < $data['container_count']; $i++) {
            $count = $i + 1;
            auth()->user()->inventories()->create([
                'user_id' => auth()->id(),
                'chemical_id' => $data['chemical_id'],
                'location' => $data['location'],
                'packaging_type' => $data['packaging_type'],
                'quantity' => $data['quantity'],
                'unit' => $data['unit'],
                'acq_at' => $data['acq_at'],
                'exp_at' => $data['exp_at'],
                'add_by' => auth()->user()->name,
                // 'description' => $data['description'],
                'serial_number' => $data['serial_number'],
                'notes' => $data['notes'],
                'brand' => $data['brand'],
                'min_quantity' => $data['min_quantity'],
                'container_number' => $count,
            ]);
        }

        // $serial = SerialNumber::where('serial_number', $data['serial_number'])->first();

        // dd($serial);

        // if ($serial) {
        //     // Serial number already exists, increment the counter
        //     $serial->increment('counter', $data['container_count']);
        // } else {
            // Create new serial number with initial counter
        $serial = SerialNumber::create([
            'serial_number' => $data['serial_number'],
            'counter' => $data['container_count'],
        ]);
        // }

        if ($serial && $serial->status === 'notified') {
            $serial->buy_status = 'safe';
            $serial->save();
        }

        return redirect('/i/'.$chemical->id)->with('success', 'Inventory added successfully');
    }

    public function addInventory(Inventory $inventory) {
        $this->authorize('create', Inventory::class);
        // $chemicals = Chemical::all();
        $users = User:: where('role', '=', config('roles.supplier'))->get();
        $brands = Brand::orderBy('name')->get();
        return view('inventories.addInventory', compact('inventory', 'users', 'brands'));
    }

    public function storeAddInventory(Inventory $inventory) {
        $this->authorize('create', Inventory::class);

        $data = request()->validate([
            'chemical_id' => ['required', 'exists:chemicals,id'],
            // 'chemical_name' => ['required', 'required_without:chemical_id'],
            // 'description' => ['required', 'string'],
            'serial_number' => ['required', 'string', 'exists:serial_numbers,serial_number'],
            'notes' => ['nullable', 'string'],
            'brand' => ['nullable', 'string'],
            // 'chemical_structure' => ['required', 'image'],
            // 'SDS_file' => ['required', 'mimes:pdf'],

            'location' => ['required', 'string'],
            'packaging_type' => ['required', 'string'],
            'quantity' => ['required', 'numeric'],
            'unit' => ['required', 'string'],
            'acq_at' => ['required', 'date'],
            'exp_at' => ['required', 'date'],
            'container_count' => ['required', 'integer', 'min:1'],
            'min_quantity' => ['required', 'numeric'],
            // 'add_by' => ['required', 'string'],
        ]);

        $serial = SerialNumber::where('serial_number', $data['serial_number'])->first();
        $startCounter = $serial->counter + 1;

        for ($i = 0; $i < $data['container_count']; $i++) {
            $count = $startCounter + $i;

            auth()->user()->inventories()->create([
                'user_id' => auth()->id(),
                'chemical_id' => $data['chemical_id'],
                'location' => $data['location'],
                'packaging_type' => $data['packaging_type'],
                'quantity' => $data['quantity'],
                'unit' => $data['unit'],
                'acq_at' => $data['acq_at'],
                'exp_at' => $data['exp_at'],
                'add_by' => auth()->user()->name,
                // 'description' => $data['description'],
                'serial_number' => $data['serial_number'],
                'notes' => $data['notes'],
                'brand' => $data['brand'],
                'min_quantity' => $data['min_quantity'],
                'container_number' => $count,
            ]);
        }

        if ($serial && $serial->status === 'notified') {
            $serial->status = 'safe';
            $serial->save();
        }

        // dd($serial);

        $serial->increment('counter', $data['container_count']);

        return redirect('/i/'.$inventory->chemical->id)->with('success', 'Inventory added successfully');
    }

    public function editInventory(Inventory $inventory){
        // dd($inventory);
        $this->authorize('update', $inventory);
        $users = User:: where('role', '=', config('roles.supplier'))->get();
        $brands = Brand::orderBy('name')->get();
        return view('inventories.editInventory', compact('inventory', 'users', 'brands'));
    }

    public function updateInventory(Request $request, Inventory $inventory){
        $this->authorize('update', $inventory);

        $data = $request->validate([
            // 'chemical_id' => ['required'],
            // 'description' => ['required', 'string'],
            'serial_number' => ['required', 'string'],
            'notes' => ['nullable', 'string'],
            'brand' => ['nullable', 'string'],

            'location' => ['required', 'string'],
            'quantity' => ['required', 'numeric'],
            'acq_at' => ['required', 'date'],
            'exp_at' => ['required', 'date'],
            'min_quantity' => ['required', 'numeric'],
            // 'container_count' => ['required', 'integer', 'min:1'],
        ]);


        $inventory->update($data);
        Inventory::where('serial_number', $inventory->serial_number)
                    ->update(['min_quantity' => $data['min_quantity']]);

        return redirect('/i/'.$inventory->chemical->id)->with('success', 'Chemical updated successfully');
    }

    public function deleteInventory(Inventory $inventory){
        $this->authorize('delete', $inventory);
        // dd($inventory);
        $inventory->delete();

        return redirect('/inventory')->with('success', 'Offer deleted successfully');
    }

    public function index(Request $request) {
        $this->authorize('viewAny', Chemical::class);
        // $chemicals = Chemical::paginate(3);
        $query = $request->input('search');

        $filters = $request->input('filters', []);

        $chemicals = Chemical::where('chemical_name', 'LIKE', "%{$query}%")
                ->orWhere('CAS_number', 'LIKE', "%{$query}%")
                ->orWhere('empirical_formula', 'LIKE', "%{$query}%")
                ->orWhere('ec_number', 'LIKE', "%{$query}%")
                ->orWhere('molecular_weight', 'LIKE', "%{$query}%")
                ->paginate(10);


        // $chemicals = Inventory::whereHas('chemical', function ($q) use ($query) {
        //     $q->where('chemical_name', 'LIKE', "%{$query}%")
        //         ->orWhere('CAS_number', 'LIKE', "%{$query}%")
        //         ->orWhere('serial_number', 'LIKE', "%{$query}%")
        //         ->orWhere('SKU', 'LIKE', "%{$query}%");
        // })
        // ->orWhere('location', 'LIKE', "%{$query}%")
        // ->orWhere('quantity', 'LIKE', "%{$query}%")
        // ->orWhere('exp_at', 'LIKE', "%{$query}%")
        // ->paginate(5);

        if ($request->ajax()) {
            return view('inventories._search', compact('chemicals'))->render();
        }

        return view('inventories.index', compact('chemicals'));
    }

    // public function search(Request $request){
    //     $this->authorize('viewAny', Chemical::class);
    //     $query = $request->input('search');

    //     $filters = $request->input('filters', []);

    //     $chemicals = Chemical::where('chemical_name', 'LIKE', "%{$query}%")
    //             ->orWhere('CAS_number', 'LIKE', "%{$query}%")
    //             ->orWhere('serial_number', 'LIKE', "%{$query}%")
    //             ->orWhere('SKU', 'LIKE', "%{$query}%")
    //             ->paginate(3);


    //     // $chemicals = Inventory::whereHas('chemical', function ($q) use ($query) {
    //     //     $q->where('chemical_name', 'LIKE', "%{$query}%")
    //     //         ->orWhere('CAS_number', 'LIKE', "%{$query}%")
    //     //         ->orWhere('serial_number', 'LIKE', "%{$query}%")
    //     //         ->orWhere('SKU', 'LIKE', "%{$query}%");
    //     // })
    //     // ->orWhere('location', 'LIKE', "%{$query}%")
    //     // ->orWhere('quantity', 'LIKE', "%{$query}%")
    //     // ->orWhere('exp_at', 'LIKE', "%{$query}%")
    //     // ->paginate(5);

    //     return view('inventories._search', compact('chemicals'))->render();
    // }

    public function details(Chemical $chemical, Request $request) {
        $this->authorize('viewAny', Inventory::class);
        // $chemical = $inventory->chemical();
        // dd($chemical->id, $chemical->chemical_name);
        // $inventories = Inventory::where('chemical_id', $chemical->id)->where('status', '!=', 'disabled')->with('chemical')->get();
        $query = $request->input('search');
        $filters = $request->input('filters', []);

        // $chemicals = Chemical::where('chemical_name', 'LIKE', "%{$query}%")
        //         ->orWhere('CAS_number', 'LIKE', "%{$query}%")
        //         ->orWhere('serial_number', 'LIKE', "%{$query}%")
        //         ->orWhere('SKU', 'LIKE', "%{$query}%")
        //         ->paginate(3);

        $user = auth()->user();
        if ($user->role === config('roles.admin')) {
            $inventories = Inventory::where('chemical_id', $chemical->id)
            ->where(function ($q) use ($query) {
                $q->where('description', 'LIKE', "%{$query}%")
                    ->orWhere('location', 'LIKE', "%{$query}%")
                    ->orWhere('packaging_type', 'LIKE', "%{$query}%")
                    ->orWhere('quantity', 'LIKE', "%{$query}%")
                    ->orWhere('unit', 'LIKE', "%{$query}%")
                    ->orWhere('status', 'LIKE', "%{$query}%")
                    ->orWhere('serial_number', 'LIKE', "%{$query}%")
                    ->orWhere('notes', 'LIKE', "%{$query}%")
                    ->orWhere('brand', 'LIKE', "%{$query}%")
                    ->orWhere('add_by', 'LIKE', "%{$query}%")
                    ->orWhere('acq_at', 'LIKE', "%{$query}%")
                    ->orWhere('exp_at', 'LIKE', "%{$query}%");
            })
            ->paginate(5);
        } else {
            $inventories = Inventory::where('chemical_id', $chemical->id)->where('status', '!=', 'disabled')->with('chemical')
            ->where(function ($q) use ($query) {
                $q->where('description', 'LIKE', "%{$query}%")
                    ->orWhere('location', 'LIKE', "%{$query}%")
                    ->orWhere('packaging_type', 'LIKE', "%{$query}%")
                    ->orWhere('quantity', 'LIKE', "%{$query}%")
                    ->orWhere('unit', 'LIKE', "%{$query}%")
                    ->orWhere('status', 'LIKE', "%{$query}%")
                    ->orWhere('serial_number', 'LIKE', "%{$query}%")
                    ->orWhere('notes', 'LIKE', "%{$query}%")
                    ->orWhere('brand', 'LIKE', "%{$query}%")
                    ->orWhere('add_by', 'LIKE', "%{$query}%")
                    ->orWhere('acq_at', 'LIKE', "%{$query}%")
                    ->orWhere('exp_at', 'LIKE', "%{$query}%");
            })
            ->paginate(5);
        }

        if ($request->ajax()) {
            return view('inventories._searchDetail', compact( 'inventories'))->render();
        }

        return view('inventories.details', compact('chemical', 'inventories'));
    }

    public function reduceQuantity(Inventory $inventory) {
        $this->authorize('use', Inventory::class);

        if ($inventory->status === 'sealed') {
            return back()->with('error', 'This inventory is sealed and cannot be used.');
        }
        return view('inventories.useInventory', compact('inventory'));
    }

    public function storeReduce(Inventory $inventory) {
        $this->authorize('use', Inventory::class);
        // dd(Inventory::where('chemical_id', $inventory->chemical_id)->count() < 2);
        $chemical = $inventory->chemical_id;
        $data = request()->validate([
            'quantity_used' => ['required', 'numeric', 'max:' . $inventory->quantity],
            'reason' => ['required', 'string'],
        ]);

        // if ($inventory->status === 'sealed') {
        //     return back()->with('error', 'This inventory is sealed and cannot be used.');
        // }

        // Deduct quantity
        $inventory->decrement('quantity', $data['quantity_used']);

        $containerCount = Inventory::where('serial_number', $inventory->serial_number)
                                    ->where('quantity', '>', 0)
                                    ->count();

        // Log the usage
        auth()->user()->inventory_usages()->create([
            'user_id' => auth()->id(),
            'inventory_id' => $inventory->id,
            'user_name' => $inventory->user->name,
            'chemical_name' => $inventory->chemical->chemical_name,
            'chemical_cas' => $inventory->chemical->CAS_number,
            'inventory_serial' => $inventory->serial_number,
            'quantity_used' => $data['quantity_used'],
            'quantity_left' => $inventory->quantity,
            'container_left' => $containerCount,
            'reason' => $data['reason'],
        ]);


        // **Check if quantity is below the threshold**
        // $totalQuantity = Inventory::where('chemical_id', $inventory->chemical_id)->sum('quantity');
        // if (Inventory::where('chemical_id', $inventory->chemical_id)->count() < 2) { // Adjust threshold as needed
        //     Alert::create([
        //         'inventory_id' => $inventory->id,
        //         // 'user_id' => '1',
        //         'message' => "Warning: Low stock for {$inventory->chemical->chemical_name}",
        //         'receiver_type' => 'admin',
        //     ]);
        // }

        if ($inventory->quantity <= 0) {
            $inventory->update(['status' => 'empty']);
            // $inventory->status = 'disabled'; // Mark inventory as disabled
            // $inventory->save();

            // Notify admin of depletion
            // $userRequest = auth()->user()->userRequests()->create([
            $userRequest = UserRequest::create([
                // 'inventory_id' => $inventory->id,
                // 'user_id' => auth()->id(),
                'type' => 'inventory',
                'item_id' => $inventory->id,
                'request' => "Container for one of {$inventory->chemical->chemical_name} ({$inventory->serial_number} #{$inventory->container_number}) is depleted.
                                Please handle the empty container.",
                'receiver_type' => 'admin',
            ]);

            Alert::create([
                // 'inventory_id' => $inventory->id,
                // 'user_id' => auth()->id(),
                'user_request_id' => $userRequest->id,
                'message' => $userRequest['request'],
                'receiver_type' => $userRequest['receiver_type'],
            ]);
        } else {
            $inventory->save();
        }

        $totalQuantity = Inventory::where('serial_number', $inventory->serial_number)->sum('quantity');
        $serial = SerialNumber::where('serial_number', $inventory->serial_number)->first();

        if ($totalQuantity < $inventory->min_quantity && $serial && $serial->status !== 'notified') {
            // $userRequest = auth()->user()->userRequests()->create([
            $userRequest = UserRequest::create([
                // 'inventory_id' => $inventory->id,
                // 'user_id' => auth()->id(),
                'type' => 'inventory',
                'item_id' => $inventory->id,
                'request' => "Warning: Threshold reached for {$inventory->chemical->chemical_name} ({$inventory->serial_number}).
                                Please restock as soon as possible.",
                'receiver_type' => 'admin',
            ]);
            Alert::create([
                'inventory_id' => $inventory->id,
                // 'user_id' => auth()->id(),
                'user_request_id' => $userRequest->id,
                'message' => $userRequest['request'],
                'receiver_type' => $userRequest['receiver_type'],
            ]);

            $serial->status = 'notified';
            $serial->save();
        }

        return redirect('/i/'.$chemical)->with('success', 'Quantity reduced successfully');
    }

    public function destroy(Inventory $inventory) {
        $this->authorize('delete', $inventory);

        if ($inventory->status == 'empty') {
            $inventory->delete();
            return back()->with('success', 'Depleted inventory deleted successfully.');
        }

        return back()->with('error', 'Inventory must be disabled before deletion.');
    }

    public function autocomplete(Request $request){
        $query = $request->input('query');

        return RegisteredChemical::where('chemical_name', 'LIKE', "%{$query}%")
            ->limit(10)
            ->get(['chemical_name', 'CAS_number', 'empirical_formula']);
    }

    public function unseal(Inventory $inventory) {
        $this->authorize('unseal', Inventory::class);
        // $inventory = Inventory::findOrFail($id);
        // dd($inventory->id);
        $inventory->update(['status' => 'opened']);
        return back()->with('success', 'Inventory unsealed successfully.');
    }

    // public function editThreshold(Inventory $inventory) {

    //     return view('inventories.inventoryThreshold', compact('inventory'));
    // }

    // public function getByChemical(Chemical $chemical) {
    //     $inventories = Inventory::where('chemical_id', $chemical)->get();

    //     return response()->json($inventories);
    // }

    // public function storeThreshold(Inventory $inventory) {
    //     // $data = request()->validate([
    //     //     'chemical_id' => ['required|exists:chemicals,id'],
    //     //     'id' => ['required|exists:inventories,id'],
    //     //     'min_quantity' => ['required', 'numeric'],
    //     // ]);

    //     // Inventory::where('id', $data['id'])->update([
    //     //     'min_quantity' => $data['min_quantity'],
    //     // ]);

    //     // return back()->with('success', 'Threshold set successfully');
    //     $data = request()->validate([
    //         'min_quantity' => ['required', 'numeric'],
    //     ]);

    //     // dd($inventory);


    //     // $inventory->update($data);
    //     Inventory::where('serial_number', $inventory->serial_number)
    //                 ->update(['min_quantity' => $data['min_quantity']]);

    //     return redirect('/i/'.$inventory->chemical->id)->with('success', 'Chemical Container threshold updated successfully');
    // }
}

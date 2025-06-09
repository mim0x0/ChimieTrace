<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Alert;
use GuzzleHttp\Client;
use App\Models\Chemical;
use App\Models\Inventory;
use Faker\Provider\Image;
use Illuminate\Http\Request;
use App\Models\InventoryUsage;
use Illuminate\Validation\Rule;

class InventoriesController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function createChemical() {
        // $chemicals = Chemical::all();
        $this->authorize('create', Chemical::class);
        return view('inventories.createChemical');
    }

    public function storeChemical() {
        $data = request()->validate([
            // 'chemical_id' => ['required', 'exists:chemicals,id'],
            'chemical_name' => ['required', 'string'],
            'CAS_number' => ['required', 'string', 'unique:chemicals,CAS_number'],
            'empirical_formula' => ['required', 'string'],
            'molecular_weight' => ['required', 'numeric'],
            'ec_number' => ['required', 'string'],
            'image' => ['required', 'image'],
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
            $imagePath = request('image')->store('uploads', 'public');
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
                'ec_number' => $data['ec_number'],
                'empirical_formula' => $data['empirical_formula'],
                'molecular_weight' => $data['molecular_weight'],
                'image' => $imagePath,
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
            'molecular_weight' => ['required', 'numeric'],
            'ec_number' => ['required', 'string'],
            'image' => ['nullable', 'image'],
            'chemical_structure' => ['nullable', 'image'],
            'SDS_file' => ['nullable', 'mimes:pdf'],
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('image', 'public');
        } else {
            $data['image'] = $chemical->image;
        }

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

    public function deleteChemical(Chemical $chemical){
        $this->authorize('delete', $chemical);
        // dd($chemical);
        $chemical->delete();

        return redirect('/inventory')->with('success', 'Offer deleted successfully');
    }

    public function createInventory() {
        $this->authorize('create', Inventory::class);
        $chemicals = Chemical::all();
        $users = User:: where('role', '=', 'supplier')->get();
        return view('inventories.createInventory', compact('chemicals', 'users'));
    }

    public function storeInventory() {
        $data = request()->validate([
            'chemical_id' => ['required', 'exists:chemicals,id'],
            // 'chemical_name' => ['required', 'required_without:chemical_id'],
            'description' => ['required', 'string'],
            'serial_number' => ['required', 'string'],
            'notes' => ['required', 'string'],
            'brand' => ['required', 'exists:users,name'],
            // 'chemical_structure' => ['required', 'image'],
            // 'SDS_file' => ['required', 'mimes:pdf'],

            'location' => ['required', 'string'],
            'quantity' => ['required', 'numeric'],
            'acq_at' => ['required', 'date'],
            'exp_at' => ['required', 'date'],
            'container_count' => ['required', 'integer', 'min:1'],
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
            auth()->user()->inventories()->create([
                'user_id' => auth()->id(),
                'chemical_id' => $data['chemical_id'],
                'location' => $data['location'],
                'quantity' => $data['quantity'],
                'acq_at' => $data['acq_at'],
                'exp_at' => $data['exp_at'],
                'add_by' => auth()->user()->name,
                'description' => $data['description'],
                'serial_number' => $data['serial_number'],
                'notes' => $data['notes'],
                'brand' => $data['brand'],
            ]);
        }

        return redirect('/inventory')->with('success', 'Inventory added successfully');
    }

    public function editInventory(Inventory $inventory){
        // dd($inventory);
        $this->authorize('update', $inventory);
        return view('inventories.editInventory', compact('inventory'));
    }

    public function updateInventory(Request $request, Inventory $inventory){
        $this->authorize('update', $inventory);

        $data = $request->validate([
            // 'chemical_id' => ['required'],
            'description' => ['required', 'string'],
            // 'serial_number' => ['nullable', 'string'],
            'notes' => ['required', 'string'],
            // 'brand' => ['nullable'],

            'location' => ['required', 'string'],
            'quantity' => ['required', 'numeric'],
            'acq_at' => ['required', 'date'],
            'exp_at' => ['required', 'date'],
            // 'container_count' => ['required', 'integer', 'min:1'],
        ]);


        $inventory->update($data);

        return redirect('/i/'.$inventory->chemical->id)->with('success', 'Chemical updated successfully');
    }

    public function deleteInventory(Inventory $inventory){
        $this->authorize('delete', $inventory);
        // dd($inventory);
        $inventory->delete();

        return redirect('/inventory')->with('success', 'Offer deleted successfully');
    }

    public function index() {
        $this->authorize('viewAny', Chemical::class);
        $chemicals = Chemical::paginate(3);
        return view('inventories.index', compact('chemicals'));
    }

    public function search(Request $request){
        $query = $request->input('search');

        $filters = $request->input('filters', []);

        $chemicals = Chemical::where('chemical_name', 'LIKE', "%{$query}%")
                ->orWhere('CAS_number', 'LIKE', "%{$query}%")
                ->orWhere('serial_number', 'LIKE', "%{$query}%")
                ->orWhere('SKU', 'LIKE', "%{$query}%")
                ->paginate(3);


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

        return view('inventories.search', compact('chemicals'))->render();
    }

    public function show(Chemical $chemical) {
        // $chemical = $inventory->chemical();
        // dd($chemical->id, $chemical->chemical_name);
        // $inventories = Inventory::where('chemical_id', $chemical->id)->where('status', '!=', 'disabled')->with('chemical')->get();
        $user = auth()->user();
        if (strpos($user->email, '@admin.com') !== false) {
            $inventories = Inventory::where('chemical_id', $chemical->id)->get();
        } else {
            $inventories = Inventory::where('chemical_id', $chemical->id)->where('status', '!=', 'disabled')->with('chemical')->get();
        }

        return view('inventories.show', compact('chemical', 'inventories'));
    }

    // public function scrape(Request $request) {
    //     $url = $request->get('url');

    //     $client = new Client();

    //     $response = $client->request(
    //         'get',
    //         $url,
    //     );

    //     $response_status = $response->getStatusCode();
    //     $response_body = $response->getBody()->getContents();

    //     if($response_status == 200) {
    //         dd($response_body);
    //     };
    // }

    public function reduceQuantity(Inventory $inventory) {
        if ($inventory->status === 'sealed') {
            return back()->with('error', 'This inventory is sealed and cannot be used.');
        }
        return view('inventories.useInventory', compact('inventory'));
    }

    public function storeReduce(Inventory $inventory) {
        // dd(Inventory::where('chemical_id', $inventory->chemical_id)->count() < 2);
        $chemical = $inventory->chemical_id;
        $data = request()->validate([
            'quantity_used' => ['required', 'numeric', 'max:' . $inventory->quantity],
            'reason' => ['required', 'string'],
        ]);

        // if ($inventory->status === 'sealed') {
        //     return back()->with('error', 'This inventory is sealed and cannot be used.');
        // }

        // Log the usage
        auth()->user()->inventory_usages()->create([
            'user_id' => auth()->id(),
            'inventory_id' => $inventory->id,
            'quantity_used' => $data['quantity_used'],
            'reason' => $data['reason'],
        ]);

        // Deduct quantity
        $inventory->decrement('quantity', $data['quantity_used']);

        // **Check if quantity is below the threshold**
        // $totalQuantity = Inventory::where('chemical_id', $inventory->chemical_id)->sum('quantity');
        if (Inventory::where('chemical_id', $inventory->chemical_id)->count() < 2) { // Adjust threshold as needed
            Alert::create([
                'inventory_id' => $inventory->id,
                // 'user_id' => '1',
                'message' => "Warning: Low stock for {$inventory->chemical->chemical_name}",
            ]);
        }

        if ($inventory->quantity <= 0) {
            $inventory->update(['status' => 'disabled']);
            // $inventory->status = 'disabled'; // Mark inventory as disabled
            // $inventory->save();

            // Notify admin of depletion
            Alert::create([
                'inventory_id' => $inventory->id,
                'user_id' => auth()->id(),
                'message' => "Inventory for {$inventory->chemical->chemical_name} is depleted. Admin should review and delete if necessary.",
            ]);
        } else {
            $inventory->save();
        }

        return redirect('/i/'.$chemical)->with('success', 'Quantity reduced successfully');
    }

    public function destroy(Inventory $inventory) {
        if ($inventory->status == 'disabled') {
            $inventory->delete();
            return back()->with('success', 'Depleted inventory deleted successfully.');
        }

        return back()->with('error', 'Inventory must be disabled before deletion.');
    }

    public function inventoryLog() {
        $this->authorize('viewAny', InventoryUsage::class);
        $inventoryUsage = InventoryUsage::with('inventory.chemical.user')->latest()->paginate(3);
        // dd($inventoryUsage);
        return view('inventories.inventoryLog', compact('inventoryUsage'));
    }

    public function showAlerts(){
        $this->authorize('viewAny', InventoryUsage::class);
        $alerts = Alert::where('is_read', false)->latest()->paginate(3);
        return view('inventories.alert', compact('alerts'));
    }

    public function markAsRead(Alert $alert){
        $alert->update(['is_read' => true]);
        return redirect()->back()->with('success', 'Alert marked as read');
    }

    public function unseal(Inventory $inventory) {
        // $inventory = Inventory::findOrFail($id);
        // dd($inventory->id);
        $inventory->update(['status' => 'enabled']);
        return back()->with('success', 'Inventory unsealed successfully.');
    }
}

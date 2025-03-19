<?php

namespace App\Http\Controllers;

use App\Models\Chemical;
use App\Models\Inventory;
use Faker\Provider\Image;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class InventoriesController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function createChemical() {
        // $chemicals = Chemical::all();
        return view('inventories.createChemical');
    }

    public function storeChemical() {
        $data = request()->validate([
            // 'chemical_id' => ['required', 'exists:chemicals,id'],
            'chemical_name' => ['required', 'string'],
            'CAS_number' => ['required', 'string', 'unique:chemicals,CAS_number'],
            'serial_number' => ['required', 'string'],
            'SKU' => ['required', 'string'],
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
                'serial_number' => $data['serial_number'],
                'SKU' => $data['SKU'],
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

    public function createInventory() {
        $chemicals = Chemical::all();
        return view('inventories.createInventory', compact('chemicals'));
    }

    public function storeInventory() {
        $data = request()->validate([
            'chemical_id' => ['required', 'exists:chemicals,id'],
            // 'chemical_name' => ['required', 'required_without:chemical_id'],
            // 'CAS_number' => ['required', 'required_without:chemical_id'],
            // 'serial_number' => ['required', 'string'],
            // 'SKU' => ['required', 'string'],
            // 'image' => ['required', 'image'],
            // 'chemical_structure' => ['required', 'image'],
            // 'SDS_file' => ['required', 'mimes:pdf'],

            'location' => ['required', 'string'],
            'quantity' => ['required', 'numeric'],
            'acq_at' => ['required', 'date'],
            'exp_at' => ['required', 'date'],
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

        auth()->user()->inventories()->create([
            'user_id' => auth()->id(),
            'chemical_id' => $data['chemical_id'],
            'location' => $data['location'],
            'quantity' => $data['quantity'],
            'acq_at' => $data['acq_at'],
            'exp_at' => $data['exp_at'],
            'add_by' => auth()->user()->name,
        ]);

        return redirect('/inventory')->with('success', 'Inventory added successfully');
    }

    public function index() {
        $chemicals = Chemical::paginate(5);
        return view('inventories.index', compact('chemicals'));
    }

    public function search(Request $request){
        $query = $request->input('search');

        $filters = $request->input('filters', []);

        $chemicals = Chemical::where('chemical_name', 'LIKE', "%{$query}%")
                ->orWhere('CAS_number', 'LIKE', "%{$query}%")
                ->orWhere('serial_number', 'LIKE', "%{$query}%")
                ->orWhere('SKU', 'LIKE', "%{$query}%")
                ->paginate(5);


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
        $inventories = Inventory::where('chemical_id', $chemical->id)->get();
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
}

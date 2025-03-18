<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Faker\Provider\Image;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class InventoriesController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function create() {
        return view('inventories.create');
    }

    public function store() {
        $data = request()->validate([
            'chemical_name' => 'required',
            'CAS_number' => 'required',
            'serial_number' => 'required',
            'location' => 'required',
            'quantity' => 'required',
            'SKU' => 'required',
            'image' => ['required', 'image'],
            'chemical_structure' => ['required', 'image'],
            'acq_at' => 'required',
            'exp_at' => 'required',
            'SDS_file' => ['required', 'mimes:pdf'],
        ]);

        $imagePath = request('image')->store('uploads', 'public');
        $structurePath = request('chemical_structure')->store('uploads', 'public');
        $SDSPath = request('SDS_file')->store('uploads', 'public');

        // $image = Image::make(public_path("storage/{$imagePath}"))->fit(1200, 800);
        // $image->save();

        // auth()->user()->inventories()->create([
        //     'chemical_name' => $data['chemical_name'],
        //     'image' => $imagePath,
        // ]);

        auth()->user()->inventories()->create(array_merge(
            $data,
            ['image' => $imagePath],
            ['chemical_structure' => $structurePath],
            ['SDS_file' => $SDSPath],
        ));

        return redirect('/inventory');
    }

    public function index() {
        $inventories = Inventory::paginate(5);
        return view('inventories.index', compact('inventories'));
    }

    public function search(Request $request){
        $query = $request->input('search');

        $filters = $request->input('filters', []);

        $inventories = Inventory::where('chemical_name', 'LIKE', "%{$query}%")
                                ->orWhere('CAS_number', 'LIKE', "%{$query}%")
                                ->orWhere('serial_number', 'LIKE', "%{$query}%")
                                ->orWhere('location', 'LIKE', "%{$query}%")
                                ->orWhere('quantity', 'LIKE', "%{$query}%")
                                ->orWhere('SKU', 'LIKE', "%{$query}%")
                                ->orWhere('exp_at', 'LIKE', "%{$query}%")
                                ->paginate(5);

        // Return the partial view with updated results
        return view('inventories.search', compact('inventories'))->render();
    }

    public function show(Inventory $inventory) {
        return view('inventories.show', compact('inventory'));
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

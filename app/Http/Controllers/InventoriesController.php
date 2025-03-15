<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Faker\Provider\Image;
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
            'exp_at' => 'required',
        ]);

        $imagePath = request('image')->store('uploads', 'public');
        $structurePath = request('chemical_structure')->store('uploads', 'public');

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
        ));

        return redirect('/inventory');
    }

    public function index() {
        $inventories = Inventory::paginate(5);
        return view('inventories.index', compact('inventories'));
    }

    public function show(Inventory $inventory) {
        return view('inventories.show', compact('inventory'));
    }
}

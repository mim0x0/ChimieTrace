<?php

namespace App\Http\Controllers;

use App\Models\Chemical;
use App\Models\Inventory;
use App\Models\Market;
use Illuminate\Http\Request;

class MarketsController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        $markets = Market::with(['inventory', 'chemical', 'user'])->paginate(3);
        return view('markets.index', compact('markets'));
    }

    public function create(){
        $inventories = Inventory::all();
        $chemicals = Chemical::all();
        return view('markets.create', compact('inventories' , 'chemicals'));
    }

    public function store(Request $request){
        $data = $request->validate([
            'inventory_id' => 'nullable|exists:inventories,id',
            'chemical_id' => 'required|exists:chemicals,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:1',
            'description' => ['nullable'],
            // 'supplier_id' => auth()->user(),
        ]);

        // dd($data);

        auth()->user()->markets()->create($data);

        return redirect('/market')->with('success', 'Offer added successfully');
    }

    public function edit(Market $market){
        $this->authorize('update', $market); // Ensure only supplier can edit their own offers
        return view('market.edit', compact('market'));
    }

    public function update(Request $request, Market $market){
        $this->authorize('update', $market);

        $data = $request->validate([
            'price' => 'required|numeric|min:0',
            'available_quantity' => 'required|integer|min:0',
        ]);

        $market->update($data);

        return redirect()->route('market.index')->with('success', 'Offer updated successfully');
    }

    public function destroy(Market $market){
        $this->authorize('delete', $market);
        $market->delete();

        return redirect()->route('market.index')->with('success', 'Offer deleted successfully');
    }
}

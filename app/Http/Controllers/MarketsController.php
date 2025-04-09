<?php

namespace App\Http\Controllers;

use App\Models\Chemical;
use App\Models\Inventory;
use App\Models\Market;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Tarsoft\Toyyibpay\Toyyibpay;

class MarketsController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        $this->authorize('viewAny', Market::class);

        // dd(auth()->user()->email);

        if (auth()->user()->role === 'supplier') {
            $markets = Market::with(['inventory', 'chemical', 'user'])->where('user_id', auth()->id())->paginate(3);
        } else {
            $markets = Market::with(['inventory', 'chemical', 'user'])->paginate(3);
        }


        return view('markets.index', compact('markets'));
    }

    public function create(){
        $inventories = Inventory::all();
        $chemicals = Chemical::all();
        return view('markets.create', compact('inventories' , 'chemicals'));
    }

    public function store(Request $request){
        $data = $request->validate([
            // 'inventory_id' => 'nullable|exists:inventories,id',
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

    public function buy(Market $markets) {

        $markets = Market::where('id', $markets->id)->with(['chemical', 'user'])->first();
        // dd($markets);
        return view('markets.buy', compact('markets'));
    }

    public function edit(Market $markets){
        // dd($markets);
        $this->authorize('update', $markets);
        return view('markets.edit', compact('markets'));
    }

    public function update(Request $request, Market $markets){
        $this->authorize('update', $markets);

        $data = $request->validate([
            'description' => ['nullable'],
            'price' => 'nullable|numeric',
            'stock' => 'nullable|integer',
        ]);

        $markets->update($data);

        return redirect('/m/'.$markets->id)->with('success', 'Offer updated successfully');
    }

    public function delete(Market $markets){
        $this->authorize('delete', $markets);
        // dd($markets);
        $markets->delete();

        return redirect('/market')->with('success', 'Offer deleted successfully');
    }




    public function getBankFPX() {
        $client = new Client();
        $toyyibpay = new Toyyibpay(true, config('toyyibpay.client_secret'), config('toyyibpay.redirect_uri'), $client);
        $data = $toyyibpay->getBanks();

        dd($data);
    }

    public function createBill(Market $markets) {

        $user = auth()->user();
        $markets = Market::where('id', $markets->id)->with(['chemical', 'user'])->first();
        $category_code = config('toyyibpay.code');

        $client = new Client();
        $toyyibpay = new Toyyibpay(true, config('toyyibpay.client_secret'), config('toyyibpay.redirect_uri'), $client);

        // dd($markets->user->name);

        $price = $markets->price * 100; // ToyyibPay calculate in cents, so multiply by 100

        $data = $toyyibpay->createBill($category_code, (object)[
            'billName' => 'Product Fee',
            'billDescription' => $markets->description,
            'billPriceSetting'=> 1,
            'billPayorInfo'=> 1,
            'billAmount'=> $price,

            // 'billReturnUrl'=> $bill_object->billReturnUrl ?? $this->redirect_uri,
            // 'billCallbackUrl'=> $bill_object->billCallbackUrl ?? $this->redirect_uri,

            'billExternalReferenceNo' => $markets->chemical->chemical_name.' '.$markets->description,
            'billTo'=> $markets->user->name,
            'billEmail'=> $markets->user->email,
            'billPhone'=> $markets->user->profile->phone_number,

        ]);

        $bill_code = $data->BillCode;
        // dd($data);
        // dd($bill_code);

        return redirect('/m/bill/'.$bill_code);
    }

    public function billPaymentLink($bill_code) {
        $client = new Client();
        $toyyibpay = new Toyyibpay(true, config('toyyibpay.client_secret'), config('toyyibpay.redirect_uri'), $client);
        // dd($toyyibpay);
        $data = $toyyibpay->billPaymentLink($bill_code);

        return redirect($data);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Notifications\BidAccepted;
use App\Notifications\NewMarket;
use Stripe\Stripe;
use Stripe\Account;
use App\Models\User;
use App\Models\Market;
use GuzzleHttp\Client;
use Stripe\AccountLink;
use App\Models\Chemical;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Tarsoft\Toyyibpay\Toyyibpay;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use PaypalServerSdkLib\Models\Order;
use Illuminate\Support\Facades\Session;

class MarketsController extends Controller
{
    public function __construct() {
        $this->middleware(['auth', 'banned']);
    }

    public function index(Request $request) {
        // $this->authorize('viewAny', Market::class);

        // dd(auth()->user()->email);

        if (auth()->user()->role === 'supplier') {
            $markets = Market::with(['inventory', 'chemical', 'user'])->where('user_id', auth()->id())->paginate(3);
        } else {
            $markets = Market::with(['inventory', 'chemical', 'user'])->paginate(3);
        }

        $query = $request->input('search');

        $filters = $request->input('filters', []);

        $markets = Market::where(function ($q) use ($query) {
            $q->whereHas('chemical', function ($subQ) use ($query) {
                $subQ->where('chemical_name', 'LIKE', "%{$query}%");
            })
            ->orWhere(function ($q) use ($query) {
                $q->whereHas('user', function ($subQ) use ($query) {
                    $subQ->where('name', 'LIKE', "%{$query}%");
                });
            })
            ->orWhere('description', 'LIKE', "%{$query}%")
            // ->orWhere('user_id', 'LIKE', "%{$query}%")
            ->orWhere('price', 'LIKE', "%{$query}%")
            ->orWhere('currency', 'LIKE', "%{$query}%")
            ->orWhere('stock', 'LIKE', "%{$query}%");
        })->paginate(3);

        if ($request->ajax()) {
            return view('markets._search', compact('markets'))->render();
        }

        return view('markets.index', compact('markets'));
    }

    public function create(){
        $this->authorize('create', Market::class);
        // $inventories = Inventory::groupBy('serial_number')->get();
        // dd($inventories);
        $chemicals = Chemical::all();
        return view('markets.create', compact( 'chemicals'));
    }

    public function createRe(Request $request){
        $this->authorize('create', Market::class);
        $inventoryId = $request->query('inventory_id');

        $inventory = $inventoryId ? Inventory::findOrFail($inventoryId) : null;

        return view('markets.createRe', compact('inventory'));
    }

    public function createOption($chemical){
        $inventories = Inventory::where('chemical_id', $chemical)
            ->whereIn('id', function ($query) {
                $query->selectRaw('MIN(id)')
                    ->from('inventories')
                    ->groupBy('serial_number');
            })->get();

        return response()->json($inventories);
    }


    // public function search(Request $request){
    //     $query = $request->input('search');

    //     $filters = $request->input('filters', []);

    //     $markets = Market::where(function ($q) use ($query) {
    //         $q->whereHas('chemical', function ($subQ) use ($query) {
    //             $subQ->where('chemical_name', 'LIKE', "%{$query}%");
    //         })
    //         ->orWhere(function ($q) use ($query) {
    //             $q->whereHas('user', function ($subQ) use ($query) {
    //                 $subQ->where('name', 'LIKE', "%{$query}%");
    //             });
    //         })
    //         ->orWhere('description', 'LIKE', "%{$query}%")
    //         // ->orWhere('user_id', 'LIKE', "%{$query}%")
    //         ->orWhere('price', 'LIKE', "%{$query}%")
    //         ->orWhere('currency', 'LIKE', "%{$query}%")
    //         ->orWhere('stock', 'LIKE', "%{$query}%");
    //     })->paginate(3);

    //     return view('markets.search', compact('markets'))->render();
    // }

    public function store(Request $request){
        $this->authorize('create', Market::class);
        $data = $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'chemical_id' => 'required|exists:chemicals,id',
            // 'price' => 'required|numeric|min:0',
            'quantity_needed' => 'required|integer|min:1',
            'unit' => ['required'],
            'notes' => ['nullable'],
        ]);

        // dd($data);

        $market = auth()->user()->markets()->create($data);

        $suppliers = User::where('role', config('roles.supplier'))->get();
        foreach ($suppliers as $supplier) {
            $supplier->notify(new NewMarket($market));
        }

        return redirect('/market')->with('success', 'Offer added successfully');
    }

    public function detail(Market $markets, Request $request) {

        $markets = Market::where('id', $markets->id)->with(['chemical', 'user'])->first();
        // dd($markets);
        $user = auth()->user();
        // $cart = $user->cart;
        // $existingQuantity = 0;

        // if ($cart) {
        //     $item = $cart->items()->where('market_id', $markets->id)->first();
        //     if ($item) {
        //         $existingQuantity = $item->quantity;
        //     }
        // }

        // $stockLeft = $markets->stock - $existingQuantity;
        $query = $request->input('search');
        $filters = $request->input('filters', []);

        // $chemicals = Chemical::where('chemical_name', 'LIKE', "%{$query}%")
        //         ->orWhere('CAS_number', 'LIKE', "%{$query}%")
        //         ->orWhere('serial_number', 'LIKE', "%{$query}%")
        //         ->orWhere('SKU', 'LIKE', "%{$query}%")
        //         ->paginate(3);

        $bids = Bid::where('market_id', $markets->id)
                    ->where(function ($q) use ($query) {
                        $q->where('price', 'LIKE', "%{$query}%")
                        ->orWhere('quantity', 'LIKE', "%{$query}%")
                        ->orWhere('delivery', 'LIKE', "%{$query}%")
                        ->orWhere('notes', 'LIKE', "%{$query}%")
                        ->orWhere('status', 'LIKE', "%{$query}%")
                        ->orWhereHas('user', function ($q) use ($query) {
                            $q->where('name', 'LIKE', "%{$query}%");
                        });
                    })
                    ->with('user')
                    ->paginate(3);

        if ($request->ajax()) {
            return view('markets._searchDetail', compact( 'bids'))->render();
        }

        return view('markets.detail', compact('markets', 'bids'));
    }

    public function edit(Market $markets){
        // dd($markets);
        $this->authorize('update', $markets);

        return view('markets.edit', compact('markets'));
    }

    public function update(Request $request, Market $markets){
        $this->authorize('update', $markets);

        $data = $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'chemical_id' => 'required|exists:chemicals,id',
            // 'price' => 'required|numeric|min:0',
            'quantity_needed' => 'required|integer|min:1',
            'unit' => ['required'],
            'notes' => ['nullable'],
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

    public function bid(Market $markets){
        $this->authorize('create', Bid::class);
        return view('markets.bid', compact('markets'));
    }

    public function storeBid(Request $request, Market $markets){
        $this->authorize('create', Bid::class);
        $data = $request->validate([
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'delivery' => 'nullable|string',
            // 'notes' => 'nullable|string',
        ]);

        // dd($data);

        $markets->bids()->create([
            'user_id' => auth()->id(),
            'price' => $data['price'],
            'quantity' => $data['quantity'],
            'delivery' => $data['delivery'],
            'notes' => $data['notes'] ?? '',
        ]);

        return redirect()->route('market.detail', $markets->id)->with('success', 'Bid submitted successfully.');
    }

    public function editBid(Bid $bids){
        // dd($markets);
        $this->authorize('update', $bids);

        return view('markets.editBid', compact('bids'));
    }

    public function updateBid(Request $request, Bid $bids){
        $this->authorize('update', $bids);

        $data = $request->validate([
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'delivery' => 'nullable|string',
            // 'notes' => 'nullable|string',
        ]);

        $bids->update($data);

        return redirect('/m/'.$bids->market->id)->with('success', 'Offer updated successfully');
    }

    public function deleteBid(Bid $bids){
        $this->authorize('delete', $bids);
        // dd($bids);
        $marketsId = $bids->market_id;
        $bids->delete();

        return redirect()->route('market.detail', $marketsId)->with('success', 'Offer deleted successfully');
    }

    public function accept(Bid $bids) {
        $this->authorize('accept', Bid::class);
        // $bid = bid::findOrFail($id);
        // dd($bid->id);
        $bids->update(['status' => 'accepted']);

        $bids->user->notify(new BidAccepted($bids));
        return back()->with('success', 'bids accepted successfully.');
    }





    private function getAccessToken() {
        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Authorization' => 'Basic ' . base64_encode(config('paypal.client_id') . ':' . config('paypal.client_secret')),
        ];

        $response = Http::withHeaders($headers)
                    ->withBody('grant_type=client_credentials')
                    ->post(config('paypal.base_url') . '/v1/oauth2/token');

        return json_decode($response->body())->access_token;
    }

    //cart
    public function addToCart(Request $request, Market $market){
        $this->authorize('buy', $market);

        $user = auth()->user();
        $cart = $user->cart ?? $user->cart()->create();

        // Check if item already in cart
        $item = $cart->items()->where('market_id', $market->id)->first();
        $existingQuantity = $item ? $item->quantity : 0;

        $stockLeft = $market->stock - $existingQuantity;

        if ($stockLeft <= 0) {
            return back()->with('error', 'This item is already fully in your cart. No more stock available.');
        }

        $maxAllowed = $market->stock - $existingQuantity;

        $request->validate([
            'quantity' => [
                'required',
                'integer',
                'min:1',
                'max:' . $maxAllowed,
            ],
        ]);

        // Proceed to add or update item
        if ($item) {
            $item->quantity += $request->quantity;
            $item->save();
        } else {
            $cart->items()->create([
                'market_id' => $market->id,
                'quantity' => $request->quantity,
            ]);
        }

        return redirect(route('cart.index'))->with('success', 'Item added to cart.');
    }


    public function viewCart(){
        $this->authorize('buy', Market::class);

        $cart = auth()->user()->cart;
        return view('markets.cart', compact('cart'));
    }

    public function updateCart(Request $request, $itemId){
        $this->authorize('buy', Market::class);

        $cart = auth()->user()->cart;
        $item = $cart->items()->where('id', $itemId)->firstOrFail();

        $stock = $item->market->stock; // market stock quantity

        if ($request->action === 'increase') {
            if ($item->quantity < $stock) {
                $item->quantity += 1;
            } else {
                return redirect()->back()->with('error', 'Cannot exceed available stock.');
            }
        } elseif ($request->action === 'decrease') {
            $item->quantity -= 1;

            if ($item->quantity <= 0) {
                $item->delete();
                return redirect()->back()->with('success', 'Item removed from cart.');
            }
        }

        $item->save();

        return redirect()->back()->with('success', 'Cart updated.');
    }

    public function checkout(){
        $this->authorize('buy', Market::class);

        $user = auth()->user();
        $cart = $user->cart;

        if (!$cart || $cart->items->isEmpty()) {
            return back()->with('error', 'Your cart is empty.');
        }

        DB::beginTransaction();
        try {
            foreach ($cart->items as $item) {
                $market = $item->market;
            }

            // Clear the cart
            $cart->items()->delete();

            DB::commit();
            return back()->with('success', 'Checkout completed.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Checkout failed: ' . $e->getMessage());
        }
    }


// payment
    public function createPaypal(Market $markets) {

        $id = uuid_create();
        $amount = $markets->price;

        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->getAccessToken(),
            'Paypal-Request-Id' => $id,
        ];

        $body = [
            "intent" => "CAPTURE",
            "purchase_units" => [
                [
                    "reference_id" =>$id,
                    "amount" => [
                        "currency_code" => $markets->currency,
                        "value" => number_format($markets->price, 2),
                    ],
                    // "payee" => [
                    //     "email_adress" => $markets->user->paypal_email,
                    // ]
                ]
            ]
        ];

        $response = Http::withHeaders($headers)
                    ->withBody(json_encode($body))
                    ->post(config('paypal.base_url') . '/v2/checkout/orders');

        Session::put('request_id', $id);
        Session::put('order_id', json_decode($response->body())->id);

        return json_decode($response->body())->id;
    }

    public function complete(Market $markets) {
        $orderId = Session::get('order_id');
        if (!$orderId) {
            return response()->json(['error' => 'No order ID found in session'], 400);
        }

        $url = config('paypal.base_url') . '/v2/checkout/orders/' . $orderId . '/capture';

        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->getAccessToken(),
        ];

        $response = Http::withHeaders($headers)
                        ->post($url, null);

        $payout = app(MarketsController::class)->sendPayout($markets);


        return response()->json(json_decode($response->body()));
    }

    public function sendPayout(Market $markets)
{
    $tokenResponse = Http::withBasicAuth(config('paypal.client_id'), config('paypal.client_secret'))
        ->asForm()
        ->post('https://api-m.sandbox.paypal.com/v1/oauth2/token', [
            'grant_type' => 'client_credentials'
        ]);

    if (!$tokenResponse->successful()) {
        return ['error' => 'Failed to obtain PayPal access token', 'details' => $tokenResponse->json()];
    }

    $accessToken = $tokenResponse->json()['access_token'];
        $afterCut = number_format($markets->price - ($markets->price * 0.10), 2, '.', '');

        $payoutResponse = Http::withToken($accessToken)
            ->post('https://api-m.sandbox.paypal.com/v1/payments/payouts', [
                'sender_batch_header' => [
                    'sender_batch_id' => uniqid(),
                    'email_subject' => 'You have a payment from ChimieTrace',
                ],
                'items' => [[
                    'recipient_type' => 'EMAIL',
                    'amount' => [
                        'value' => $afterCut,
                        'currency' => $markets->currency,
                    ],
                    'receiver' => $markets->user->paypal_email,
                    'note' => 'Payment for ' . $markets->chemical->chemical_name . ' (' . $markets->description . ') ' . 'from ' . auth()->user()->name,
                    'sender_item_id' => uniqid(),
                ]]
            ]);

        if (!$payoutResponse->successful()) {
            return [
                'error' => 'Payout failed',
                'details' => $payoutResponse->json()
            ];
        }

        return $payoutResponse->json();
    }

    // public function sendPayout(Market $markets)
    // {
    //     $response = Http::withBasicAuth(config('paypal.client_id'), config('paypal.client_secret'))
    //         ->asForm()
    //         ->post('https://api-m.sandbox.paypal.com/v1/oauth2/token', [
    //             'grant_type' => 'client_credentials'
    //         ]);

    //     $accessToken = $response->json()['access_token'];
    //     $afterCut = $markets->price - $markets->price*10/100;

    //     $payoutResponse = Http::withToken($accessToken)
    //         ->post('https://api-m.sandbox.paypal.com/v1/payments/payouts', [
    //             'sender_batch_header' => [
    //                 'sender_batch_id' => uniqid(),
    //                 'email_subject' => 'You have a payment from ChimieTrace',
    //             ],
    //             'items' => [[
    //                 'recipient_type' => 'EMAIL',
    //                 'amount' => [
    //                     'value' => $afterCut,
    //                     'currency' => $markets->currency,
    //                 ],
    //                 'receiver' => $markets->user->paypal_email,
    //                 'note' => 'Payment for ' . $markets->serial_number,
    //                 'sender_item_id' => uniqid(),
    //                 "recipient_wallet" => "PAYPAL",
    //             ]]
    //         ]);

    //     return $payoutResponse->json();
    // }










    // public function checkout(Market $markets) {
    //     Stripe::setApiKey(config('stripe.sk'));

    //     $supplier = $markets->user->stripe_account_id;

    //     $session = \Stripe\Checkout\Session::create([
    //         'line_items' => [
    //             [
    //             'price_data' => [
    //                 'currency' => $markets->currency,
    //                 'product_data' => [
    //                     'name' => 'send money here',
    //                 ],
    //                 'unit_amount' => $markets->price * 1000,
    //             ],
    //             'quantity' => 1,
    //             ],
    //         ],
    //         'payment_intent_data' => [
    //             'transfer_data' => [
    //                 'destination' => $supplier,
    //             ],
    //         ],
    //         'mode' => 'payment',
    //         'success_url' => route('success', $markets->id),
    //         'cancel_url' => route('market.detail', $markets->id),
    //     ]);

    //     return redirect()->away($session->url);
    // }

    public function success(Market $markets) {
        return redirect()->route('market.detail', $markets->id);
    }

    public function createStripeAccount (User $user) {
        Stripe::setApiKey(config('stripe.sk'));

        $account = Account::create([
            'type' => 'express',
            // 'country' => 'MY',
            // 'email' => $user->email,
            // 'capabilities' => [
            //     'transfers' => ['requested' => true],
            // ],
        ]);

        $user->stripe_account_id = $account->id;
        $user->save();

        return $account;
    }

    public function stripeOnboarding (User $user) {
        $account = $this->createStripeAccount($user);

        $accountLink = AccountLink::create([
            'account' => $account->id,
            'refresh_url' => route('stripe.refresh'),
            'return_url' => route('stripe.return'),
            'type' => 'account_onboarding',
        ]);

        return redirect()->away($accountLink->url);
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

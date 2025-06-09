<?php

namespace App\Http\Controllers;

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
use Illuminate\Support\Facades\Http;
use PaypalServerSdkLib\Models\Order;
use Illuminate\Support\Facades\Session;

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

    public function search(Request $request){
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

        return view('markets.search', compact('markets'))->render();
    }

    public function store(Request $request){
        $data = $request->validate([
            // 'inventory_id' => 'nullable|exists:inventories,id',
            'chemical_id' => 'required|exists:chemicals,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:1',
            'description' => ['nullable'],
            'currency' => ['required'],
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
            'currency' => ['required'],
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










    public function checkout(Market $markets) {
        Stripe::setApiKey(config('stripe.sk'));

        $supplier = $markets->user->stripe_account_id;

        $session = \Stripe\Checkout\Session::create([
            'line_items' => [
                [
                'price_data' => [
                    'currency' => $markets->currency,
                    'product_data' => [
                        'name' => 'send money here',
                    ],
                    'unit_amount' => $markets->price * 1000,
                ],
                'quantity' => 1,
                ],
            ],
            'payment_intent_data' => [
                'transfer_data' => [
                    'destination' => $supplier,
                ],
            ],
            'mode' => 'payment',
            'success_url' => route('success', $markets->id),
            'cancel_url' => route('buy', $markets->id),
        ]);

        return redirect()->away($session->url);
    }

    public function success(Market $markets) {
        return redirect()->route('buy', $markets->id);
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

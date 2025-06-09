<?php

namespace App\Http\Controllers\Auth;

use Stripe\Stripe;
use Stripe\Account;
use App\Models\User;
use Stripe\AccountLink;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected function redirectTo(){
        if (auth()->user()->role === 'faculty') {
            return '/inventory';
        } elseif (auth()->user()->role === 'supplier') {
            return '/market';
        }

        return '/';
    }
    // protected $redirectTo = '/inventory';


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $role = $data['role'] ?? null;

        if ($role === 'faculty') {
            Validator::extend('faculty_email', function($attribute, $value, $parameters, $validator){
                return preg_match('/@(student|lecturer|admin)\.com$/', $value);
            });

            $email = ['required', 'string', 'email', 'max:255', 'unique:users', 'faculty_email'];
        } else {
            $email = ['required', 'string', 'email', 'max:255', 'unique:users'];
        }

        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => $email,
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:faculty,supplier'],
            'paypal_email' => ['nullable'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
        // $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'paypal_email' => $data['paypal_email'] ?? '',
        ]);

        // if ($user->role === 'supplier') {
        //     Stripe::setApiKey(config('stripe.sk'));

        //     $account = Account::create([
        //         'type' => 'express',
        //         'country' => 'MY',
        //         'email' => $user->email,
        //         'capabilities' => [
        //             'transfers' => ['requested' => true],
        //             'card_payments' => ['requested' => true],
        //         ],
        //         'business_type' => 'individual',
        //     ]);

        //     dd($account);

        //     $user->stripe_account_id = $account->id;
        //     $user->save();

        //     $accountLink = AccountLink::create([
        //         'account' => $account->id,
        //         'refresh_url' => route('stripe.refresh'),
        //         'return_url' => route('stripe.return'),
        //         'type' => 'account_onboarding',
        //     ]);

        //     session(['supplier_oboarding_url' => $accountLink->url]);
        // }

        // return $user;
    }

    // protected function registered(Request $request, $user) {
    //     if($user->role === 'supplier' && session('supplier_onboarding_url')) {
    //         $onboardingUrl = session('supplier_onboarding_url');
    //         session()->forget('supplier_onboarding_url');

    //         return redirect()->away($onboardingUrl);
    //     }

    //     return redirect($this->redirectPath());
    // }
}

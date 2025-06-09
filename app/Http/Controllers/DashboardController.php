<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Chemical;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        // $user = auth()->user();

        // $chemicalsCount = Chemical::count();
        // // $ordersCount = \App\Models\Order::count();
        // $suppliersCount = User::where('role', 'supplier')->count();
        // // $pendingDeliveries = \App\Models\Delivery::where('status', 'pending')->count();
        // // $recentOffers = \App\Models\Market::with(['inventory', 'supplier'])
        // //                     ->latest()->take(5)->get();
        // $cart = $user->cart()->with('items.market.inventory')->first();

        // return view('dashboard.admin', compact(
        //     'chemicalsCount',
        //     // 'ordersCount',
        //     'suppliersCount',
        //     // 'pendingDeliveries',
        //     // 'recentOffers',
        //     'cart'
        // ));
    }


}

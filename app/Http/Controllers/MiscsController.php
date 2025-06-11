<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Alert;
use App\Models\Brand;
use App\Models\Market;
use App\Models\Chemical;
use App\Models\Inventory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\InventoryUsage;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Pagination\LengthAwarePaginator;
use willvincent\Feeds\Facades\FeedsFacade as Feeds;

class MiscsController extends Controller
{

    public function __construct() {
        $this->middleware(['auth', 'banned']);
    }

    public function createRequest() {
        $this->authorize('viewAny', Chemical::class);
        return view('miscs.userRequest');
    }

    public function storeRequest() {
        $this->authorize('viewAny', Chemical::class);
        $data = request()->validate([
            'type' => ['required', 'string'],
            'request' => ['required', 'string'],
            'item_id' => ['required', 'integer'],
            // 'chemical_id' => ['nullable', 'integer'],
            // 'inventory_id' => ['nullable', 'integer'],
            // 'market_id' => ['nullable', 'integer'],
            // 'respondent_id' => ['nullable', 'integer'],
        ]);

        $label = $this->getItemLabel($data['type'], $data['item_id']);

        $finalRequest = $label ? "Subject: {$label} By: " . auth()->user()->name . ' || ' . $data['request'] : "Type: " . $data['type'] . " || " . $data['request'];

        $userRequest = auth()->user()->userRequests()->create([
            'user_id' => auth()->id(),
            // 'chemical_id' => $data['chemical_id'] ?? null,
            // 'inventory_id' => $data['inventory_id'] ?? null,
            // 'market_id' => $data['market_id'] ?? null,
            // 'respondent_id' => $data['respondent_id'] ?? null,
            'type' => $data['type'],
            'item_id' => $data['item_id'],
            'receiver_type' => 'admin',
            'request' => $finalRequest,
        ]);

        // dd($userRequest);

        Alert::create([
            'user_id' => auth()->id(),
            'user_request_id' => $userRequest->id,
            'message' => $finalRequest,
            'receiver_type' => 'admin',
        ]);

        return redirect()->back()->with('success', 'request sent successfully');
    }

    public function requestOption($type){
        switch ($type) {
            case 'chemical':
                $items = Chemical::select('id', 'chemical_name', 'CAS_number')->get()
                                ->map(function ($item) {
                                    return [
                                        'id' => $item->id,
                                        'label' => $item->chemical_name . ' (' . $item->CAS_number . ')',
                                    ];
                                });
                break;
            case 'inventory':
                // $items = Inventory::select('id', 'serial_number', 'description')->groupBy('serial_number')->get()
                $items = Inventory::whereIn('id', function ($query) {
                                    $query->selectRaw('MIN(id)')
                                        ->from('inventories')
                                        ->groupBy('serial_number');
                                })->with('chemical')->get()
                                ->map(function ($item) {
                                    return [
                                        'id' => $item->id,
                                        'label' => $item->chemical->chemical_name . ' (' . $item->chemical->CAS_number . ') --- ' . $item->serial_number . ' (' . $item->description . ')',
                                    ];
                                });
                break;
            case 'market':
                // $items = Market::with('chemical')->select('id', 'serial_number', 'description')->groupBy('serial_number')->get()
                $items = Market::whereIn('id', function ($query) {
                                    $query->selectRaw('id')
                                        ->from('markets');
                                        // ->groupBy('serial_number');
                                })->with('chemical')->get()
                                ->map(function ($item) {
                                    return [
                                        'id' => $item->id,
                                        'label' => $item->chemical->chemical_name . ' (' . $item->chemical->CAS_number . ') --- (' . $item->inventory->description . ')',
                                    ];
                                });
                break;
            case 'user':
                $items = User::select('id', 'name')->get()
                                ->map(function ($item) {
                                    return [
                                        'id' => $item->id,
                                        'label' => $item->name,
                                    ];
                                });
                break;
            default:
                return response()->json([]);
        }

        return response()->json($items);
    }

    protected function getItemLabel($type, $id){
        if (!$id) return null;

        switch ($type) {
            case 'chemical':
                $item = Chemical::find($id);
                return $item ? "{$item->chemical_name} ({$item->CAS_number})" : null;

            case 'inventory':
                $item = Inventory::with('chemical')->find($id);
                return $item && $item->chemical
                    ? "{$item->chemical->chemical_name} ({$item->chemical->CAS_number}) --- {$item->serial_number} ({$item->description})"
                    : null;

            case 'market':
                $item = Market::with('chemical', 'inventory')->find($id);
                return $item && $item->chemical && $item->inventory
                    ? "{$item->chemical->chemical_name} ({$item->chemical->CAS_number}) --- ({$item->inventory->description})"
                    : null;

            case 'user':
                $item = User::find($id);
                return $item
                    ? $item->name
                    : null;

            default:
                return null;
        }
    }


    public function logs(Request $request, $type = null) {
        $this->authorize('viewAny', Alert::class);

        $query = $request->input('search');
        $filters = $request->input('filters', []);

        $query2 = Activity::query()->latest();

        // $query2 = Activity::where('description', 'LIKE', "%{$query}%")
        //         ->orWhere('created_at', 'LIKE', "%{$query}%")
        //         ->latest();
                // ->orWhere('serial_number', 'LIKE', "%{$query}%")
                // ->orWhere('SKU', 'LIKE', "%{$query}%")
                // ->paginate(10);

        if ($type) {
            if ($type == 'user') {
                $query2->whereIn('log_name', ['user', 'auth', 'profile']);
                    // ->orWhere('description', 'LIKE', "%{$query}%")
                    // ->orWhere('created_at', 'LIKE', "%{$query}%");
            } else {
                $query2->where('log_name', $type);
                    // ->orWhere('description', 'LIKE', "%{$query}%")
                    // ->orWhere('created_at', 'LIKE', "%{$query}%");
            }
        }

        if ($query) {
            $query2->where(function ($q) use ($query) {
                $q->where('event', 'LIKE', "%{$query}%")
                    ->orWhere('description', 'LIKE', "%{$query}%")
                    ->orWhere('properties->custom->causer_name', 'LIKE', "%{$query}%")
                    // ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(properties, '$.causer_name')) LIKE ?", ["%{$query}%"])
                    ->orWhere('created_at', 'LIKE', "%{$query}%");
            });
        }

        $activities = $query2->paginate(10);

        if ($request->ajax()) {
            return view('miscs._searchLog', compact('activities', 'type'))->render();
        }

        return view('miscs.log', compact('activities', 'type'));
    }

    public function showAlerts(Request $request, $type = null){
        $this->authorize('viewAny', Alert::class);

        $query = $request->input('search');
        $filters = $request->input('filters', []);

        $alertQuery = Alert::with('userRequest')
            ->where('is_read', false)
            ->latest();

        // Type filtering: chemical, market, user
        if ($type) {
            $alertQuery->whereHas('userRequest', function ($q) use ($type) {
                if ($type === 'chemical') {
                    $q->whereIn('type', ['chemical', 'inventory']);
                } else {
                    $q->where('type', $type);
                }
            });
        }

        // Search filtering
        if ($query) {
            $alertQuery->where('message', 'LIKE', "%{$query}%");
        }

        $alerts = $alertQuery->paginate(10);

        // AJAX partial return
        if ($request->ajax()) {
            return view('miscs._searchAlert', compact('alerts', 'type'))->render();
        }

        return view('miscs.alert', compact('alerts', 'type'));
    }

    // public function showAlerts(Request $request){
    //     $this->authorize('viewAny', Alert::class);
    //     // $this->authorize('viewAny', InventoryUsage::class);

    //     $query = $request->input('search');

    //     $filters = $request->input('filters', []);

    //     $alerts = Alert::where('message', 'LIKE', "%{$query}%")
    //             ->where('is_read', false)
    //             ->latest()
    //             // ->orWhere('serial_number', 'LIKE', "%{$query}%")
    //             // ->orWhere('SKU', 'LIKE', "%{$query}%")
    //             ->paginate(10);

    //     if ($request->ajax()) {
    //         return view('miscs._searchAlert', compact('alerts'))->render();
    //     }

    //     // $alerts = Alert::where('is_read', false)->latest()->paginate(3);
    //     return view('miscs.alert', compact('alerts'));
    // }

    // public function alertRedirect(Alert $request){
    //     switch ($request->type) {
    //         case 'chemical':
    //             return redirect()->route('chemical.show', $request->related_id);
    //         case 'inventory':
    //             return redirect()->route('inventory.detail', ['chemical' => $request->related_id]);
    //         case 'market':
    //             return redirect()->route('market.show', $request->related_id);
    //         case 'user':
    //             return redirect()->route('admin.user.show', $request->related_id); // Or your actual route
    //         default:
    //             return redirect()->back()->with('error', 'Unknown request type.');
    //     }
    // }

    public function increment(Alert $alert){

        if ($alert->current_containers === null) {
            $alert->current_containers = 1;
            $alert->save();
        }

        if ($alert->userRequest && $alert->inventory_id) {
            return redirect()->route('market.createRe', ['inventory_id' => $alert->inventory_id]);
        } elseif ($alert->userRequest->type === 'chemical' || $alert->userRequest->type === 'inventory') {
            $inventory = Inventory::where('id', $alert->userRequest->item_id)->first();
            $chemical = Chemical::where('id', $alert->userRequest->item_id)->first();
            // dd($inventory);
            return redirect()->route('inventory.detail', ['chemical' => $inventory ? $inventory->chemical->id : $chemical->id]);
        } elseif ($alert->userRequest->type === 'market' && $alert->userRequest->item_id === -2) {
            return redirect()->route('cart.orders');
        } elseif ($alert->userRequest->type === 'market') {
            // $market = Market::where('id', $alert->userRequest->item_id)->first();
            return redirect()->route('market.detail', ['markets' => $alert->userRequest->item_id]);
        } elseif ($alert->userRequest->type === 'user') {
            return redirect()->route('admin.viewUsers');
        }
    }


    public function markAsRead(Alert $alert){
        $this->authorize('viewAny', Alert::class);
        $alert->update(['is_read' => true]);
        return redirect()->back()->with('success', 'Alert marked as read');
    }

    public function inventoryLog(Request $request) {
        $this->authorize('viewAny', Alert::class);
        // $inventoryUsage = InventoryUsage::with('inventory.chemical.user')->latest()->paginate(10);
        $query = $request->input('search');

        $filters = $request->input('filters', []);

        $inventoryUsage = InventoryUsage::where('user_name', 'LIKE', "%{$query}%")
                ->orWhere('chemical_name', 'LIKE', "%{$query}%")
                ->orWhere('chemical_cas', 'LIKE', "%{$query}%")
                ->orWhere('inventory_serial', 'LIKE', "%{$query}%")
                ->orWhere('quantity_used', 'LIKE', "%{$query}%")
                ->orWhere('quantity_left', 'LIKE', "%{$query}%")
                ->orWhere('container_left', 'LIKE', "%{$query}%")
                ->orWhere('reason', 'LIKE', "%{$query}%")
                ->latest()
                ->paginate(3);

        if ($request->ajax()) {
            return view('miscs._searchInventoryLog', compact('inventoryUsage'))->render();
        }
        // dd($inventoryUsage);
        return view('miscs.inventoryLog', compact('inventoryUsage'));
    }

    public function indexBrands(){
        $brands = Brand::latest()->get();
        return view('miscs.indexBrands', compact('brands'));
    }

    public function storeBrands(Request $request){
        $request->validate([
            'name' => 'required|string|unique:brands,name|max:255',
        ]);

        Brand::create(['name' => $request->name]);

        return redirect()->route('brands.index')->with('success', 'Brand added.');
    }

    public function showChemistryNews(){
        $feed = Feeds::make([
            'https://www.chemistryworld.com/rss',
        ]);

        $items = $feed->get_items(0, 6); // Get latest 10 items

        return view('miscs.chemistryNews', compact('items'));
    }

    // public function showChemistryNews(Request $request){
    //     // 1. Load feed
    //     $feed = Feeds::make(['https://www.chemistryworld.com/rss']);
    //     $items = $feed->get_items(); // get all items

    //     // 2. Convert to Collection and normalize
    //     $collection = collect($items)->map(function ($item) {
    //         return [
    //             'title' => $item->get_title(),
    //             'description' => $item->get_description(),
    //             'link' => $item->get_link(),
    //             'date' => $item->get_date('Y-m-d H:i'),
    //         ];
    //     });

    //     // 3. Apply search filter
    //     if ($request->has('search')) {
    //         $search = Str::lower($request->input('search'));
    //         $collection = $collection->filter(function ($item) use ($search) {
    //             return Str::contains(Str::lower($item['title']), $search) ||
    //                 Str::contains(Str::lower($item['description']), $search);
    //         });
    //     }

    //     // 4. Paginate manually
    //     $perPage = 10;
    //     $currentPage = LengthAwarePaginator::resolveCurrentPage();
    //     $paginated = new LengthAwarePaginator(
    //         $collection->forPage($currentPage, $perPage)->values(),
    //         $collection->count(),
    //         $perPage,
    //         $currentPage,
    //         ['path' => request()->url(), 'query' => request()->query()]
    //     );

    //     return view('miscs.chemistryNews', ['items' => $paginated]);
    // }

}

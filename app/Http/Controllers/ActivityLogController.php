<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index($type = null) {
        $query = Activity::latest();

        if ($type) {
            if ($type == 'user') {
                $query->whereIn('log_name', ['user', 'auth', 'profile']);
            } else {
                $query->where('log_name', $type);
            }
        }

        $activities = $query->paginate(10);
        return view('logs.index', compact('activities', 'type'));
    }
}

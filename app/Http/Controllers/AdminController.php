<?php

namespace App\Http\Controllers;

use Mail;
use App\Models\User;
use App\Models\Alert;
use App\Mail\UserBannedMail;
use Illuminate\Http\Request;
use App\Notifications\UserBanned;

class AdminController extends Controller
{
    public function __construct() {
        $this->middleware(['auth', 'banned']);
    }
    public function viewUsers(Request $request) {
        $this->authorize('viewAny', Alert::class);
        // $users = User::paginate(10);
        $query = $request->input('search');

        $filters = $request->input('filters', []);

        $users = User::with('profile')
                        ->where(function ($q) use ($query) {
                            $q->where('name', 'LIKE', "%{$query}%")
                            ->orWhere('email', 'LIKE', "%{$query}%")
                            ->orWhere('role', 'LIKE', "%{$query}%");
                        })
                        ->orWhereHas('profile', function ($q) use ($query) {
                            $q->where('status', 'LIKE', "%{$query}%");
                        })
                        ->paginate(10);

        if ($request->ajax()) {
            return view('admins._searchViewUsers', compact('users'))->render();
        }
        return view('admins.viewUsers', compact('users'));
    }

    public function toggleBan($userId){
        $this->authorize('viewAny', Alert::class);

        $user = User::findOrFail($userId);
        $profile = $user->profile;

        if (!$profile) {
            return redirect()->back()->with('error', 'Profile not found.');
        }

        if ($user->role === config('roles.admin')) {
            return redirect()->back()->with('error', 'Cannot ban an admin user.');
        }

        $profile->status = $profile->status === 'banned' ? 'active' : 'banned';
        $profile->save();

        // Mail::to($user->email)->send(new UserBannedMail($user));
        if ($profile->status === 'banned') {
            $user->notify(new UserBanned($user->name, $user->email));
        }

        return redirect()->back()->with('success', 'User status updated.');
    }
}

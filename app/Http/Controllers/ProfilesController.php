<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ProfilesController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function __construct() {
        $this->middleware(['auth', 'banned']);
    }

    public function details(User $user){
        // dd($user->id);
        // $user = User::findOrFail($user);
        $activities = Activity::where('causer_id', $user->id)
                            ->latest()->take(5)->get();

        return view('profiles.details', compact('user', 'activities'));
    }

    public function edit(User $user) {
        $this->authorize('update', arguments: $user->profile);

        return view('profiles.edit', compact('user'));
    }

    public function update(User $user) {
        $this->authorize('update', arguments: $user->profile);

        $data = request()->validate([
            'user_name' => 'required',
            'email' => 'required',
            'status' => 'required',
            'score' => 'nullable',
            'profile_pic' => 'nullable',
            'phone_number' => 'nullable',
            'address' => 'nullable',
            'city' => 'nullable',
            'postal' => 'nullable',
        ]);

        // dd(request()->all());
        // dd($data);

        // $user->profile->update($data);
        // dd(request('profile_pic')->store('profile', 'public'));

        if(request('profile_pic')) {
            $imagePath = request('profile_pic')->store('profile', 'public');
        }

        // $image = Image::make(public_path("storage/{$imagePath}"))->fit(1200, 800);
        // $image->save();

        // dd(auth()->user()->update($data));

        auth()->user()->update([
            'name' => $data['user_name'],
            'email' => $data['email'],
        ]);

        auth()->user()->profile->update([
            'status' => $data['status'],
            'score' => $data['score'] ?? '',
            'profile_pic' => $imagePath ?? '',
            'phone_number' => $data['phone_number'] ?? '',
            'address' => $data['address'] ?? '',
            'city' => $data['city'] ?? '',
            'postal' => $data['postal'] ?? '',
        ]);

        return redirect("/profile/{$user->id}");
    }

}

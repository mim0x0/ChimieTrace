<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfilesController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function details(User $user)
    {
        // $user = User::findOrFail($user);

        return view('profiles.details', compact('user'));
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
            // 'score' => 'required',
            'profile_pic' => '',
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
            // 'score' => $data['score'],
            'profile_pic' => $imagePath,
        ]);

        return redirect("/profile/{$user->id}");
    }

}

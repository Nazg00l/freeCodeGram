<?php

namespace App\Http\Controllers;

use \App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;

class ProfilesController extends Controller
{
    // public function index($user)
    // {
    //     // $user = \App\Models\User::find($user);
    //     // $user = \App\Models\User::findOrFail($user);
    //     $user = User::findOrFail($user);
    //     return view('profiles.index', [
    //         'user' => $user,
    //     ]);
    //     // return view('home');
    // }

    // Another way of doing it, simpler, cleaner, and more compact
    public function index(User $user)
    {
        $follows = (auth()->user()) ? auth()->user()->following->contains($user->id) : false;

        // $postCount = $user->posts->count();
        $postCount = Cache::remember(
            'count.posts.' .$user->id, 
            now()->addSeconds(30), 
            function () use ($user) { 
                return $user->posts->count();
            });

        // $followersCount = $user->profile->followers->count();
        $followersCount = Cache::remember(
            'count.followers' .$user->id, 
            now()->addSeconds(30), 
            function () use ($user) {
                return $user->profile->followers->count();
            });

        // $followingCount = $user->following->count();
        $followingCount = Cache::remember(
            'count.following' .$user->id, 
            now()->addSeconds(30), 
            function () use ($user) {
                return $user->following->count();
            });

        return view('profiles.index', compact('user', 
        'follows', 'postCount', 'followersCount', 'followingCount'));
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user->profile);

        return view('profiles.edit', compact('user'));
    }

    public function update (User $user) {
        
        $this->authorize('update', $user->profile);

        $data = request()->validate([
            'title' => 'required',
            'description' => 'required',
            'url' => 'url',
            'image' => '',
        ]);

        //dd($data);

        if (request('image')) {
            $imagePath = request('image')->store('profile', 'public');

            $image = Image::make(public_path("storage/{$imagePath}"))->fit(1000, 1000);

            // $image->save();
            // Used this because the gif pic won't work unless I do this 
            // but I don't know why (for now) it is saving to pics in the 
            // profile folder. 
            $image->save(public_path("storage/{$imagePath}").'.gif', 100, 'gif');
            $imageArray = ['image' => $imagePath];
        }

        
        auth()->user()->profile->update(array_merge(
            $data, 
            $imageArray ?? []
        ));

        return redirect("/profile/{$user->id}");
    }
}

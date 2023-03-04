<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class PostController extends Controller
{
    // Mezzo: By adding this we are protecting this route 
    // protected by a login. 
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {

        // Get all of the users we are following
        $users = auth()->user()->following()->pluck('profiles.user_id');

        // $posts = Post::whereIn('user_id', $users)->get();
        // $posts = Post::whereIn('user_id', $users)->orderBy('created_at', 'DESC')->get();
        // $posts = Post::whereIn('user_id', $users)->latest()->get();
        // $posts = Post::whereIn('user_id', $users)->latest()->paginate(5);
        $posts = Post::whereIn('user_id', $users)->with('user')->latest()->paginate(5);
        
        // dd($posts);
        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }
    
    public function store() {
        $data = request()->validate([
            'caption' => 'required',
            'image' => ['required', 'image'],
            ]);

            //dd(request('image')->store('uploads', 'public'));
            $imagePath = request('image')->store('uploads', 'public');

            $image = Image::make(public_path("storage/{$imagePath}"))->fit(1200, 1200);
            // $image = Image::make(public_path("storage/{$imagePath}"))->resize(1200, 1200);

            $image->save();

            //auth()->user()->posts()->create($data);
            auth()->user()->posts()->create([
                'caption' => $data['caption'],
                'image' => $imagePath,
            ]);

            // \App\Models\Post::create($data);

        //dd('requset()->all() printed this cause I could not locate request function :)');
        return redirect('/profile/' . auth()->user()->id);
    }

    public function show(\App\Models\Post $post) {
        return view('posts.show', compact('post'));
    }
}

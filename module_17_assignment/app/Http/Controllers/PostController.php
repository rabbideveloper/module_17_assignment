<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // assignment
        // $posts = DB::table('posts')
        // ->select('excerpt', 'description')
        // ->get();


        $posts = DB::table('users')->count();
        $posts = DB::table('posts')->sum('min_to_read');
        $posts = DB::table('posts')->avg('min_to_read');
        $posts = DB::table('posts')->max('min_to_read');
        $posts = DB::table('posts')->min('min_to_read');

        $user = DB::table('users')
            ->whereNot('name', '=', 'korim ali')
            ->get();

        $posts = DB::table('posts')
            ->whereBetween('min_to_read', [1,5])
            ->get();


        $affected = DB::table('posts')
            ->where('id', '=', 3)
            ->increment('min_to_read');

        // dd($affected);

        $posts = DB::table('posts')
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->select('posts.id', 'title', 'name', 'excerpt', 'posts.created_at')
            ->get();


        return view('pages.posts', [
            'posts' => $posts
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.create-post');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        if($request->validated()) {
            $post = DB::table('posts')
                ->insert([
                    'user_id'=>$request->input('user_id'),
                    'title'=>$request->input('title'),
                    'slug'=>$request->input('slug'),
                    'excerpt'=>$request->input('excerpt'),
                    'description'=>$request->input('description'),
                    'is_published'=>$request->input('is_published'),
                    'min_to_read'=>$request->input('min_to_read'),
                ]);

            if(!$post) {
                return Response()->json([
                    'success'=>false,
                    'message'=>'Data not created'
                ]);
            } else {
                return Response()->json([
                    'success'=>true,
                    'message'=>$post
                ], 201);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // assignment
        $posts = DB::table('posts')
            ->where('id', '2')
            ->select('description')
            ->first();


        // assignment
        $posts = DB::table('posts')
            ->where('id', '=', '2')
            ->pluck('description');

        // assignment
        $posts = DB::table('posts')
            ->find('2');

        // assignment
        $posts = DB::table('posts')
            ->select('title')
            ->get();
        // dd($posts);

        // assignment  // return unic value
        $posts = DB::table('posts')
            ->select('title')
            ->distinct()
            ->get();

        // assignemnt
        $posts = DB::table('posts')
            ->select('title')
            ->get();

        // dd($posts);



        $post = DB::table('posts')
            ->where('posts.id', '=', $id)
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->select('posts.id', 'title', 'name', 'excerpt', 'description', 'posts.created_at')
            ->get();

        return view('pages.single-post', [
            'post' => $post
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, string $id)
    {
        if($request->validated()) {
            $post = DB::table('posts')
                ->where('id', $id)
                ->update($request->input());

            if(!$post) {
                return Response()->json([
                    'success'=>false,
                    'message'=>'Data not created'
                ]);
            } else {
                return Response()->json([
                    'success'=>true,
                    'message'=>$post
                ], 201);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = DB::table('posts')
            ->where('id', $id)
            ->delete();

        if(!$post) {
            return Response()->json([
                'success'=>false,
                'message'=>'Data not created'
            ]);
        } else {
            return Response()->json([
                'success'=>true,
                'message'=>$post
            ]);
        }
    }
}

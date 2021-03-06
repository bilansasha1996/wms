<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function home(){
        return view('vueApp');
    }

    public function product()
    {
        $headers = [

            [ 'align' => 'left', 'sortable' => false, 'text' => 'Имя', 'value' => 'name'],
            [ 'text' => 'Цена', 'value' => 'price']
        ];

        $items = [
            ['value' => false, 'name' => 'test product 1', 'price' => '100'],
            ['value' => false, 'name' => 'test product 2', 'price' => '200']
        ];
        $arr = ['headers' => $headers, 'items' => $items];
        return json_encode($arr);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        dd(1);

        return Post::orderBy('id','DESC')->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

//    /**
//     * Сохранение нового поста.
//     *
//     * @param  \Illuminate\Http\Request  $request
//     * @return \Illuminate\Http\Response
//     */
//    public function save(Request $request)
//    {
//        dd(1);
//
//        $this->validate($request, [
//            'title' => 'required',
//            'body' => 'required',
//        ]);
//
//        $create = Post::create($request->all());
//        return response()->json(['status' => 'success','msg'=>'post created successfully']);
//
//    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
        ]);

        $create = Post::create($request->all());
        return response()->json(['status' => 'success','msg'=>'post created successfully']);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Post::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return Post::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        dd($request);
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
        ]);

        $post = Post::find($id);
        if($post->count()){
            $post->update($request->all());
            return response()->json(['statur'=>'success','msg'=>'Post updated successfully']);
        } else {
            return response()->json(['statur'=>'error','msg'=>'error in updating post']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        if($post->count()){
            $post->delete();
            return response()->json(['statur'=>'success','msg'=>'Post deleted successfully']);
        } else {
            return response()->json(['statur'=>'error','msg'=>'error in deleting post']);
        }
    }
}

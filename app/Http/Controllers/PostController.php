<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Http\Resources\PostResource;
use App\Services\ResponseService;
use Validator;

class PostController extends Controller
{

    public function __construct(ResponseService $responseService){
        $this->responseService = $responseService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts          = Post::latest()->get();
        return $this->responseService->successResponse(PostResource::collection($posts), 'Post Succesfuly Received');;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input          = $request->all();
        $validator      = Validator::make($input,[
            'title'     => 'required',
            'content'   => 'required',
        ]);

        if ($validator->fails()) {
            return $this->responseService->errorResponse('Validator Error',$validator->errors());;
        }else {
            $post           = Post::create($input);
            return $this->responseService->successResponse(new PostResource($post), 'Post Succesfuly Created');;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post       = Post::find($id);

        if (is_null($post)) {
            return $this->responseService->errorResponse('Post Not Found');;
        }

        return $this->responseService->successResponse(new PostResource($post), 'Post Succesfuly Retreived');;
        
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Post $post)
    {
        

        $input          = $request->all();
        $validator      = Validator::make($input,[
            'title'     => 'required',
            'content'   => 'required',
        ]);

        if ($validator->fails()) {
            return $this->responseService->errorResponse('Validator Error',$validator->errors());;
        }else {
            $post->update([
                'title'         => $input['title'],
                'content'       => $input['content'],
            ]);
            return $this->responseService->successResponse(new PostResource($post), 'Post Succesfuly Updated');;
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return $this->responseService->successResponse(new PostResource($post), 'Post Succesfuly Deleted');;
    }
}

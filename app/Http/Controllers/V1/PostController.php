<?php

namespace App\Http\Controllers\V1;

use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Dingo\Api\Routing\Helpers;

class PostController extends Controller
{
    use Helpers;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $posts = $this->post->paginate(20);
        return $posts;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $input['user_id'] = $this->user->id;

        $validationRules = [
            'content' => 'required|min:1',
            'title' => 'required|min:1',
            'status' => 'required|in:draft,published',
            'user_id' => 'required|exists:users,id'
        ];
        $validator = Validator::make($input, $validationRules);
        if ($validator->fails()) {
            return new \Illuminate\Http\JsonResponse(
                [
                    'errors' => $validator->errors()
                ], \Illuminate\Http\Response::HTTP_BAD_REQUEST
            );
        }
        $post = $this->post->create($input);
        return [
            'data' => $post
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->post->find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();
        $post = $this->post->find($id);
        if (!$post) {
            abort(404);
        }

        if ($this->user->id != $post->user_id) {
            return new JsonResponse(
                [
                    'errors' => 'Only Post Owner can update it'
                ], Response::HTTP_FORBIDDEN
            );
        }
        $post->fill($input);
        $post->save();
        return $post;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = $this->post->find($id);
        if (!$post) {
            abort(404);
        }
        if ($this->user->id != $post->user_id) {
            return new JsonResponse(
                [
                    'errors' => 'Only Post Owner can delete it'
                ], Response::HTTP_FORBIDDEN
            );
        }
        $post->delete();
        return ['message' => 'deleted successfully', 'post_id' => $id];
    }
}

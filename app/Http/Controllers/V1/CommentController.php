<?php

namespace App\Http\Controllers\V1;

use App\Comment;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Dingo\Api\Routing\Helpers;

class CommentController extends Controller
{
    use Helpers;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        //$records = Post::find($id)->comments()->paginate(20);
        //$records = Post::find($id)->with('comments')->paginate(20);
        $records = $this->comment->where('post_id', $id)->paginate(20);
        return $records;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $input = $request->all();
        $input['user_id'] = $this->user->id;

        $post = Post::find($id);
        if (!$post) {
            abort(404);
        }
        $input['post_id'] = $post->id;

        $validationRules = [
            'comment' => 'required|min:1',
            'post_id' => 'required|exists:posts,id',
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

        $this->post->create($input);

        return [
            'data' => $input
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
        $comment = $this->comment->find($id);

        if (!$comment) {
            abort(404);
        }

        return $comment;
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
        $input['user_id'] = $this->user->id;

        $post = $this->post->find($id);

        if (!$post) {
            abort(404);
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

        $post->delete();

        return ['message' => 'deleted successfully', 'post_id' => $id];

    }
}

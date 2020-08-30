<?php

namespace App\Repositories;

use App\Comment;
use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Post;
use App\Libraries\JsonAPILibrary;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

class PostRepository implements PostRepositoryInterface
{

    private $apiClient;

    public function __construct(JsonAPILibrary $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    public function all(Request $request)
    {
        // try on cached (local db)
        $postM = Post::all()->toArray();
        $total = count($postM);
        $current_page = $request->input("page") ?? 1;
        $starting_point = ($current_page * $this::PER_PAGE) - $this::PER_PAGE;
        $arrayM = array_slice($postM, $starting_point, $this::PER_PAGE, true);

        if (count($arrayM) < 5) {
            // call external api
            $response = $this->apiClient->getData('posts');
            $arrayM = array_slice($response['body'], $starting_point, $this::PER_PAGE, true);
        }

        $postR = PostResource::collection($arrayM)->resolve();
        return new Paginator($postR, $total, $this::PER_PAGE, $current_page, [
            'path' => $request->url(),
            'query' => $request->query(),
        ]);
    }

    public function find($post)
    {
        // try on cached (local db)
        $postM = Post::where('id', $post)->first();
        if (!$postM) {
            // call external api
            $response = $this->apiClient->getData(
                'posts',
                $post,
                null
            );
            $postM = $response['body'];
        }

        if ($postM) return PostResource::make($postM)->resolve();
        return response()->json(['error' => 'post not found'], 404);
    }

    public function findComments(Request $request, $post)
    {
        // try on cached (local db)
        $commentM = Comment::where('post_id', $post)->get()->toArray();
        $total = count($commentM);
        $current_page = $request->input("page") ?? 1;
        $starting_point = ($current_page * $this::PER_PAGE) - $this::PER_PAGE;
        $arrayM = array_slice($commentM, $starting_point, $this::PER_PAGE, true);

        if (count($arrayM) < 5) {
            // call external api
            $response = $this->apiClient->getData(
                'comments',
                null,
                ['postId' => $post]
            );
            $arrayM = array_slice($response['body'], $starting_point, $this::PER_PAGE, true);
        }

        $commentsR = CommentResource::collection($arrayM)->resolve();
        return new Paginator($commentsR, $total, $this::PER_PAGE, $current_page, [
            'path' => $request->url(),
            'query' => $request->query(),
        ]);
    }
}

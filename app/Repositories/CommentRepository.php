<?php

namespace App\Repositories;

use App\Comment;
use App\Http\Resources\CommentResource;
use App\Libraries\JsonAPILibrary;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

class CommentRepository implements CommentRepositoryInterface
{

    private $apiClient;

    public function __construct(JsonAPILibrary $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    public function all(Request $request)
    {
        // try on cached (local db)
        $commentM = Comment::all()->toArray();
        $total = count($commentM);
        $current_page = $request->input("page") ?? 1;
        $starting_point = ($current_page * $this::PER_PAGE) - $this::PER_PAGE;
        $arrayM = array_slice($commentM, $starting_point, $this::PER_PAGE, true);

        if (count($arrayM) < 5) {
            // call external api
            $response = $this->apiClient->getData('comments');
            $arrayM = array_slice($response['body'], $starting_point, $this::PER_PAGE, true);
        }

        $postR = CommentResource::collection($arrayM)->resolve();
        return new Paginator($postR, $total, $this::PER_PAGE, $current_page, [
            'path' => $request->url(),
            'query' => $request->query(),
        ]);
    }

    public function find($comment)
    {
        // try on cached (local db)
        $commentM = Comment::where('id', $comment)->first();
        if (!$commentM) {
            // call external api
            $response = $this->apiClient->getData(
                'posts',
                $comment,
                null
            );
            $commentM = $response['body'];
        }

        if ($commentM) return CommentResource::make($commentM)->resolve();
        return response()->json(['error' => 'comment not found'], 404);
    }
}

<?php

namespace App\Repositories;

use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use App\Libraries\JsonAPILibrary;
use App\Post;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

class UserRepository implements UserRepositoryInterface
{
    private $apiClient;

    public function __construct(JsonAPILibrary $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    public function all(Request $request)
    {
        // try on cached (local db)
        $userM = User::all()->toArray();
        $total = count($userM);
        $current_page = $request->input("page") ?? 1;
        $starting_point = ($current_page * $this::PER_PAGE) - $this::PER_PAGE;
        $arrayM = array_slice($userM, $starting_point, $this::PER_PAGE, true);

        if (count($arrayM) < 5) {
            // call external api
            $response = $this->apiClient->getData('users');
            $arrayM = array_slice($response['body'], $starting_point, $this::PER_PAGE, true);
        }

        $usersR = UserResource::collection($arrayM)->resolve();
        return new Paginator($usersR, $total, $this::PER_PAGE, $current_page, [
            'path' => $request->url(),
            'query' => $request->query(),
        ]);
    }

    public function find($user)
    {
        // try on cached (local db)
        $userM = User::where('id', $user)->first();
        if (!$userM) {
            // call external api
            $response = $this->apiClient->getData(
                'users',
                $user,
                null
            );
            $userM = $response['body'];
        }

        if ($userM) return UserResource::make($userM)->resolve();
        return response()->json(['error' => 'user not found'], 404);
    }

    public function findPosts(Request $request, $user)
    {
        // try on cached (local db)
        $postM = Post::where('user_id', $user)->get()->toArray();
        $total = count($postM);
        $current_page = $request->input("page") ?? 1;
        $starting_point = ($current_page * $this::PER_PAGE) - $this::PER_PAGE;
        $arrayM = array_slice($postM, $starting_point, $this::PER_PAGE, true);

        if (count($arrayM) < 5) {
            // call external api
            $response = $this->apiClient->getData(
                'posts',
                null,
                ['userId' => $user]
            );
            $arrayM = array_slice($response['body'], $starting_point, $this::PER_PAGE, true);
        }

        $postsR = PostResource::collection($arrayM)->resolve();
        return new Paginator($postsR, $total, $this::PER_PAGE, $current_page, [
            'path' => $request->url(),
            'query' => $request->query(),
        ]);
    }

}

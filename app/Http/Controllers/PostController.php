<?php

namespace App\Http\Controllers;

use App\Repositories\PostRepositoryInterface;
use Illuminate\Http\Request;

class PostController extends Controller
{
    private $repository;

    public function __construct(PostRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
    public function index(Request $request)
    {
        return $this->repository->all($request);
    }

    public function show(Request $request, $post)
    {
        return $this->repository->find($post);
    }

    public function comments(Request $request, $post)
    {
        return $this->repository->findComments($request, $post);
    }
}

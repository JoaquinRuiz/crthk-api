<?php

namespace App\Http\Controllers;

use App\Repositories\CommentRepositoryInterface;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    private $repository;

    public function __construct(CommentRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
    public function index(Request $request)
    {
        return $this->repository->all($request);
    }

    public function show(Request $request, $comment)
    {
        return $this->repository->find($comment);
    }
}

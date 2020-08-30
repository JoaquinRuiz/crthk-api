<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\UserRepositoryInterface;

class UserController extends Controller
{
    private $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
    public function index(Request $request)
    {
        return $this->repository->all($request);
    }

    public function show(Request $request, $user)
    {
        return $this->repository->find($user);
    }

    public function posts(Request $request, $user)
    {
        return $this->repository->findPosts($request, $user);
    }
}

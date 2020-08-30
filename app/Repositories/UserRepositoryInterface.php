<?php

namespace App\Repositories;

use Illuminate\Http\Request;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function all(Request $request);

    public function find($id);

    public function findPosts(Request $request, $id);
}

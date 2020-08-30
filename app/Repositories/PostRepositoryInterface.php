<?php

namespace App\Repositories;

use Illuminate\Http\Request;

interface PostRepositoryInterface extends RepositoryInterface
{
    public function all(Request $request);

    public function find($id);

    public function findComments(Request $request, $id);
}

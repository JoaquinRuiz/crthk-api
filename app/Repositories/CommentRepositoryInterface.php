<?php

namespace App\Repositories;

use Illuminate\Http\Request;

interface CommentRepositoryInterface extends RepositoryInterface
{
    public function all(Request $request);

    public function find($id);

}

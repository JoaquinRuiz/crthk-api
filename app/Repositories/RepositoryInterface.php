<?php

namespace App\Repositories;

use Illuminate\Http\Request;

interface RepositoryInterface
{
    CONST PER_PAGE = 5;

    public function all(Request $request);

    public function find($id);
}

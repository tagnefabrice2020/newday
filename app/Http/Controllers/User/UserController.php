<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\PaginatorTrait;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use PaginatorTrait;

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);

        $users = User::all();

        $paginator = $this->generatePaginator(
            $users,
            $users->count(),
            $perPage,
            $page,
            $request->url(),
            $request->query()
        );

        return response()->json($paginator, 200);
    }
}

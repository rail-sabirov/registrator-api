<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
     /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function index()
    {
        return response()->json(User::all());
    }
}

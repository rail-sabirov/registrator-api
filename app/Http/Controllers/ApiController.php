<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function index()
    {
        return response()->json([
            'message' => 'Registrator API, 2024',
            'description' => 'Registrator API, Five Star`s Local Web Server.',
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ApiResponse;
class ApiController extends Controller
{

    use ApiResponse;

    public function __construct()
    {
        $this->middleware('auth:api');
    }
}

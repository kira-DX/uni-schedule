<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Member;

class MemberController extends Controller
{
    public function index()
    {
        return response()->json(Member::all());
    }
}
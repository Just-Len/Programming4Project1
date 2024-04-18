<?php

namespace App\Http\Controllers;

use App\Models\Lessor;
use Illuminate\Http\Request;

class LessorController extends Controller
{
    public function index()
    {
        return Lessor::all();
    }
}

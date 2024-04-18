<?php

namespace App\Http\Controllers;

use App\Models\Lodging;
use Illuminate\Http\Request;

class LodgingController extends Controller
{
    public function index()
    {
        return Lodging::all();
    }
}

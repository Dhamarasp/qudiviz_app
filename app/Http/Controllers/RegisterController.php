<?php

namespace App\Http\Controllers;

use App\Models\Language;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function index()
    {
        $languages = Language::all();
        return view('auth.register', compact('languages'));
    }
}

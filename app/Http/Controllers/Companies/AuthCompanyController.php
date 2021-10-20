<?php

namespace App\Http\Controllers\Companies;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthCompanyController extends Controller
{
    public function index()
    {
        return view('frontend.companies.login');
    }
}

<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $employee = $user->employee;
        $company = $employee->company->first();

        return view('frontend.users.employee.profile.index', [
            'user' => $user,
            'employee' => $employee,
            'company' => $company
        ])
            ->with(['bodyClass' => $company->primary_color . ' ' . $company->secondary_color . '    Profile']);
    }

    public function edit()
    {
        $user = auth()->user();
        $employee = $user->employee;
        $company = $employee->company->first();

        return view('frontend.users.employee.profile.edit', [
            'user' => $user,
            'company' => $company,
            'employee' => $employee
        ])
            ->with(['bodyClass' => $company->primary_color . ' ' . $company->secondary_color . ' ProfileEdit CompanyEdit Admin ProfileEdit']);
    }
}

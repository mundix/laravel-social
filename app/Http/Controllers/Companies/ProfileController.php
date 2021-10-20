<?php

namespace App\Http\Controllers\Companies;

use App\Http\Controllers\Controller;
use App\Models\company;
use App\Services\CompanyService;
use Illuminate\Http\Request;
use UxWeb\SweetAlert\SweetAlert;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $user = auth()->user();
        $company = $user->company;

        return view('frontend.companies.index', [
            'user' => $user,
            'company' => $company,
        ])
            ->with(['bodyClass' => $company->primary_color . ' ' . $company->secondary_color . ' Company']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\company $company
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit()
    {
        $user = auth()->user();
        $company = $user->company;

        return view('frontend.companies.edit', [
            'company' => $company
        ])
            ->with(['bodyClass' => $company->primary_color . ' ' . $company->secondary_color. ' ProfileEdit CompanyEdit Admin']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\company $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, company $company)
    {
        return redirect()->route('companies.edit', $company);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CauseService;
use App\Services\InvolvementService;

class CompanyController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $company = $user->company;

        $totalCauses = CauseService::getCompanyCauses($company)->count();
        $totalEvents = $company->user->events->count();
        $totalRaises = (new InvolvementService)->getCompanyRises($company);
        $totalUsers = $company->employees->count();

        return view('frontend.companies.admin.index', [
            'user' => $user,
            'company' => $company,
            'totalEvents' => $totalEvents,
            'totalCauses' => $totalCauses,
            'totalRaises' => $totalRaises,
            'totalUsers' => $totalUsers,
        ])
            ->with([
                'bodyClass' => '--overview',
                'adminTitle' => 'Overview'
            ]);
    }

    public function causes()
    {
        $user = auth()->user();
        $company = $user->company;

        return view('frontend.companies.admin.causes.index', [
            'user' => $user,
            'company' => $company,
        ])
            ->with([
                'bodyClass' => '--causes',
                'adminTitle' => 'Causes'
            ]);
    }

    public function involvements()
    {
        $user = auth()->user();
        $company = $user->company;

        return view('frontend.companies.admin.involvements.index',[
                    'user' => $user,
                    'company' => $company,
                ])
            ->with([
                'bodyClass' => '--community-involvement',
                'adminTitle' => 'Community Involvements'
            ]);
    }

    public function stories()
    {
        $user = auth()->user();
        $company = $user->company;

        return view('frontend.companies.admin.stories.index',
            compact('user', 'company'))
            ->with([
                'bodyClass' => '--success-stories',
                'adminTitle' => 'Success Stories'
            ]);
    }

    public function news()
    {
        $user = auth()->user();
        $company = $user->company;

        return view('frontend.companies.admin.news.index',
            compact('user', 'company'))
            ->with([
                'bodyClass' => '--news',
                'adminTitle' => 'News'
            ]);
    }

    public function events()
    {
        $user = auth()->user();
        $company = $user->company;

        return view('frontend.companies.admin.events.index',
            compact('user', 'company'))
            ->with([
                'bodyClass' => '--events',
                'adminTitle' => 'Events'
            ]);
    }

    public function employees()
    {
        $user = auth()->user();
        $company = $user->company;

        return view('frontend.companies.admin.employees.index',
            compact('user', 'company'))
            ->with([
                'bodyClass' => '--employees',
                'adminTitle' => 'Employees'
            ]);
    }

    public function users()
    {
        $user = auth()->user();
        $company = $user->company;
        return view('frontend.companies.admin.users.index',
            compact('user', 'company'))
            ->with([
                'bodyClass' => '--users',
                'adminTitle' => 'Admin'
            ]);
    }

    public function profile()
    {
        $user = auth()->user();
        $company = $user->company;

        return view('frontend.companies.admin.profile.index',
            compact('user', 'company'))
            ->with([
                'bodyClass' => 'ProfileEdit CompanyEdit',
                'adminTitle' => 'Profile Details'
            ]);
    }

}

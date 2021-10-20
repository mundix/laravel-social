<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cause;
use App\Models\Company;
use App\Models\Invite;
use App\Models\User;
use App\Services\CauseService;
use App\Services\CompanyService;
use App\Services\EmployeeService;
use App\Services\InviteService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $totalCauses = Cause::count();

        $totalCompanies = Company::count();

        $totalNominations = CauseService::getNominatePendingTotal();

        $totalUsers = User::all()->count();

        return view('backend.dashboard.index', [
            'user' => $user,
            'totalCompanies' => $totalCompanies,
            'totalCauses' => $totalCauses,
            'totalNominations' => $totalNominations,
            'totalUsers' => $totalUsers
        ])->with([
            'bodyClass' => '--overview',
            'adminTitle' => 'Overview'
        ]);
    }

    public function profile()
    {
        return view('backend.profile.index')
            ->with([
                'bodyClass' => ' --profile',
                'adminTitle' => 'Profile'
            ]);
    }

    public function companies()
    {
        $user = auth()->user();

        return view('backend.companies.index', [
            'user' => $user
        ])
            ->with([
                'bodyClass' => '--companies',
                'adminTitle' => 'Companies'
            ]);
    }

    public function causes()
    {
        $user = auth()->user();

        return view('backend.causes.index', [
            'user' => $user
        ])->with([
            'bodyClass' => '--causes',
            'adminTitle' => 'Causes'
        ]);
    }

    /**
     * Retrieve Admin Users
     */
    public function users()
    {
        return view('backend.users.index')
            ->with([
                'bodyClass' => ' --users"',
                'adminTitle' => 'Admins'
            ]);
    }

    /**
     * Create User Company By Token
     * @param string $token
     * @return \Illuminate\Http\RedirectResponse
     */
    public function invite(string $token): \Illuminate\Http\RedirectResponse
    {
        $user = InviteService::process($token);
        if ($user) {

            \Auth::login($user);
            return redirect()->route('company.onboarding.signup');

        } else {

            \Log::info('Invite Was used with this token' . $token);
            $user = InviteService::getUserByToken($token);

            if ($user) {
                \Auth::login($user);
                return redirect()->route('company.profile');
            }
            \Log::info('Invite Was used with this token' . $token);
        }

        return redirect()->route('home')->withErrors('Cannot created this company.');
    }

    public function categories()
    {
        return view('backend.causes.categories.index')
            ->with([
                'bodyClass' => ' --causes"',
                'adminTitle' => 'Admins'
            ]);
    }

}

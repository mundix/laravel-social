<?php

namespace App\Http\Controllers;

use App\Models\Cause;
use App\Models\Company;
use App\Services\CauseService;
use Illuminate\Http\Request;

class CauseController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index($slug)
	{
        $company = Company::whereSlug($slug)
            ->whereHas('user', function ($query) {
                $query->where('status','active');
            })
            ->first();
        if (!$company) {
            return abort(404);
        }

		return view('frontend.causes.index', compact('company'))
            ->with(['bodyClass' => 'Company ProfileFavorites']);
	}
}

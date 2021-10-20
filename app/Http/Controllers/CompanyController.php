<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\CategoryPost;
use App\Models\Cause;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Post;
use App\Models\User;
use App\Services\CompanyService;
use Illuminate\Http\Request;

class CompanyController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Company $company
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Company $company)
    {
        if ($company->user->status !== 'active') {
            return abort(404);
        }

        return redirect()->route('company.slug', $company->slug);
    }

    /**
     * Display Company Profile By Slug
     * @param string $slug
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    */
    public function company(string $slug)
    {
        $company = Company::whereSlug(trim(strtolower($slug)))->whereHas('user', function ($query) {
            $query->where('status', 'active');
        })->first();

        $user = auth()->check() ? auth()->user() : null;

        if (!$company) {
            return abort(404);
        }

        return view('frontend.companies.index', [
            'company' => $company,
            'user' => $user,
        ])->with(['bodyClass' => $company->primary_color . ' ' . $company->secondary_color . ' Company']);
    }

    /**
     * Display Company's Employees By Company Slug
     * @param string $slug
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function employees(string $companySlug)
    {
        $company = Company::whereSlug(trim(strtolower($companySlug)))
            ->whereHas('user', function ($query) {
                $query->where('status', 'active');
            })
            ->first();

        if (!$company) {
            return abort(404);
        }

        $user = auth()->check() ? auth()->user() : null;

        return view('frontend.companies.employees.index', [
            'user' => $user,
            'company' => $company,
        ])
            ->with([
                'bodyClass' => $company->primary_color . ' ' . $company->secondary_color . ' Company',
                'extraClass' => 'Company ProfileFavorites'
            ]);
    }

    /**
     * Show Company Employee Public access
     * @param string $companySlug
     * @param string $slug
     *
     * @return mixed
     */
    public function employee(string $companySlug, string $slug)
    {
        $company = Company::whereSlug(trim(strtolower($companySlug)))
            ->whereHas('user', function ($query) {
                $query->where('status', 'active');
            })
            ->first();

        $employee = Employee::whereSlug($slug)->first();

        if (!$company) {
            return abort(404);
        }

        $user = auth()->check() ? auth()->user() : null;

        return view('frontend.users.employee.profile.index', [
            'user' => $user,
            'employee' => $employee,
            'company' => $company
        ])->with(['bodyClass' => 'Profile ' . $company->primary_color . ' ' . $company->secondary_color . ' Company']);
    }

    /**
     * Show Company News By Company Slug
     * @param string $slug
     *
     * @return mixed
     */
    public function news($slug = null)
    {
        $company = Company::whereSlug($slug)->first();

        if (!$company) {
            return abort(404);
        }

        $posts = $company->user->posts;

        $user = auth()->check() ? auth()->user() : null;

        return view('frontend.companies.posts.index', [
            'user' => $user,
            'company' => $company,
            'posts' => $posts,
        ])
            ->with([
                'bodyClass' => $company->primary_color . ' ' . $company->secondary_color . ' Company',
                'extraClass' => 'ProfileFavorites'
            ]);
    }

    /**
     * show company news details selected by company slug and news slug
     * @param string $companySlug
     * @param string $slug
     * @return mixed
     */
    public function news_detail($companySlug, $slug)
    {
        $company = Company::whereSlug(trim(strtolower($companySlug)))
            ->whereHas('user', function ($query) use ($slug) {
                $query->where('status', 'active');

                $query->whereHas('posts', function ($query) use ($slug) {
                    $query->whereSlug($slug);

                    $query->where('status', 'publish');
                });
            })
            ->first();

        if (!$company) {
            return abort(404);
        }

        $post = Post::whereSlug($slug)->first();

        $relatedArticles = $company->user->posts()->where('id', '<>', $post->id)->get();
        $related = collect($relatedArticles)->shuffle()->take(3);

        $user = auth()->check() ? auth()->user() : null;

        return view('frontend.companies.posts.show', [
            'user' => $user,
            'post' => $post,
            'related' => $related,
            'company' => $company
        ])
            ->with([
                'bodyClass' => $company->primary_color . ' ' . $company->secondary_color . ' Company',
                'extraClass' => 'ProfileFavorites'
            ]);
    }

    public function create_news()
    {
        $user = auth()->check() ? auth()->user() : null;
        $company = $user->company;

        return view('frontend.companies.posts.create', [
            'user' => $user,
            'company' => $company,
        ])
            ->with([
                'bodyClass' => $company->primary_color . ' ' . $company->secondary_color . ' Company',
                'extraClass' => 'Company CompanyNewArticle ProfileFavorites'
            ]);
    }

    public function edit_news($id)
    {
        $post = Post::findOrFail($id);

        $user = auth()->check() ? auth()->user() : null;

        $company = $user->company;

        return view('frontend.companies.posts.edit', [
            'user' => $user,
            'company' => $company,
            'post' => $post,
            'categories' => CategoryPost::all()
        ])
            ->with([
                'bodyClass' => $company->primary_color . ' ' . $company->secondary_color . ' Company',
                'extraClass' => 'Company CompanyNewArticle ProfileFavorites'
            ]);
    }

    /**
     * Show News previews by Post ID
     * @param int $id
     *
     * @return mixed
     */
    public function news_preview($id)
    {
        $post = Post::findOrFail($id);

        $user = auth()->check() ? auth()->user() : null;
        $company = $user->company;

        $related = $company->user->posts()->where('id', '<>', $post->id)->get();

        return view('frontend.companies.posts.show', [
            'user' => $user,
            'post' => $post,
            'related' => $related,
            'company' => $company
        ])
            ->with([
                'bodyClass' => $company->primary_color . ' ' . $company->secondary_color . ' Company',
                'extraClass' => 'Company ProfileFavorites'
            ]);
    }

    /**
     * Show all Company Causes By Company Slug
     * @param string $slug
     *
     * @return mixed
     */
    public function causes($slug = null)
    {
        $company = Company::whereSlug($slug)->first();
        if (!$company) {
            return abort(404);
        }

        $user = auth()->check() ? auth()->user() : null;

        return view('frontend.causes.index', [
            'user' => $user,
            'company' => $company
        ])
            ->with(['bodyClass' => $company->primary_color . ' ' . $company->secondary_color . ' Company']);;
    }

    public function signup()
    {
        if (\Auth::check()) {
            $user = auth()->user();

            return view('frontend.companies.onboarding.signup', [
                'user' => $user,
            ])
                ->with(['bodyClass' => 'User UserSignup CompanySignup']);;
        } else {
            return abort(404);
        }
    }

    public function onboarding()
    {
        if (\Auth::check()) {
            $user = auth()->user();

            return view('frontend.companies.onboarding.basic-details', [
                'user' => $user,
            ])
                ->with(['bodyClass' => 'User UserOnboarding CompanyOnboarding']);;
        } else {
            return abort(404);
        }
    }

}

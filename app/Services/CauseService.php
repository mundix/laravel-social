<?php


namespace App\Services;


use App\Models\CategoryCause;
use App\Models\Cause;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Nominate;
use App\Models\User;
use Illuminate\Support\Collection;

class CauseService
{

    public static function getCauses($limit = 6, $searchQuery = '', $category = 'all', $pageName = '')
    {
        return Cause::when(!empty(trim($searchQuery)), function ($query) use ($searchQuery) {
            $query->where('name', 'like', '%' . $searchQuery . '%');
        })
            ->when(!empty(trim($category)) && $category !== 'all', function ($query) use ($category) {
                $query->where('category_id', $category);
            })
            ->where('status', 'approved')
            ->orderBy('id', 'DESC')
            ->paginate($limit, ['*'], $pageName);
    }

    public static function getCategories()
    {
        return CategoryCause::orderBy('name', 'asc')->get();
    }

    public static function getCategoriesHasCauses($searchQuery = null)
    {
        return CategoryCause::has('causes')->orderBy('name', 'asc')
            ->when($searchQuery, function ($query) use ($searchQuery) {
                $query->where('name', 'like', '%' . $searchQuery . '%');
            })
            ->get();
    }

    public function getCauseCategories($searchQuery = null)
    {
        return CategoryCause::when($searchQuery, function ($query) use ($searchQuery) {
                $query->where('name', 'like', '%' . $searchQuery . '%');
            })
            ->orderBy('name', 'asc')
            ->get();
    }

    public static function getCategoriesPluck($fields = 'id')
    {
        return CategoryCause::orderBy('name', 'asc')->pluck($fields);
    }

    /**
     * Retrieve company cause by search
     *
     * @param array|null $companiesIds
     * @param mixed|int $limit
     * @param int|array|mixed $category
     * @param string $locationType
     * @param mixed $searchQuery
     * @param mixed $status
     * @param bool|mixed $matchable
     * @param bool|mixed $favorite
     * @param bool $sortOrder
     *
     * @return mixed
     */
    public static function search(
        $companiesIds = null,
        $limit = null,
        $category = null,
        $locationType = [],
        $searchQuery = null,
        $status = 'approved',
        $disabled = null,
        $matchable = null,
        $favorite = null,
        $sortColumn = null,
        bool $sortOrder = true,
        $pageName = 'causesPage'
    ) {
        $query = Cause::select('causes.*')
            ->with([
                'media',
                'user',
                'referral',
                'category',
                'favorites'
            ])
            ->whereNull('deleted_at')
            ->withCount('favorites')
            ->when($companiesIds, function($query) use ($companiesIds){
                $query->whereHas('user', function ($query) use ($companiesIds) {
                    if (is_array($companiesIds)) {
                        $query->whereIn('id', $companiesIds);
                    }
                });
            })
            ->when($favorite && $favorite != 'all', function ($query) use ($favorite) {
                if ($favorite == 'favorite') {
                    $query->Has('favorites');
                } elseif ($favorite == 'unfavorite') {
                    $query->doesnthave('favorites');
                }
            })
            ->join('category_causes', 'category_causes.id', '=', 'causes.category_id')
            ->when(!empty(trim($status)) && $status !== 'all', function ($query) use ($status) {
                $query->where(function ($query) use ($status) {
                    if ($status === 'nominate') {
                        $query->where('causes.status', 'approved');
                        $query->where('causes.is_nominated', true);
                    } elseif ($status === 'no-nominate') {
                        $query->where('causes.status', 'approved');
                        $query->where('causes.is_nominated', false);
                    } else {
                        $query->where('causes.status', $status);
                    }
                });
            })
            ->when(!empty($matchable) && $matchable !== 'all', function ($query) use ($matchable) {
                if ($matchable == 1) {
                    $query->where('causes.matchable', $matchable);
                } else {
                    $query->where('causes.matchable', 0);
                }
            })
            ->when($category && is_array($category) && count($category), function ($query) use ($category) {
                if(is_array($category)) {
                    $query->whereIn('causes.category_id', $category);
                }else {
                    $query->where('causes.category_id', $category);
                }
            })
            ->when($locationType && is_array($locationType) && count($locationType),
                function ($query) use ($locationType) {
                    $query->whereIn('causes.location_type', $locationType);
                })
            ->when($searchQuery, function ($query) use ($searchQuery) {
                $query->where(function ($query) use ($searchQuery) {
                    $query->where('causes.name', 'like', '%' . $searchQuery . '%');
                    $query->orWhere('causes.description', 'like', '%' . $searchQuery . '%');
                });
            })
        ;

        if ($disabled === 'pending') {
            $query->where('causes.status', 'pending');
        } else {
            $query->where('causes.status', 'approved');
        }

        if ($sortColumn) {
            if ($sortColumn === 'favorites') {
                $query->orderBy('favorites_count', $sortOrder ? 'asc' : 'desc');
            } else {
                $query->orderBy($sortColumn, $sortOrder ? 'asc' : 'desc');
            }
        }

        return $query->paginate($limit, ['*'], $pageName);
    }

    /**
     * Retrieve company cause by search
     *
     * @param Company $company
     * @param mixed|int $limit
     * @param int|array|mixed $category
     * @param string $locationType
     * @param mixed $searchQuery
     * @param mixed $status
     * @param bool|mixed $matchable
     * @param bool|mixed $favorite
     * @param bool $sortOrder
     *
     * @return mixed
     */
    public function searchByCompany(
        Company $company,
        $limit = null,
        $category = null,
        $locationType = [],
        $searchQuery = null,
        $status = 'approved',
        $disabled = null,
        $matchable = null,
        $favorite = null,
        $sortColumn = null,
        bool $sortOrder = true,
        $pageName = 'causesPage'
    ) {
        $query = Cause::select('causes.*')
            ->with([
                'media',
                'user',
                'referral',
                'category',
                'favorites'
            ])
            ->whereNull('deleted_at')
            ->withCount('favorites')
            ->whereHas('user', function ($query) use ($company) {
                $query->where('id', $company->user->id)
                    ->orWhere('type', 'admin')
                    ->orWhere('type', 'super');
            })
            ->when($favorite && $favorite != 'all', function ($query) use ($favorite) {
                if ($favorite == 'favorite') {
                    $query->Has('favorites');
                } elseif ($favorite == 'unfavorite') {
                    $query->doesnthave('favorites');
                }
            })
            ->join('category_causes', 'category_causes.id', '=', 'causes.category_id')
            ->when(!empty(trim($status)) && $status !== 'all', function ($query) use ($status) {
                $query->where(function ($query) use ($status) {
                    if ($status === 'nominate') {
                        $query->where('causes.status', 'approved');
                        $query->where('causes.is_nominated', true);
                    } elseif ($status === 'no-nominate') {
                        $query->where('causes.status', 'approved');
                        $query->where('causes.is_nominated', false);
                    } else {
                        $query->where('causes.status', $status);
                    }
                });
            })
            ->when(!empty($matchable) && $matchable !== 'all', function ($query) use ($matchable) {
                if ($matchable == 1) {
                    $query->where('causes.matchable', $matchable);
                } else {
                    $query->where('causes.matchable', 0);
                }
            })
            ->when($category && is_array($category) && count($category), function ($query) use ($category) {
                $query->whereIn('causes.category_id', $category);
            })
            ->when($locationType && is_array($locationType) && count($locationType),
                function ($query) use ($locationType) {
                    $query->whereIn('causes.location_type', $locationType);
                })
            ->when($category && !is_array($category) && $category !== 'all', function ($query) use ($category) {
                $query->where('causes.category_id', $category);
            })
            ->when($searchQuery, function ($query) use ($searchQuery) {
                $query->where(function ($query) use ($searchQuery) {
                    $query->where('causes.name', 'like', '%' . $searchQuery . '%');
                    $query->orWhere('causes.description', 'like', '%' . $searchQuery . '%');
                });
            });

        if ($disabled === 'pending') {
            $query->where('causes.status', 'pending');
        } else {
            $query->where('causes.status', 'approved');
        }

        if ($sortColumn) {
            if ($sortColumn === 'favorites') {
                $query->orderBy('favorites_count', $sortOrder ? 'asc' : 'desc');
            } else {
                $query->orderBy($sortColumn, $sortOrder ? 'asc' : 'desc');
            }
        }

        return $query->paginate($limit, ['*'], $pageName);
    }

    public static function getNominatePendingTotal(Company $company = null)
    {
        if (!is_null($company)) {
            return $company->user->causes()->where('is_nominated', true)->where('status', 'nominate')->count();
        } else {
            return Cause::where('is_nominated', true)->where('status', 'nominate')->count();
        }
    }

    /**
     * Gets Nominate Causes By company
     * @param Company $company
     * @param integer $limit
     * @param null|string $sortColumn
     * @param null|bool $sortOrder
     */
    public static function getNominatesByCompany(
        Company $company = null,
        $limit = 5,
        $sortColumn = null,
        bool $sortOrder = true,
        $pageName = 'nominatePages'
    ) {
        if (!is_null($company)) {
            $query = $company->user->causes()->where('is_nominated', true)
                ->where('status', 'nominate');
        } else {
            $query = Cause::where('is_nominated', true)
                ->where('status', 'nominate');
        }

        if ($sortColumn) {
            $query->orderBy($sortColumn, $sortOrder ? 'asc' : 'desc');
        }

        return $query->paginate($limit, ['*'], $pageName);
    }

    public static function getCompanyCauses(
        Company $company,
        $limit = 6,
        $searchQuery = '',
        $category = 'all',
        $pageName = ''
    ) {
            $query = Cause::where('user_id', $company->user->id)
                ->where('status', 'approved')
                ->when(!empty(trim($searchQuery)), function ($query) use ($searchQuery) {
                    $query->where('name', 'like', '%' . $searchQuery . '%');
                })
                ->when(!empty(trim($category)) && $category !== 'all', function ($query) use ($category) {
                    $query->where('category_id', $category);
                })
                ->orderBy('id', 'DESC');

        if (!is_null($limit)) {
            return  $query->paginate($limit, ['*'], $pageName);
        } else {
            return $query->get();
        }
    }

    public static function getFavoriteCauses(
        Company $company,
        $limit = 6,
        $searchQuery = '',
        $category = 'all',
        $pageName = ''
    ) {
        if (!is_null($limit)) {
            return $company->user->getFavoriteItems(Cause::class)->when(!empty(trim($searchQuery)),
                function ($query) use ($searchQuery) {
                    $query->where('name', 'like', '%' . $searchQuery . '%');
                })
                ->when(!empty(trim($category)) && $category !== 'all', function ($query) use ($category) {
                    $query->where('category_id', $category);
                })
                ->orderBy('id', 'DESC')
                ->paginate($limit, ['*'], $pageName);
        } else {
            return $company->user->getFavoriteItems(Cause::class)->when(!empty(trim($searchQuery)),
                function ($query) use ($searchQuery) {
                    $query->where('name', 'like', '%' . $searchQuery . '%');
                })
                ->when(!empty(trim($category)) && $category !== 'all', function ($query) use ($category) {
                    $query->where('category_id', $category);
                })
                ->orderBy('id', 'DESC')
                ->get();
        }
    }


    public static function getFavoriteCausesByEmployee(
        Employee $employee,
        $limit = 6,
        $searchQuery = '',
        $category = 'all',
        $pageName = ''
    ) {
        if (!is_null($limit)) {
            return $employee->user->getFavoriteItems(Cause::class)->when(!empty(trim($searchQuery)),
                function ($query) use ($searchQuery) {
                    $query->where('name', 'like', '%' . $searchQuery . '%');
                })
                ->when(!empty(trim($category)) && $category !== 'all', function ($query) use ($category) {
                    $query->where('category_id', $category);
                })
                ->orderBy('id', 'DESC')
                ->paginate($limit, ['*'], $pageName);
        } else {
            return $employee->user->getFavoriteItems(Cause::class)->when(!empty(trim($searchQuery)),
                function ($query) use ($searchQuery) {
                    $query->where('name', 'like', '%' . $searchQuery . '%');
                })
                ->when(!empty(trim($category)) && $category !== 'all', function ($query) use ($category) {
                    $query->where('category_id', $category);
                })
                ->orderBy('id', 'DESC')
                ->get();
        }
    }

    public function getFavoriteCausesByUser(
        User $user,
        $limit = 6,
        $searchQuery = null,
        $category = null,
        $sortColumn = null,
        bool $sortOrder = true,
        $pageName = 'causesPage'
    ) {
        $query = $user->getFavoriteItems(Cause::class)
            ->select('causes.*')
            ->whereNull('deleted_at')
            ->withCount('favorites')
            ->join('category_causes', 'category_causes.id', '=', 'causes.category_id')
            ->when(!empty(trim($searchQuery)),
                function ($query) use ($searchQuery) {
                    $query->where('causes.name', 'like', '%' . $searchQuery . '%');
                })
            ->when(!empty(trim($category)) && $category !== 'all', function ($query) use ($category) {
                $query->where('causes.category_id', $category);
            });

        if ($sortColumn) {
            if ($sortColumn === 'favorites') {
                $query->orderBy('favorites_count', $sortOrder ? 'asc' : 'desc');
            } else {
                $query->orderBy($sortColumn, $sortOrder ? 'asc' : 'desc');
            }
        }

        return $query->paginate($limit, ['*'], $pageName);
    }

    /**
     * Search Company by searchQuery
     * @param string|null $searchQuery
     *
     * @return  Collection
    */
    public function getCompaniesHasCauses($searchQuery = null)
    {
        return  Company::whereHas('user', function($query){
            $query->has('causes');
        })->when($searchQuery, function ($query) use ($searchQuery) {
            $query->where(function($query) use ($searchQuery) {
                $query->where('name', 'like', '%' . $searchQuery . '%');
            });
        })
            ->get();

    }
}

<?php


namespace App\Services;

use App\Interfaces\Causes;
use App\Interfaces\Users;
use App\Models\Cause;
use App\Models\Company;
use App\Models\User;
use Carbon\Carbon;

class EmployeeService implements Users, Causes
{

    /**
     * Retrieve Logged User
     */
    public static function getUser()
    {
        return auth()->user();
    }

    public static function getCauses()
    {
        $user = self::getUser();
        return $user->causes;
    }

    public static function getPhotos()
    {
        return self::getUser()->employee->getMedia('photos')->isNotEmpty() ? self::getUser()->employee->getMedia('photos') : [];
    }

    public static function getProfilePicture()
    {
        return self::getUser()->employee->profile->url;
    }

    public static function getProfileBackground()
    {
        return self::getUser()->employee->background->url;
    }

    /**
     * @return User array
     */
    public static function getAll($only_confirm = true)
    {
        return User::whereType(Users::TYPE_EMPLOYEE)->where(function ($query) use ($only_confirm) {
            if ($only_confirm) {
                return $query->where('status_id', Users::STATUS_DEFAULT)->where('confirmed', 'approved');
            }
        })->get();
    }

    public static function getPaginate($only_confirmed = true, $filter = null, $limit = 20)
    {
        return User::whereType(Users::TYPE_EMPLOYEE)
            ->with('employee')
            ->where(function ($query) use ($only_confirmed) {
                if ($only_confirmed) {
                    return $query->where('status_id', Users::STATUS_DEFAULT)->where('confirmed', 'approved');
                }
            })->when(!is_null($filter), function ($query) use ($filter) {
                if (isset($filter['period'])) {
                    if ($filter['period'] === 'day') {
                        $query->whereDate('created_at', '>=', Carbon::now()->subDays($filter['value']));
                    }
                    if ($filter['period'] === 'month') {
                        $query->whereDate('created_at', '>=', Carbon::now()->subMonths($filter['value']));
                    }
                    if ($filter['period'] === 'year') {
                        $query->whereDate('created_at', '>=', Carbon::now()->subYear($filter['value']));
                    }
                }
            })
            ->paginate($limit);
    }

    public static function getAllPluck($field = 'id')
    {
        return User::whereType(Users::TYPE_EMPLOYEE)->pluck($field);
    }

    public static function getFavoriteCauses()
    {
        return self::getUser()->FavoriteCauses->get();
    }

    public static function getFavoritesCausesPaginated($limit = 20)
    {
        return self::getUser()->FavoriteCauses->paginate($limit);
    }

    public static function getCompany()
    {
        return self::getUser()->employee->invite->company;
    }


    public static function getEmployeesByCompany(Company $company, $limit = 20, $searchString = '', $status = null)
    {
        return $company->employees()
            ->when(!empty(trim($searchString)), function ($query) use ($searchString) {
                $query->where('first_name', 'like', '%' . $searchString . '%');
                $query->orWhere('last_name', 'like', '%' . $searchString . '%');
                $query->orWhere('job_title', 'like', '%' . $searchString . '%');
                $query->orWhere('description', 'like', '%' . $searchString . '%');
                $query->orWhere('location', 'like', '%' . $searchString . '%');
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->paginate($limit);
    }

    /**
     * @param Company $company
     * @param null|string $searchQuery
     * @param null|string $status
     * @param int @limit
     * @param string $pageName
     */
    public function search(
        Company $company,
        $searchQuery = null,
        $status = null,
        $disabled = null,
        $limit = 5,
        $sortColumn = null,
        bool $sortOrder = true,
        $pageName = 'pageEmployees'
    ) {
        $query = $company->employees()
            ->select('employees.*')
            ->with(['media', 'user'])
            ->when(!empty(trim($searchQuery)), function ($query) use ($searchQuery) {
                $query->where(function ($query) use ($searchQuery) {
                    $query->where('first_name', 'like', '%' . $searchQuery . '%');
                    $query->orWhere('last_name', 'like', '%' . $searchQuery . '%');
                    $query->orWhere('job_title', 'like', '%' . $searchQuery . '%');
                    $query->orWhere('description', 'like', '%' . $searchQuery . '%');
                    $query->orWhere('location', 'like', '%' . $searchQuery . '%');
                });
            })
            ->join('users', 'users.id', '=', 'employees.user_id')
            ->whereHas('user', function ($query) use ($status, $searchQuery) {
                $query->whereType('employee');
            })
            ->when($status, function ($query) use ($status) {
                $query->where('users.status', $status);
            });

        if ($disabled === 'disabled') {
            $query->where('users.status', 'disabled');
        } else {
            $query->where('users.status', 'active');
        }

        if ($sortColumn) {
            $query->orderBy($sortColumn, $sortOrder ? 'asc' : 'desc');
        }

        return $query->paginate($limit, ['*'], $pageName);
    }

}
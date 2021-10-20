<?php


namespace App\Services;


use App\Models\Admin;
use App\Models\Company;
use App\Models\User;

class UserService
{
    /**
     * @param null|string $searchQuery
     * @param null|string $status
     * @param int @limit
     * @param string $pageName
     */
    public function search(
        $searchQuery = null,
        $status = null,
        $limit = 5,
        $orderBy = 'first_name',
        $sortBy = false,
        $pageName = 'pageEmployees'
    ) {
        return User::with(['admin'])
            ->select('users.*')
            ->when(!empty(trim($searchQuery)), function ($query) use ($searchQuery) {
                $query->whereHas('admin', function ($query) use ($searchQuery) {
                    $query->with(['media']);
                    $query->where('admins.first_name', 'like', '%' . $searchQuery . '%');
                    $query->orWhere('admins.last_name', 'like', '%' . $searchQuery . '%');
                });
            })
            ->whereType('admin')
            ->join('admins', 'admins.user_id', '=', 'users.id')
            ->when($status, function ($query) use ($status) {
                $query->where('users.status', $status);
            })
            ->orderBy($orderBy, $sortBy ? 'ASC' : 'DESC')
            ->paginate($limit, ['*'], $pageName);
    }

    /**
     * @param null|string $searchQuery
     * @param null|string $status
     * @param null|string $disabled
     * @param int @limit
     * @param string $pageName
     */
    public function searchAdmins(
        $searchQuery = null,
        $status = null,
        $disabled = null,
        $limit = 5,
        $sortColumn = null,
        bool $sortOrder = true,
        $pageName = 'pageAdmins'
    ) {
        $query = Admin::with(['media', 'user'])->select('admins.*')
            ->when(!empty(trim($searchQuery)), function ($query) use ($searchQuery) {
                $query->where(function ($query) use ($searchQuery) {
                    $query->where('admins.first_name', 'like', '%' . $searchQuery . '%');
                    $query->orWhere('admins.last_name', 'like', '%' . $searchQuery . '%');
                });
            })
            ->join('users', 'users.id', '=', 'admins.user_id')
            ->whereHas('user', function ($query) use ($status, $searchQuery) {
                $query->whereType('admin');
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

    public function searchCompanies(
        $searchQuery = null,
        $status = null,
        $disabled = null,
        $limit = 5,
        $sortColumn = null,
        bool $sortOrder = true,
        $pageName = 'pageCompany'
    )
    {
        $query = Company::with(['media', 'user'])->select('companies.*')
            ->when(!empty(trim($searchQuery)), function ($query) use ($searchQuery) {
                $query->where(function ($query) use ($searchQuery) {
                    $query->where('companies.name', 'like', '%' . $searchQuery . '%');
                });
            })
            ->join('users', 'users.id', '=', 'companies.user_id')
            ->whereHas('user', function ($query) use ($status, $searchQuery) {
                $query->whereType('company');
            });

        if ($disabled === 'disabled') {
            $query->where('users.status', 'disabled');
        } else {
            $query->whereIn('users.status', ['active', 'pending']);
        }

        if ($sortColumn) {
            $query->orderBy($sortColumn, $sortOrder ? 'asc' : 'desc');
        }

        return $query->paginate($limit, ['*'], $pageName);
    }

    public function searchCompaniesAdmin(
        $searchQuery = null,
        $status = null,
        $disabled = null,
        $limit = 5,
        $sortColumn = null,
        bool $sortOrder = true,
        $pageName = 'pageCompany'
    )
    {
        $query = Admin::with(['media', 'user'])->select('admins.*')
            ->when(!empty(trim($searchQuery)), function ($query) use ($searchQuery) {
                $query->where(function ($query) use ($searchQuery) {
                    $query->where('admins.first_name', 'like', '%' . $searchQuery . '%');
                    $query->orWhere('admins.last_name', 'like', '%' . $searchQuery . '%');
                });
            })
            ->join('users', 'users.id', '=', 'admins.user_id')
            ->whereHas('user', function ($query) use ($status, $searchQuery) {
                $query->whereType('company-admin');
            });

        if ($disabled === 'disabled') {
            $query->where('users.status', 'disabled');
        } else {
            $query->whereIn('users.status', ['active', 'pending']);
        }

        if ($sortColumn) {
            $query->orderBy($sortColumn, $sortOrder ? 'asc' : 'desc');
        }

        return $query->paginate($limit, ['*'], $pageName);
    }
}
<?php


namespace App\Services;


use App\Interfaces\Causes;
use App\Interfaces\Companies;
use App\Interfaces\Users;
use App\Interfaces\MediaLibrary;
use App\Models\Cause;
use App\Models\Company;
use App\Models\Employee;
use App\Models\User;
use Carbon\Carbon;

class CompanyService implements Users, Causes, MediaLibrary, Companies
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

    public static function getFavoriteCauses($queryString, $category_id)
    {
        return $causes = self::getUser()->getFavoriteItems(Cause::class)
            ->when(!empty(trim($queryString)), function ($query) use ($queryString) {
                $query->where('name', 'like', '%' . $queryString . '%');
                $query->orWhere('description', 'like', '%' . $queryString . '%');
            })
            ->when(!empty(trim($category_id)) && $category_id !== 'all', function ($query) use ($category_id) {
                $query->where('category_id', $category_id);
            })
            ->take(10)->get();
    }

    public static function getProfilePicture()
    {
        return self::getUser()->company->profile->url;
    }

    public static function getProfileBackground()
    {
        return self::getUser()->company->background->url;
    }

    /**
     * Retrieve User Company Type
     *
     * @paran array
     */
    public static function getAll()
    {
        return User::whereType(Users::TYPE_COMPANY)->get();
    }

    public function getAllCompanies()
    {
        return Company::orderBy('name', 'ASC')->get();
    }

    public static function getPaginate(
        $limit = 20,
        $searchQuery = '',
        $status = '',
        $confirmed = '',
        $pageName = '',
        $sortColumn = null,
        $sortOrder = true
    ) {
        $query = User::whereType(Users::TYPE_COMPANY)
            ->select('users.*')
            ->join('companies', 'companies.user_id', '=', 'users.id')
            ->with('company')
            ->whereHas('company', function ($query) use ($searchQuery, $status) {
                $query->when(!empty($searchQuery), function ($query) use ($searchQuery) {
                    $query->where('companies.name', 'like', '%' . $searchQuery . '%');
                    $query->orWhere('companies.description', 'like', '%' . $searchQuery . '%');
                    $query->orWhere('companies.location', 'like', '%' . $searchQuery . '%');
                });
            })
            ->when(!empty(trim($status)) && $status != 'all', function ($query) use ($status) {
                $query->where('users.status', $status);
            })
            ->when(!empty($confirmed), function ($query) use ($confirmed) {
                $query->where('users.confirmed', $confirmed);
            });

        if ($sortColumn) {
            $query->orderBy($sortColumn, $sortOrder ? 'asc' : 'desc');
        }

        return $query->paginate($limit, ['*'], $pageName);
    }

    public static function getAllPluck($field = Users::PLUCK_FIELD)
    {
        return User::whereType(Users::TYPE_COMPANY)->pluck($field);
    }

    public static function getPhotos()
    {
        return self::getUser()->company->getMedia(MediaLibrary::MEDIA_PHOTOS)->isNotEmpty() ? self::getUser()->employee->getMedia(MediaLibrary::MEDIA_PHOTOS) : [];
    }

    public static function getProfile()
    {
        return self::getUser()->company->procile->url;
    }

    public static function getBackground()
    {
        return self::getUser()->company->backgorund->url;
    }

    public static function getTestimonials()
    {
        return self::getUser()->company->testimonials;
    }

    public static function getEmployeesRandom()
    {
        return collect(self::getUser()->company->confirmedEmployees)->shuffle()->take(Companies::EMPLOYEE_LIMIT);
    }

    public static function getEmployees()
    {
        return self::getUser()->company->confirmedEmployees;
    }

    public static function getEmployeesByCompany(Company $company)
    {
        return $company->confirmedEmployees;
    }

    /**
     *
     */
    public function getActiveEmployeesByCompany(Company $company, $searchQuery = null, $limit = 20, $pageName = 'employeePage')
    {
        return $company->employees()
                ->when($searchQuery && !empty(trim($searchQuery)), function ($query) use ($searchQuery) {
                    $query->where(function ($query) use ($searchQuery) {
                            $query->where('first_name', 'like', '%' . $searchQuery . '%');
                            $query->orWhere('last_name', 'like', '%' . $searchQuery . '%');
                    });
                })
            ->whereHas('user', function ($query) {
                $query->where('status', 'active');
            })->orderBy('id', 'DESC')
            ->paginate($limit, ['*'], $pageName);
    }

    public static function getPaginateEmployees(
        $limit = 20,
        $searchQuery = '',
        $status = 'active',
        $confirmed = 'approved',
        $pageName = 'employeesPage',
        $sortColumn = null,
        $sortOrder = true
    ) {
        $query = User::whereType(Users::TYPE_EMPLOYEE)
            ->select('users.*')
            ->join('employees', 'employees.user_id', '=', 'users.id')
            ->with('employee', 'employee.media')
            ->whereHas('employee', function ($query) use ($searchQuery, $status) {
                $query->when(!empty($searchQuery), function ($query) use ($searchQuery) {
                    $query->where('employees.first_name', 'like', '%' . $searchQuery . '%');
                    $query->orWhere('employees.last_name', 'like', '%' . $searchQuery . '%');
                    $query->orWhere('employees.description', 'like', '%' . $searchQuery . '%');
                    $query->orWhere('employees.location', 'like', '%' . $searchQuery . '%');
                });
            })
            ->when(!empty(trim($status)) && $status != 'all', function ($query) use ($status) {
                $query->where('users.status', $status);
            })
            ->when(!empty($confirmed), function ($query) use ($confirmed) {
                $query->where('users.confirmed', $confirmed);
            });

        if ($sortColumn) {
            $query->orderBy($sortColumn, $sortOrder ? 'asc' : 'desc');
        }

        return $query->paginate($limit, ['*'], $pageName);
    }

    /**
     * Verify if User is email is not created, then create a new user company
     * @param string $email
     * @param string $name
     *
     * @return User | bool
     */
    public static function create(string $email, string $name)
    {
        if (!User::where('email', trim($email))->first()) {
            $data = [
                'email' => $email,
                'password' => GlobalService::generateToken(10),
                'type' => self::TYPE_COMPANY,
                'accept_agreements' => true,
                'status' => 'active',
                'confirmed' => self::CONFIRM_APPROVED,
                'status_id' => self::STATUS_DEFAULT,
            ];
            $user = User::create($data);

            #Create Company on the UserObserver
            if (isset($user->company)) {
                $user->company->update(['name' => $name]);
            } else {
                $company = new Company(['user_id' => $user->id, 'name' => $name]);
                $company->save();
            }
            return $user;
        }
        return false;
    }

    /**
     * Companies Stories
     */
    public static function getStories(Company $company, $limit = 10, $pageName = '')
    {
        return $company->stories()->paginate($limit, ['*'], $pageName);
    }

    /**
     * Retrieve a Employee Collection User Type by Company
     * @param Company $company
     * @param
     * return array
     */
    public function getEmployeesUserCollection(Company $company, $startOfDay = null, $endOfDay = null)
    {
        return Employee::with('company')->whereHas('company', function ($query) use ($company) {
            return $query->where('company_id', $company->id);
        })
            ->whereBetween('created_at', [$startOfDay, $endOfDay])
            ->get();
    }
}

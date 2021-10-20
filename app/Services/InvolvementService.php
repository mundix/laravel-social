<?php


namespace App\Services;

use App\Models\Cause;
use App\Models\Company;
use App\Models\Involvement;
use App\Models\Employee;

class InvolvementService
{
    protected $employee;
    protected $companyFavoriteCauses;

    public function __construct(Employee $employee = null)
    {
        if (!is_null($employee)) {
            $this->employee = $employee;
            $this->companyFavoriteCauses = CauseService::getFavoriteCauses($employee->company->first());
            if ($this->companyFavoriteCauses->count() > $this->employee->involvements->count()) {
                $this->fetchInvolvementWithFavoriteCauses();
            }
        }
    }

    /**
     * @param string $searchQuery
     * @param string $orderBy
     * @param string $sort
     */
    public function getEmployeeInvolvement(
        $searchQuery = '',
        $orderBy = 'causes.name',
        $sort = 'asc'
    ): \Illuminate\Database\Eloquent\Collection {
        return $this->employee->involvements()
            ->when($searchQuery && !empty(trim($searchQuery)),
                function ($query) use ($searchQuery) {
                    $query->whereHas('cause', function ($query) use ($searchQuery) {
                        $query->where('name', 'like', '%' . $searchQuery . '%');
                    });
                })
            ->select('involvements.*')
            ->leftJoin('causes', 'causes.id', '=', 'involvements.cause_id')
            ->whereNull('causes.deleted_at')
            ->orderBy($orderBy, $sort)
            ->get();
    }

    /**
     * Generate Involvement is hasn't cause related with employee
     * @return void
     */
    private function fetchInvolvementWithFavoriteCauses(): void
    {
        foreach ($this->companyFavoriteCauses as $cause) {
            if (!$this->isFetchedCause($cause)) {
                $this->create($cause);
            }
        }
    }

    /**
     * Verify if exist relation
     * @param Cause $cause
     * @return  bool
     */
    private function isFetchedCause(Cause $cause): bool
    {
        if (Involvement::where('cause_id', $cause->id)
            ->where('company_id', $this->employee->company->first()->id)->where('employee_id',
                $this->employee->id)->first()) {
            return true;
        }
        return false;
    }

    private function create(Cause $cause): void
    {
        Involvement::create([
            'company_id' => $this->employee->company->first()->id,
            'employee_id' => $this->employee->id,
            'cause_id' => $cause->id,
            'status' => 'publish'
        ]);
    }

    /**
     * Retrieve Company Involvements
     * @param Company $company
     * @param string $sarchQuery
     * @param string $orderBy
     * @param string $sort
     *
     * @return mixed
     */
    public function getCompanyInvolvements(Company $company, $searchQuery, $orderBy = 'causes.name', $sort = 'asc')
    {
        return $company->involvements()
            ->when($searchQuery, function ($query) use ($searchQuery) {
                $query->whereHas('cause', function ($query) use ($searchQuery) {
                    $query->where('name', 'like', '%' . $searchQuery . '%');
                });
            })->when($searchQuery, function ($query) use ($searchQuery) {
                $query->orWhereHas('employee', function ($query) use ($searchQuery) {
                    $query->where('first_name', 'like', '%' . $searchQuery . '%');
                    $query->orWhere('last_name', 'like', '%' . $searchQuery . '%');
                });
            })
            ->select('involvements.*')
            ->leftJoin('causes', 'causes.id', '=', 'involvements.cause_id')
            ->leftJoin('employees', 'employees.id', '=', 'involvements.employee_id')
            ->whereNull('causes.deleted_at')
            ->orderBy($orderBy, $sort)
            ->get();
    }

    public function getCompanyRises(Company $company)
    {
        return $company->involvements()->sum('donations');
    }
    /**
     * Retrieve Total Company Favorite Causes
     * @return int
    */
    public function getTotalInvolvementsByCompany(Company $company)
    {
        return CauseService::getFavoriteCauses($company)->count();
    }
}
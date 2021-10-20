<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\CategoryCause;
use App\Models\Employee;
use App\Models\User;
use App\Services\CauseService;
use App\Services\EmployeeService;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index()
	{
		$employees = Employee::orderBy('id', 'desc')->paginate(50)->get();
		return view('employees.index', compact('employees'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function create()
	{
		return view('employees.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store(StoreEmployeeRequest $request)
	{
		$user = new User();
		$user->fill($request->only('email', 'password'));

		$employee = new Employee();
		$employee->fill($request->all());
		$user->employee()->save($employee);

		return redirect()->route('employees.index')
			->with('success', trans('cruds.employee.title') . ' ' . trans('global.is_created'));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param \App\Models\Employee $employee
	 * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function show(Employee $employee)
	{
	    $user = $employee->user;
        if ($company->user->status !== 'active') {
            return abort(404);
        }
        return redirect()->route('company.slug', $company->slug);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param \App\Models\Employee $employee
	 * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function edit(Employee $employee)
	{
		return view('employees.edit', compact('employee'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param \App\Models\Employee $employee
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update(UpdateEmployeeRequest $request, Employee $employee)
	{
		$user = $employee->user;
		$user->update($request->all());
		$employee->update($request->all());
		return redirect()->route('employees.edit', $employee)
			->with('success', trans('cruds.employee.title') . ' ' . trans('global.is_updated'));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param \App\Models\Employee $employee
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function destroy(Employee $employee)
	{
		$employee->delete();
		return redirect()->route('employees.index');
	}

	public function favorites()
	{
		return view('frontend.employees.favorites')
			->with(['bodyClass' => 'ProfileFavorites']);
	}
}

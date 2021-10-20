<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyInvite extends Model
{
	use HasFactory;

	protected $fillable = ['status', 'company_id', 'employee_id'];

	public function company()
	{
		return $this->belongsTo(Company::class);
	}

	public function employee()
	{
		return $this->belongsTo(Employee::class);
	}
}

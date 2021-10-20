<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Involvement extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'cause_id',
        'employee_id',
        'company_id',
        'hours',
        'donations',
        'matches',
        'status',
    ];

    protected static $logAttributes = [
        'cause_id',
        'employee_id',
        'company_id',
        'hours',
        'donates',
        'matches',
        'status',
    ];

    /**
     * ---------------------------------------
     *  Relations
     * ---------------------------------------
     */
    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function cause(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Cause::class)->whereNull('deleted_at');
    }

    public function employee(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}

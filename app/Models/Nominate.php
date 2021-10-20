<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Nominate extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'is_promoted',
        'reasons',
        'revision',
        'location',
        'location_type',
        'category_id',
        'status',
    ];

    protected $casts = ['is_promoted' => 'boolean'];
    protected $dates = ['created_at', 'updated_at'];

    protected static $logAttributes = [
        'name',
        'email',
        'is_promoted',
        'reasons',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(CategoryCause::class, 'category_id');
    }
}

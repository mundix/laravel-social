<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'receiver_id', 'type', 'activitytable_type', 'activitytable_id'];

    public function actionable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getTypeLabelAttribute()
    {
        $array = [
            'favorite' => 'Favorited',
            'thank' => 'Sent a Thanks You to',
            'kudo' => 'Gave Kudos to',
        ];
        return $array[$this->type];
    }

    public function getTypeIconAttribute()
    {
        $array = [
            'thank' => 'assets/images/icon-thank-white.svg','create',
            'kudo' => 'assets/images/icon-kudos-white.svg','create',
            'favorite' => 'assets/images/icon-favorite-white.svg','create'
        ];
        return $array[$this->type];
    }
}

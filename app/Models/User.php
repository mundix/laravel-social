<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use \Spatie\Tags\HasTags;

use Overtrue\LaravelFavorite\Traits\Favoriter;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasTags;
    use Favoriter;
    use LogsActivity;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'confirmed',
        'accept_agreements',
        'on_boarding_complete',
        'email',
        'password',
        'status_id',
        'status',
        'instagram',
        'twitter',
        'linkedin',
        'token',
        'facebook'
    ];


    protected static $logAttributes = [
        'instagram',
        'twitter',
        'linkedin',
        'facebook',
        'on_boarding_complete'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'accept_agreements' => 'boolean',
        'on_boarding_complete' => 'boolean'
    ];

    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class, 'user_id', 'id');
    }

    public function company()
    {
        if ($this->type === 'company-admin') {
            return $this->hasOneThrough(Company::class,
                CompanyAdmin::class,
                'user_id',
                'user_id');
        }
        return $this->hasOne(Company::class);
    }


    public function admin(): HasOne
    {
        return $this->hasOne(Admin::class);
    }

    public function causes(): HasMany
    {
        return $this->hasMany(Cause::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function user_token(): HasOne
    {
        return $this->hasOne(UserToken::class);
    }


    public function getFavoriteCausesAttribute()
    {
        return $this->getFavoriteItems(Cause::class);
    }

    public function getTotalFavoriteCausesAttribute()
    {
        $this->getFavoriteItems(Cause::class)->count();
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function nominates(): HasMany
    {
        return $this->hasMany(Nominate::class)->orderBy('id', 'DESC');
    }

    public function testimonials(): HasMany
    {
        return $this->hasMany(Testimonial::class)->orderBy('id', 'DESC');
    }

    public function getHasEventsAttribute(): bool
    {
        return (int)$this->events->count() ? true : false;
    }

    public function sponsors(): BelongsToMany
    {
        return $this->belongsToMany(Event::class)->orderBy('id', 'DESC');
    }

    public function stories(): BelongsToMany
    {
        return $this->belongsToMany(Story::class)->orderBy('id', 'DESC');
    }

    public function activitiesRecords()
    {
        return $this->hasMany(Activity::class, 'user_id')->orderBy('created_at', 'desc');
    }

    public function activities()
    {
        return $this->morphMany(Activity::class, 'actionable');
    }

    public function getLogNameAttribute()
    {
        if ($this->company) {
            return $this->company->name;
        } else {
            return $this->employee->fullname;
        }
    }

    public function getImageAttribute()
    {
        if ($this->company) {
            return $this->company->profile->url ?? '';
        } else {
            return $this->employee->profile->url ?? '';
        }
    }

    public function getUrlAttribute()
    {
        if ($this->company) {
            return route('company.slug', $this->company->slug);
        } else {
            return route('company.employee.show', [$this->employee->company->first()->slug, $this->employee->slug]);
        }
    }

    public function getStatusLabelAttribute()
    {
        $labels = ['active' => 'Enabled', 'disabled' => 'Disabled', 'pending' => 'Pending', 'suspended' => 'Suspended'];
        return $labels[$this->status];
    }
}

<?php

namespace App\Models;

use App\Helpers\Util;
use App\Services\PostService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\File;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Image\Manipulations;

class Employee extends Model implements HasMedia
{
    use HasFactory;
    use HasSlug, InteractsWithMedia;
    use LogsActivity;

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['first_name', 'last_name'])
            ->saveSlugsTo('slug');
    }

    protected $fillable = ['first_name', 'last_name', 'job_title', 'location', 'description', 'status', 'quiz_primary_id', 'quiz_secondary_id'];

    protected static $logAttributes = ['first_name', 'last_name', 'job_title', 'location', 'description', 'quiz_primary_id', 'quiz_secondary_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function invite()
    {
        return $this->hasOne(CompanyInvite::class, 'employee_id', 'id');
    }

    public function company()
    {
        return $this->belongsToMany(Company::class);
    }

    public function getFullnameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function quizPrimary(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(QuizAnswer::class, 'quiz_primary_id');
    }
    public function quizSecondary(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(QuizAnswer::class, 'quiz_secondary_id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('profile')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png'])
            ->registerMediaConversions(function (Media $media) {
                $this
                    ->addMediaConversion('thumb')
                    ->width(200)
                    ->withResponsiveImages();
                $this
                    ->addMediaConversion('medium')
                    ->width(450)
                    ->withResponsiveImages();
                $this
                    ->addMediaConversion('large')
                    ->width(1024)
                    ->withResponsiveImages();
            });

        $this->addMediaCollection('background')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png'])
            ->registerMediaConversions(function (Media $media) {
                $this
                    ->addMediaConversion('thumb')
                    ->width(200)
                    ->withResponsiveImages();
                $this
                    ->addMediaConversion('medium')
                    ->width(450)
                    ->withResponsiveImages();
                $this
                    ->addMediaConversion('large')
                    ->width(1024)
                    ->withResponsiveImages();
            });

        $this->addMediaCollection('photos')
            ->acceptsMimeTypes(['image/jpeg', 'image/png'])
            ->registerMediaConversions(function (Media $media) {
                $this
                    ->addMediaConversion('thumb')
                    ->width(200)
                    ->withResponsiveImages();
                $this
                    ->addMediaConversion('medium')
                    ->width(450)
                    ->withResponsiveImages();
                $this
                    ->addMediaConversion('large')
                    ->width(1024)
                    ->withResponsiveImages();
            });

        $this->addMediaCollection('video')
            ->singleFile();
    }

    /**
     * This accessor get the media file url , if not exist retrieve an image created with first letter of the first name and last name
     * @return object
     */
    /**
     * Profile Picture getter
     * @return object
     */
    public function getProfileAttribute()
    {
        if ($file = $this->getMedia('profile')->first()) {
            if ($file) {
                $file->url = $file->getUrl();
                $file->thumbnail = $file->getUrl('thumb');
                $file->profile = $file->getUrl('medium');
            }
        } else {
            $file = new \stdClass();
            $file->url = 'https://ui-avatars.com/api/?name=' . $this->first_name . '+' . $this->last_name;
            $file->thumbnail = 'https://ui-avatars.com/api/?name=' . $this->first_name . '+' . $this->last_name;
            $file->profile = 'https://ui-avatars.com/api/?name=' . $this->first_name . '+' . $this->last_name . '&color=004b5c&bold=true&size=140';
        }

        return $file;
    }

    /**
     * Background Picture getter
     * @return object
     */
    public function getBackgroundAttribute()
    {
        if ($file = $this->getMedia('background')->first()) {
            if ($file) {
                $file->url = $file->getUrl();
                $file->thumbnail = $file->getUrl('thumb');
                $file->profile = $file->getUrl('medium');
            }
        } else {
            $file = new \stdClass();
            $file->url = 'https://ui-avatars.com/api/?name=' . $this->first_name . '+' . $this->last_name;
            $file->thumbnail = 'https://ui-avatars.com/api/?name=' . $this->first_name . ' + ' . $this->last_name;
            $file->profile = 'https://ui-avatars.com/api/?name=' . $this->first_name . ' + ' . $this->last_name . '&color=004b5c&bold=true&size=140';
        }

        return $file;
    }

    public function getVideoAttribute()
    {
        if ($file = $this->getMedia('video')->first()) {
            if ($file) {
                $file->url = $file->getUrl();
            }
        } else {
            $file = new \stdClass();
            $file->url = null;
        }

        return $file;
    }

    /**
     * Verify if has Photos in Gallery
     * @return boolean
     */
    public function getHasPhotosAttribute()
    {
        if ($this->getMedia('photos')->isNotEmpty()) {
            return true;
        }
        return false;
    }

    /**
     * Verify if has Photos in Gallery
     * @return boolean
     */
    public function getHasVideoAttribute()
    {
        if ($this->getMedia('video')->isNotEmpty()) {
            return true;
        }
        return false;
    }

    public function getSummaryAttribute()
    {
        return preg_replace("/\\<.*?\\>/", '', $this->attributes['description']);
    }


    public function companies(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Company::class);
    }

    public function involvements(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Involvement::class, 'employee_id');
    }

    public function getIsConfirmedAttribute()
    {
        if($this->user->confirmed === 'approved' && (int)$this->user->accept_agreements && (int) $this->user->on_boarding_complete) {
            return true;
        }
        return false;
    }

    public function getExcerptAttribute()
    {
        return Util::get_excerpt($this->description, 30);
    }
}

<?php

namespace App\Models;

use App\Helpers\Util;
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

class Company extends Model implements HasMedia
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
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    protected $fillable = [
        'name',
        'user_id',
        'primary_color',
        'secondary_color',
        'description',
        'location',
        'caption',
        'status',
        'about',
        'about_title',
        'about_link'
    ];

    protected static $logAttributes = [
        'name',
        'primary_color',
        'secondary_color',
        'description',
        'location',
        'caption',
        'about',
        'about_title',
        'user.instagram',
        'user.twitter',
        'user.linkedin',
        'user.facebook',
        'user.on_boarding_complete'
    ];


    /**
     * ------------------------------------------
     * Relation
     * ------------------------------------------
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function invites()
    {
        return $this->hasMany(CompanyInvite::class, 'company_id', 'id');
    }

    public function testimonials()
    {
        return $this->hasMany(Testimonial::class, 'company_id');
    }

    public function involvements()
    {
        return $this->hasMany(Involvement::class, 'company_id', 'id');
    }

    public function stories()
    {
        return $this->hasMany(Story::class);
    }

    public function confirmedEmployees()
    {
        return $this->belongsToMany(Employee::class)
            ->whereHas('user', function ($query) {
                return $query->whereStatus('pending');
            });
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class);
    }

    public function posts()
    {
        return $this->user->posts();
    }

    /**
     * -----------------------------------------
     *  Media Relations
     * -----------------------------------------
     */
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

        $this->addMediaCollection('about')
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

        $this->addMediaCollection('video')
            ->singleFile();
    }

    /**
     * ---------------------------------------
     * Accessors
     * ---------------------------------------
     */
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
        $file = $this->getMedia('profile')->last();

        if ($file) {
            $file->url = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
            $file->medium = $file->getUrl('medium');
            $file->large = $file->getUrl('large');
        } else {
            $file = new \stdClass();
            $file->url = 'https://ui-avatars.com/api/?name=' . $this->name;
            $file->thumbnail = 'https://ui-avatars.com/api/?name=' . $this->name;
            $file->profile = 'https://ui-avatars.com/api/?name=' . $this->name . '&color=004b5c&bold=true&size=140';
        }
        return $file;
    }

    /**
     * Profile Picture getter
     * @return object
     */
    public function getBackgroundAttribute()
    {
        $file = $this->getMedia('background')->last();

        if ($file) {
            $file->url = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
            $file->medium = $file->getUrl('medium');
            $file->large = $file->getUrl('large');
        } else {
            $file = new \stdClass();
            $file->url = 'https://ui-avatars.com/api/?name=' . $this->name;
            $file->thumbnail = 'https://ui-avatars.com/api/?name=' . $this->name;
            $file->profile = 'https://ui-avatars.com/api/?name=' . $this->name . '&color=004b5c&bold=true&size=140';
        }
        return $file;
    }

    /**
     * Background Picture getter
     * @return object
     */
    public function getCoverAttribute()
    {
        $file = $this->getMedia('about')->last();

        if ($file) {
            $file->url = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
            $file->medium = $file->getUrl('medium');
            $file->large = $file->getUrl('large');
        } else {
            $file = new \stdClass();
            $file->url = 'https://ui-avatars.com/api/?name=' . $this->name;
            $file->thumbnail = 'https://ui-avatars.com/api/?name=' . $this->name;
            $file->profile = 'https://ui-avatars.com/api/?name=' . $this->name . '&color=004b5c&bold=true&size=140';
        }
        return $file;
    }

    public function getVideoAttribute()
    {
        $file = $this->getMedia('video')->last();
        if ($file) {
            $file->url = $file->getUrl();
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

    public function getExcerptAttribute()
    {
        return Util::get_excerpt($this->description, 30);
    }

    public function getCleanDescriptionAttribute()
    {
        return Util::get_clean($this->description);
    }

    public function getCleanAboutAttribute()
    {
        return Util::get_clean($this->about);
    }

}

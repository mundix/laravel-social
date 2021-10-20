<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Story extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    use LogsActivity;

    protected $fillable = ['content', 'title', 'status', 'referral_id', 'company_id'];

    protected static $logAttributes = ['content', 'title'];

    /**
     * ---------------------------------------
     *  Relations
     * ---------------------------------------
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function sponsors()
    {
        return $this->belongsToMany(User::class, 'story_sponsor');
    }

    public function submitted()
    {
        return $this->belongsTo(User::class, 'referral_id', 'id');
    }

    /**
     * -----------------------------------------
     *  Media Relations
     * -----------------------------------------
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('picture')
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
    }

    /**
     * ---------------------------------------
     * Accessors
     * ---------------------------------------
     */
    /**
     * Verify if has Picture in Gallery
     * @return boolean
     */
    public function getHasPictureAttribute()
    {
        if ($this->getMedia('picture')->isNotEmpty()) {
            return true;
        }
        return false;
    }

    /**
     * Verify if has Icon in Gallery
     * @return boolean
     */
    public function getHasIconAttribute()
    {
        if ($this->getMedia('picture')->isNotEmpty()) {
            return true;
        }
        return false;
    }

    public function getPictureAttribute()
    {
        if ($file = $this->getMedia('picture')->first()) {
            if ($file) {
                $file->url = $file->getUrl();
            }
        } else {
            $file = new \stdClass();
            $file->url = 'https://ui-avatars.com/api/?name=' . $this->title;
        }

        return $file;
    }

}

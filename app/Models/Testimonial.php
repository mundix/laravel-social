<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\File;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Image\Manipulations;

class Testimonial extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    use LogsActivity;

    protected $fillable = ['author', 'name', 'referral_id', 'job_title', 'location', 'content', 'status', 'cause_id'];

    protected static $logAttributes = ['author', 'name', 'job_title', 'location', 'content', 'cause_id'];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function cause(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Cause::class);
    }

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
     * Profile Picture getter
     * @return object
     */
    public function getPictureAttribute()
    {
        if ($file = $this->getMedia('picture')->first()) {
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
}

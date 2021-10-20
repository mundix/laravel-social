<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;

class Admin extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('picture')
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
    public function getProfileAttribute()
    {
        $file = $this->getMedia('picture')->last();

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

    public function getFullnameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}

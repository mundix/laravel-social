<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\File;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Image\Manipulations;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

use Conner\Likeable\Likeable;

use Overtrue\LaravelFavorite\Traits\Favoriteable;

class Cause extends Model implements HasMedia
{
    use HasFactory, HasSlug, InteractsWithMedia;
    use Favoriteable;
    use SoftDeletes;
    use Likeable;

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
        'user_id',
        'referral_id',
        'name',
        'description',
        'website',
        'category_id',
        'is_nominated',
        'status',
        'is_promoted',
        'matchable',
        'location',
        'location_type',
        'phone',
        'email'
    ];

    protected $hidden = ['category_id'];
    protected $casts = ['is_promoted' => 'boolean', 'matchable' => 'boolean'];

    public function category()
    {
        return $this->belongsTo(CategoryCause::class, 'category_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function referral()
    {
        return $this->belongsTo(User::class, 'referral_id');
    }

    public function activities()
    {
        return $this->morphMany(Activity::class, 'actionable');
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
     * Verify if has Picture in Gallery
     * @return boolean
     */
    public function getHasPhotosAttribute()
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
            $file->url = 'https://ui-avatars.com/api/?name=' . $this->name;
        }

        return $file;
    }

    public function getLogNameAttribute()
    {
        return $this->name;
    }

    public function getImageAttribute()
    {
        return $this->picture->url ?? '';
    }

    public function getUrlAttribute()
    {
        $slug = '#';
        if($this->user->company) {
            $slug = $this->user->company->slug;
        }
        if($this->user->employee){
            $slug = $this->user->employee->company->slug;
        }

        return route('company.causes', $slug);
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('active', function (\Illuminate\Database\Eloquent\Builder $builder) {
            $builder->whereNull('deleted_at');
        });
    }
}

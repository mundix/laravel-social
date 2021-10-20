<?php

namespace App\Models;

use App\Helpers\Util;
use App\Services\PostService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\File;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Image\Manipulations;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Post extends Model implements HasMedia
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
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    protected $fillable = [
        'title',
        'content',
        'summary',
        'category_id',
        'user_id',
        'author',
        'slug',
        'status',
    ];

    protected static $logAttributes = [
        'title',
        'content',
        'summary',
        'author',
        'status'
    ];

    protected $dates = ['created_at' , 'updated_at'];

    public function category()
    {
        return $this->belongsTo(CategoryPost::class, 'category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
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
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif'])
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
        $file = $this->getMedia('picture')->last();

        if ($file) {
            $file->url = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
            $file->medium = $file->getUrl('medium');
            $file->large = $file->getUrl('large');
        } else {
            $file = new \stdClass();
            $file->url = 'https://ui-avatars.com/api/?name=' . $this->title;
            $file->thumbnail = 'https://ui-avatars.com/api/?name=' . $this->title;
            $file->profile = 'https://ui-avatars.com/api/?name=' . $this->title . '&color=004b5c&bold=true&size=140';
        }
        return $file;
    }

    /**
     * @return mixed
    */
    public function getExcerptAttribute()
    {
        return Util::get_excerpt($this->content, 30);
    }

    public function getStatusLabelAttribute()
    {
        $textArray = ['publish' => 'Published', 'draft' => 'Draft', 'disabled' => 'Draft', 'pending' => 'Pending'];
        return $textArray[$this->status] ?? '';
    }
}

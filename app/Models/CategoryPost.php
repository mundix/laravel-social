<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class CategoryPost extends Model
{
    use HasFactory;
    use HasSlug;

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom( 'name')
            ->saveSlugsTo('slug');
    }

    protected  $fillable = ['name', 'status'];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}

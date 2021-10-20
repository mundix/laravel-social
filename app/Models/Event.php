<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

use Spatie\MediaLibrary\MediaCollections\File;
use Spatie\Image\Manipulations;

class Event extends Model implements HasMedia
{
	use HasFactory, HasSlug , InteractsWithMedia;
    use LogsActivity;


    /**
	 * Get the options for generating the slug.
	 */
	public function getSlugOptions() : SlugOptions
	{
		return SlugOptions::create()
			->generateSlugsFrom( 'name')
			->saveSlugsTo('slug');
	}

	protected $fillable = [
		'user_id',
		'referral_id',
		'slug',
		'name',
		'participants',
		'description',
		'global_amount',
		'total_amount',
		'due_date',
		'status'
	];

    protected static $logAttributes = [
        'name',
        'participants',
        'description',
        'global_amount',
        'total_amount',
        'due_date',
    ];

	public $dates = ['due_date'];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

    public function submitted()
    {
        return $this->belongsTo(User::class, 'referral_id', 'id');
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
	}

	/**
	 * Verify if has Picture in Gallery
	 * @return boolean
	 */
	public function getHasProfileAttribute()
	{
		if ($this->getMedia('profile')->isNotEmpty()) {
			return true;
		}
		return false;
	}

	public function getProfileAttribute()
	{
		if ($file = $this->getMedia('profile')->first()) {
			if ($file) {
				$file->url = $file->getUrl();
			}
		} else {
			$file = new \stdClass();
			$file->url = 'https://ui-avatars.com/api/?name=' . $this->name;
		}

		return $file;
	}

	public function getStatusLabelAttribute()
	{
		switch ($this->status) {
			case 'draft':
				return \Str::of($this->status)->ucfirst();
			case 'pending':
				return \Str::of($this->status)->ucfirst();
			case 'enabled':
				return \Str::of($this->status)->ucfirst();
			case 'suspended':
				return \Str::of($this->status)->ucfirst();
		}
	}

	public function getGlobalAmountLabelAttribute()
	{
		return number_format($this->global_amount,2);
	}

	public function sponsors()
	{
		return $this->belongsToMany(User::class);
	}

	public function isSponsor(User $user)
	{
		return $this->sponsors()->where('user_id', $user->id)->exists();
	}
}

<?php


namespace App\Helpers;

use Spatie\MediaLibrary\MediaCollections\Events\MediaHasBeenAdded;

class MediaLogger
{
    public function handle(MediaHasBeenAdded $event)
    {
        $media = $event->media;

        $properties = [
            "name" => $media->name,
            "collection_name" => $media->collection_name,
            "model_id" => $media->model_id,
            "model_type" => $media->model_type,
            "id" => $media->id,
        ];

        activity()
            ->causedBy(auth()->user())
            ->performedOn($media)
            ->withProperties($properties)
            ->log('created');
    }
}
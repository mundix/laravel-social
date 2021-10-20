<?php


namespace App\Interfaces;


interface MediaLibrary
{
	public static function getPhotos();
	public static function getProfile();
	public static function getBackground();

	const MEDIA_PROFILE = 'profile';
	const MEDIA_BACKGROUND = 'background';
	const MEDIA_PHOTOS = 'photos';
}
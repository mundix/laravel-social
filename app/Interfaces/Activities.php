<?php


namespace App\Interfaces;


interface Activities
{
	const TYPES_KUDO = 'likes';
	const TYPES_FAVORITE = 'favorites';
	const TYPES_THANK = 'thanks';

	const MESSAGE_THANK = 'Sent a Thank you to';
	const MESSAGE_FAVORITE = 'Favorited';
	const MESSAGE_KUDO = 'Gave Kudos to';

	const LIMIT = 5;
}
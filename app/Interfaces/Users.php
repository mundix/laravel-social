<?php


namespace App\Interfaces;


interface Users
{
	public static function getUser();

	public const CONFIRM_APPROVED = 'approved';
	public const CONFIRM_PENDING = 'pending';
	public const CONFIRM_REJECTED = 'rejected';
	public const CONFIRM_SUSPENDED = 'suspended';

	public const CONFIRM_DEFAULT = self::CONFIRM_PENDING;

	public const TYPE_USER = 'user';
	public const TYPE_EMPLOYEE = 'employee';
	public const TYPE_COMPANY = 'company';
	public const TYPE_ADMIN = 'admin';

	public const TYPE_DEFAULT = self::TYPE_USER;

	public const STATUS_DEFAULT = 3;

	public const PLUCK_FIELD = 'id';

	public const EMAIL_DELAYS = 1;

}
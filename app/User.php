<?php

namespace App;

use App\Notifications\VerifyApiEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
// for passport
use Illuminate\Notifications\Notifiable;
// for email verify
use Laravel\Cashier\Billable;
// for cashier payment
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail {
	use HasApiTokens, Notifiable;
	use Billable;

	const STATUS_ACTIVE = 'active';
	const STATUS_INACTIVE = 'inactive';
	const STATUS_BLOCK = 'blocked';
	const STATUS_EMAIL_VERIFICATION = 'email_verification';
	const STATUS_MANUAL_VERIFICATION = 'email_verification';
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name', 'email', 'password',
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token',
	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'email_verified_at' => 'datetime',
	];

	public function roles() {
		return $this
			->belongsToMany('App\Role')
			->withTimestamps();
	}

	public function authorizeRoles($roles) {
		if ($this->hasAnyRole($roles)) {
			return true;
		}
		abort(401, 'This action is unauthorized.');
	}
	public function hasAnyRole($roles) {
		if (is_array($roles)) {
			foreach ($roles as $role) {
				if ($this->hasRole($role)) {
					return true;
				}
			}
		} else {
			if ($this->hasRole($roles)) {
				return true;
			}
		}
		return false;
	}
	public function hasRole($role) {

		if ($this->roles()->where('name', $role)->first()) {
			return true;
		}
		return false;
	}

	public function sendApiEmailVerificationNotification($url = '') {
		$this->notify(new VerifyApiEmail($url)); // my notification
	}

	/**
	 * @return bool
	 */
	public function isAdmin(): bool {
		return $this->hasRole(Role::ROLE_ADMIN);
	}

	/**
	 * @return bool
	 */
	public function isCustomer(): bool {
		return $this->hasRole(Role::ROLE_CUSTOMER);
	}

	/**
	 * @return bool
	 */
	public function isProvider(): bool {
		return $this->hasRole(Role::ROLE_PROVIDER);
	}

	/**
	 * @return int
	 */
	public function getId(): int {
		return $this->id;
	}

	public function getAccessToken() {
		return $this->accessToken;
	}

	public function getScopes() {
		return $this->getAccessToken() ? $this->getAccessToken()->scopes : [];
	}

	public static function getAllStatus() {
		return [
			self::STATUS_ACTIVE,
			self::STATUS_INACTIVE,
			self::STATUS_BLOCK,
			self::STATUS_EMAIL_VERIFICATION,
			self::STATUS_MANUAL_VERIFICATION,
		];
	}
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use app\AccessCategory;

class Node extends BFModel
{
	const STATUS_ACTIVE 		= 1;
	const STATUS_ERROR 			= 2;
	const STATUS_LOCKOUT 		= 3;
	const STATUS_INACTIVE 		= 4;
	const STATUS_DECOMISSIONED 	= 5;

	public static $statuses = [
		self::STATUS_ACTIVE 		=> 'Active',
		self::STATUS_ERROR 			=> 'Error',
		self::STATUS_LOCKOUT 		=> 'Locked Out',
		self::STATUS_INACTIVE 	 	=> 'Inactive',
		self::STATUS_DECOMISSIONED 	=> 'Decomissioned',
	];

	public function access_category() {
        return $this->belongsTo('App\AccessCategory');
    }

    public function hasAccessFlag(AccessFlag $accessFlag) {
		return $this->access_flags & $accessFlag->access_flags;
    }
}
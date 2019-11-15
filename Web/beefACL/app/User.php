<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Traits\Encryptable;

class User extends Authenticatable
{
    use Notifiable;

    use Encryptable;

    protected $encryptable = [
        'risk_notes'
    ];

    const ROLE_SUPER_ADMIN = 1;
    const ROLE_REGULAR_ADMIN = 2;
    const ROLE_CAN_VIEW_NODES = 4;
    const ROLE_CAN_MANAGE_NODES = 8;
    const ROLE_CAN_VIEW_ACCESS_CATEGORIES = 16;
    const ROLE_CAN_MANAGE_ACCESS_CATEGORIES = 32;

    static $roles = [
        self::ROLE_SUPER_ADMIN => 'Super Admin',
        self::ROLE_REGULAR_ADMIN => 'Regular Admin',
        self::ROLE_CAN_VIEW_NODES => 'Can View Nodes',
        self::ROLE_CAN_MANAGE_NODES => 'Can Manage Nodes',
        self::ROLE_CAN_VIEW_ACCESS_CATEGORIES => 'Can View Node Access Categories',
        self::ROLE_CAN_MANAGE_ACCESS_CATEGORIES => 'Can Manage Node Access Categories',
    ];

    const STATUS_PENDING   = 0;
    const STATUS_ACTIVE    = 1;
    const STATUS_SUSPENDED = 2;
    const STATUS_CANCELLED = 3;

    public static $statuses = [
        self::STATUS_PENDING   => 'Pending',
        self::STATUS_ACTIVE    => 'Active',
        self::STATUS_SUSPENDED => 'Suspended',
        self::STATUS_CANCELLED => 'Cancelled',
    ];

    const FLAG_CHANGE_PASSWORD = 1;

    public static $flags = [
        self::FLAG_CHANGE_PASSWORD => 'Force Password Change',
    ];

    const RISK_FLAG_ANY = 1;
    const RISK_FLAG_SUPERVISION = 2;

    static $riskFlags = [
        self::RISK_FLAG_ANY => [
            'name' => 'I have a condition you need to know about',
            'description' => 'Do you have any medical condition that may limit or effect your safe use of our facilities or equipment? This does not preclude you from membership but we are required to know so that we can take appropriate measures.',
        ],
        self::RISK_FLAG_SUPERVISION => [
            'name' => 'I may need assistance or supervision',
            'description' => 'Do you need someone available while you use the space to facilitate your safe use of the equipment or facilities?',
        ],
    ];
    
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

    /**
     * The user has a given access flag, set $ignoreSuperAdmin to true to ignore super admin
     * otherwise it will always allow a user with super admin privilege to do something.
     *
     * @var bool
     */
    public function hasRole($roleFlag, $ignoreSuperAdmin = false) {
        return $this->role_flags & $roleFlag || (!$ignoreSuperAdmin && $this->role_flags & self::ROLE_SUPER_ADMIN);
    }

    /**
     * The user has a given generic/utility flag
     *
     * @var bool
     */
    public function hasFlag($flag) {
        return $this->flags & $flag;
    }

    /**
     * The user has a given generic/utility flag
     *
     * @var bool
     */
    public function hasRiskFlag($flag) {
        return $this->flags & $flag;
    }

}
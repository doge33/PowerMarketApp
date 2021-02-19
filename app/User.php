<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use Notifiable; // contains the notify method . call like this: $user -> notify(new NewSharedProject($project)); & add on top: use App\Notifications\NewSharedProject;
    //alternatively, use Notification Facade like so:      Notification::send($users, new InvoicePaid($invoice));

    const ADMIN = 1;
    const ORG_ADMIN = 2;
    const ORG_MEMBER = 3;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'picture', 'role_id', 'jsonData', 'user_category', 'organization_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'role_id'
    ];

    /**
     * Get the role of the user
     *
     * @return \App\Role
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function accounts()
    {
        return $this->belongsToMany(Account::class);
    }

    public function clusters()
    {
        return $this->belongsToMany(Cluster::class);
    }

    /**
     * Get the path to the profile picture
     *
     * @return string
     */
    public function profilePicture()
    {
        if ($this->picture) {
            return "/storage/{$this->picture}";
        }
        return e(asset('argon')) . "/img/theme/team-4.jpg";
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function hasOrganization()
    {
        return $this->organization != null;
    }
    /**
     * Get the JSON Data
     *
     * @return string
     */
    public function jsonData()
    {
        if ($this->jsonData) {
            return $this->jsonData;
        }
        //        return Storage::disk('local')->get('sample.json');
        //        return Storage::get('sample.json');
    }
    /**
     * Check if the user has admin role
     *
     * @return boolean
     */
    public function isAdmin()
    {
        return $this->role_id == self::ADMIN;
    }
    /**
     * Check if the user has creator role
     *
     * @return boolean
     */
    public function isCreator()
    {
        return $this->role_id == self::ADMIN;
    }

    public function isOrgAdmin()
    {
        return $this->role_id == self::ADMIN || $this->role_id == self::ORG_ADMIN;
    }

    public function isMember()
    {
        return $this->role_id == self::ORG_MEMBER;
    }
}

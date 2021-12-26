<?php

namespace App\Models\Eloquent;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
// use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

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

    public function info()
    {
        return [
            'id' => $this->id,
            'username' => $this->name,
            'email' => $this->email,
            'is_admin' => $this->is_admin,
        ];
    }

    public function getGroupAttribute()
    {
        $group_member = GroupMember::where('user_id',$this->id)->first();
        if(!empty($group_member)) {
            return Group::find($group_member->group_id);
        }
        return null;
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'id' => $this->id,
            'username' => $this->name,
            'email' => $this->email,
            'is_admin' => $this->is_admin,
        ];
    }

}

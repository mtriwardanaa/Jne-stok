<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $appends = ['wa'];
    protected $fillable = [
        'nama', 'no_hp', 'password', 'level', 'id_divisi', 'username', 'id_agen_kategori'
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

    public function getWaAttribute()
    {
    	$new_phone = null;
    	$phone = $this->no_hp;
    	if (isset($phone)) {
    		$first = $phone[0];
    		if ($first == 0) {
    			$trim = ltrim($phone, $phone[0]);
    			$new_phone = '+62'.$trim;
    		}
    	}

    	return $new_phone;
    }

    public function devices()
    {
        return $this->hasMany('App\UserDevice', 'id_user');
    }

    public function divisi()
    {
        return $this->belongsTo('App\Divisi', 'id_divisi');
    }

    public function kategori()
    {
        return $this->belongsTo('App\AgenKategori', 'id_agen_kategori');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $id_divisi
 * @property integer $id_agen_kategori
 * @property string $nama
 * @property string $username
 * @property string $no_hp
 * @property string $password
 * @property string $level
 * @property string $remember_token
 * @property string $created_at
 * @property string $updated_at
 * @property AgenKategori $agenKategori
 * @property Divisi $divisi
 * @property AgenCancel[] $agenCancels
 * @property AgenCancel[] $agenCancels
 * @property Laporan[] $laporans
 * @property Laporan[] $laporans
 * @property UserDevice[] $userDevices
 */
class User extends Model
{
    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['id_divisi', 'id_agen_kategori', 'nama', 'username', 'no_hp', 'password', 'level', 'remember_token', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function agenKategori()
    {
        return $this->belongsTo('App\AgenKategori', 'id_agen_kategori');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function divisi()
    {
        return $this->belongsTo('App\Divisi', 'id_divisi');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function agenCancels()
    {
        return $this->hasMany('App\AgenCancel', 'id_user_handle');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function agenCancels()
    {
        return $this->hasMany('App\AgenCancel', 'id_user_input');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function laporans()
    {
        return $this->hasMany('App\Laporan', 'id_user_handle');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function laporans()
    {
        return $this->hasMany('App\Laporan', 'id_user_input');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userDevices()
    {
        return $this->hasMany('App\UserDevice', 'id_user');
    }
}

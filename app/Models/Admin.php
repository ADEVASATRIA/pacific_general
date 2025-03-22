<?php

namespace App\Models;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admin extends Authenticatable implements JWTSubject
// class Admin extends DefaultModel
{
    use SoftDeletes;

    protected $table = 'admins';

    protected $fillable = [
        'username',
        'name',
        'password',
        'pin',
        'is_active',
        'role_id'
    ];

    protected $hidden = [
        'password',
        'pin',
    ];

    public static function tableInit() {
        Schema::create(self::getTableName(), function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('role_id');
            $table->string('username');
            $table->string('name');
            $table->string('password');
            $table->string('pin');
            $table->boolean('is_active');
        
            $table->foreign('role_id')->references('id')->on(Role::getTableName())->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
        
    }

    public function role() {
        return $this->belongsTo(Role::class);
    }

    // JWT Methods
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}



<?php

namespace App\Models;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Role extends DefaultModel
{
    protected $fillable = [
        'name',
        'status'
    ];

    public static function tableInit() {
        Schema::create(self::getTableName(), function (Blueprint $table) {
           $table->increments('id');
           $table->string('name');
           $table->boolean('status');
           $table->timestamps();
           $table->softDeletes(); 
        });
    }

    public static function tableSeed(){
        Role::insert([
            [
                'name' => 'superadmin',
                'status' => true
            ],
            [
                'name' => 'admin',
                'status' => true
            ],
            [
                'name' => 'owner',
                'status' => true
            ]
        ]);
    }
    
}


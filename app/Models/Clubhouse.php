<?php

namespace App\Models;

use Illuminate\Database\Console\Migrations\StatusCommand;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Clubhouse extends DefaultModel
{
    protected $fillable = [
        'name',
        'location',
        'phone',
        'status'
    ];

    public static function tableInit(){
        Schema::create(self::getTableName(), function (Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->string('location');
            $table->string('phone');
            $table->boolean('status');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public static function tableSeed(){
        Clubhouse::insert([
            [
                'name' => 'Clubhouse 1',
                'location' => 'Jl. Raya',
                'phone' => '08123456789',
                'status' => true
            ],
            [
                'name' => 'Clubhouse 2',
                'location' => 'Jl. Raya',
                'phone' => '08123456789',
                'status' => true
            ],
            [
                'name' => 'Clubhouse 3',
                'location' => 'Jl. Raya',
                'phone' => '08123456789',
                'status' => true
            ]
        ]);
    }

    public function ticketType(){
        return $this->hasMany(TicketType::class);
    }
}

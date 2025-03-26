<?php

namespace App\Models;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TicketType extends DefaultModel
{
    protected $fillable = [
        'clubhouse_id',
        'type_ticket',
        'name',
        'price',
        'duration',
        'status',
        'ticket_code_ref',
    ];

    public static function tableInit(){
        Schema::create(self::getTableName(), function (Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('clubhouse_id')->nullable();
            $table->enum('type_ticket', [1, 2, 3])->comment('1:regular 2:member 3:package');
            $table->string('name');
            $table->unsignedInteger('price');
            $table->unsignedInteger('duration')->comment('days');
            $table->boolean('status');

            $table->foreign('clubhouse_id')->references('id')->on(Clubhouse::getTableName())->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public static function tableSeed(){
        TicketType::insert([
            [
                'clubhouse_id' => null,
                'type_ticket' => '1',
                'name' => 'Dewasa',
                'price' => '40000',
                'duration' => '1',
                'status' => '1',
            ],
            [
                'clubhouse_id' => null,
                'type_ticket' => '1',
                'name' => 'Anak-Anak (Tinggi >= 150 CM)',
                'price' => '40000',
                'duration' => '1',
                'status' => '1',
            ],
            [
                'clubhouse_id' => '1',
                'type_ticket' => '2',
                'name' => 'Member Club Renang 1 Bulan',
                'price' => '150000',
                'duration' => '30',
                'status' => '1',
            ],
            [
                'clubhouse_id' => '2',
                'type_ticket' => '2',
                'name' => 'Member Club Renang 3 Bulan',
                'price' => '450000',
                'duration' => '90',
                'status' => '1',
            ],
            [
                'clubhouse_id' => '3',
                'type_ticket' => '2',
                'name' => 'Member Club Renang 6 Bulan',
                'price' => '900000',
                'duration' => '180',
                'status' => '1',
            ],
        ]);
    }

    public function coachPass(){
        return $this->hasMany(CoachPass::class);
    }

    public function memberPass(){
        return $this->hasMany(MemberPass::class);
    }

    public function ticket(){
        return $this->hasMany(Ticket::class);
    }

    public function clubhouse(){
        return $this->belongsTo(Clubhouse::class);
    }

    public function purchaseDetail(){
        return $this->hasMany(PurchaseDetail::class);
    }

    public function packageDetail(){
        return $this->hasMany(\App\Models\Package\PackageDetail::class);
    }
}

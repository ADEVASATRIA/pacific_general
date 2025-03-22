<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class DefaultModelNoSoftDelete extends Model
{
	public function __construct() {
		parent::__construct();
	}
	
	public static function getTableName() {
		return (new static)->getTable();
	}

	public static function tableInit() {

	}

	public static function tableSeed() {

	}

	public static function tableDrop() {
		Schema::dropIfExists(static::getTableName());
	}
	
	public function scopeActive($query, $is_active=true) {
		$query->where('is_active', $is_active);
    }

}

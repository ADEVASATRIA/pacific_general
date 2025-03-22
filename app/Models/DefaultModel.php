<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class DefaultModel extends DefaultModelNoSoftDelete
{
	use SoftDeletes;
	
	protected $hidden = [
        'deleted_at'
    ];
	
	public function __construct() {
		parent::__construct();
	}

}

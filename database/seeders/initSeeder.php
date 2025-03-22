<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

use App\Models\Role;
use App\Models\TicketType;
use App\Models\Clubhouse;
class initSeeder extends Seeder
{
    public function run(): void
    {
        Role::tableSeed();
        Clubhouse::tableSeed();
        TicketType::tableSeed();
    }
}

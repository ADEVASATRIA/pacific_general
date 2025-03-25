<?php

namespace App\Services\Purchase;

use App\Models\Customer;

class CustomerService
{
    public function findOrCreateCustomer($data)
    {
        return Customer::firstOrCreate(
            ['phone' => $data['telephone']],
            [
                'customer_type_id' => $data['customer_type_id'] ?? null,
                'name' => $data['customer_name'],
            ]
        );
    }
}

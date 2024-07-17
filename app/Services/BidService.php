<?php

namespace App\Services;

use App\Models\Bid;

class BidService
{
    public function validateBid(array $data)
    {
        // Define rules
        $rules = [
            'user_id' => 'required|exists:users,id', // ensures the user_id exists in the users table
            'price' => ['required', 'regex:/^\d+(\.\d{2})?$/'], 
        ];

        // Define custom error messages 
        $messages = [
            'user_id.required' => 'The user id is required!',
            'price.required' => 'The bid price is required!',
            'price.regex' => 'The price format is invalid.'
        ];

        return validator($data, $rules, $messages);
    }

    public function createBid(array $data)
    {
        return Bid::create($data);
    }

    public function getHighestBidAmount()
    {
        return Bid::max('price');
    }
}
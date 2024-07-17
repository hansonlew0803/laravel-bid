<?php

namespace App\Http\Controllers\Api;

use App\Events\BidSaved;
use App\Http\Controllers\Controller;
use App\Models\Bid;
use App\Models\User;
use App\Services\BidService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BidController extends Controller
{
    protected $bidService;

    public function __construct(BidService $bidService)
    {
        $this->bidService = $bidService;
    }

    public function create(Request $request)
    {
        #write your code for bid creation here...
        #model name = Bid
        #table name = bids
        #table fields = id,price,user_id
        #price only can be 2 decimal and must higher than the latest bid price
        # return status code 201, with message 'Success' and data = ['full_name' => user.first_name + user.last_name]

        // make php unit test pass
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id', // ensures the user_id exists in the users table
                'price' => ['required', 'regex:/^\d+(\.\d{2})?$/', 'numeric', function($attribute, $value, $fail){
                    $max = number_format(Bid::max('price'),2,'.','');
                    if ($value <= (float)$max) {
                        $fail('The bid price cannot lower than '.$max);
                    }
                }], 
            ],[
                'price.required' => 'The bid price is required!',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        $bid = Bid::create($request->only(['user_id', 'price']));

        // Dispatch the event
        event(new BidSaved($bid));

        $user = User::find($request->input('user_id'));

        $return_data = [
            'full_name' => $user->full_name,
            'price' => number_format($request->input('price'),2,'.',''),
        ];

        return response()->json([
            'message' => 'Success',
            'data' => $return_data
        ], 201);
    }

    public function show()
    {
        $bids = Bid::all();
        return response()->json($bids);
    }
}

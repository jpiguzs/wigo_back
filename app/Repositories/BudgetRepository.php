<?php

namespace App\Repositories;


use App\Models\Budget;
use App\Models\Box;
use App\Models\Point;
use App\Models\Origin;
use Facade\FlareClient\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use stdClass;

class BudgetRepository
{

    public function __construct()
    {        

    }
    public function index(){
        return response()->json(Budget::all(),200);
    }
    public function list_user_budget(){
        $budget = Budget::where('user_id', Auth::user()->id)->get();
        return response()->json($budget,200);
    }
    
    public function register($request){

        $data=[
            'total' => $request->total,
            'user_id' => Auth::user()->id,
            'status' => 1,
            'express' => $request->express,
            'payment_methods' => $request->patment_methods
        ];
       $budget = Budget::create($data);
        foreach ($request->boxes as $key => $box) {
            echo $box['height'];
            $box_data =[
                'height' => intval($box['height']),
                'width' => intval($box['width']),
                'length' => intval($box['length']),
                'budget_id' => $budget->id,
            ];
            Box::create($box_data);


        }
        foreach ($request->delivery_points as $key => $point) {
            $origin = Origin::where('code', $point['origin_code'])->first();
            $end = Origin::where('code' , $point['delivery_code'])->first();

            $point_data =[
                'origin_id' => $origin->id,
                'end_id' => $end->id,
                'budget_id' => $budget->id,
            ];
            Point::create($point_data);

            # code...
        }
        return response()->json($budget,200);
    }
     public function getBudgetById($id){
        $budget = Budget::where('id',$id)->first();

        return response()->json($budget, 200);

     }
}
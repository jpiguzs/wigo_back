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

class UserRepository
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
            'status' => 1
        ];
       $budget = Budget::create($data);
        foreach ($request->boxes as $box) {
            
            $box_data =[
                'height' => $box->height,
                'width' => $box->width,
                'length' => $box->length,
                'budget_id' => $budget->id,
            ];
            Box::create($box_data);


        }
        foreach ($request->delivery_points as $point) {
            $origin = Origin::where('code', $point->origin_code)->first();
            $end = Origin::where('code' , $point->delivery_code)->first();

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
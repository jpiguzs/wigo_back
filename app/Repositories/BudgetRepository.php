<?php

namespace App\Repositories;


use App\Models\Budget;
use App\Models\Box;
use App\Models\Stop;
use App\Models\Delivery;
use App\Models\Pickup;
use App\Models\Origin;
use Facade\FlareClient\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Repositories\EmailRepository;
use stdClass;

class BudgetRepository
{

    private $EmailRespository;
    public function __construct()
    {
        $this->EmailRespository = new EmailRepository();
    }
    public function index(){
        $budget = Budget::with(['User','Stops'=>function($stop){
            $stop->with(['Deliverys.Box', 'Pickups.Box','City','Previous_city']);
        }])->get();
        return response()->json($budget,200);
    }
    public function list_user_budget(){
        $budget = Budget::where('user_id', Auth::user()->id)->with(['Stops'=>function($stop){
            $stop->with(['Deliverys.Box', 'Pickups.Box','City','Previous_city']);
        }])->get();
        return response()->json($budget,200);
    }

    public function register($request){

        $data=[
            'total' => $request->total,
            'user_id' => Auth::user()->id,
            'status' => 1,
            //'express' => $request->express,
            'payment_methods' => $request->payment_methods
        ];
       $budget = Budget::create($data);

        foreach ($request->boxes as $key => $box) {

            $box_data =[
                'height' => intval($box['height']),
                'width' => intval($box['width']),
                'length' => intval($box['length']),
                'user_id' => Auth::user()->id,
                'front_id'=>$box['id'],
            ];
            Box::create($box_data);


        }


        foreach ($request->stops as $key => $stop) {
            $origin = Origin::where('code', $stop['city_code'])->first();
            $end = null;
            if($stop['previous_code']){
                $end = Origin::where('code' , $stop['previous_code'])->first();
            }

            $stop_data =[
                'city_id' => $origin->id,
                'previous_city_id' => $end ? $end->id : null,
                'budget_id' => $budget->id,
                'total_stop' => $stop['stop_val'],
                'total_pick' =>$stop['total_pick'],
                'total_delivery' =>$stop['total_delivery'],
                'total' => $stop['total'],
                'front_id'=> $stop['id']
            ];
            $new_stop = Stop::create($stop_data);

            foreach ($stop['pick'] as $key2 => $pick){
                $box_found = Box::where('front_id', $pick['box_id'])->first();
                $pick_data =[
                    'box_id' => $box_found->id,
                    'stop_id'=> $new_stop->id,
                    'quantity' =>$pick['quantity']
                ];
                Pickup::create($pick_data);

            }
            foreach ($stop['delivery'] as $key2 => $delivery){
                $box_found = Box::where('front_id', $delivery['box_id'])->first();
                $delivery_data =[
                    'box_id' => $box_found->id,
                    'stop_id'=> $new_stop->id,
                    'quantity' =>$delivery['val']
                ];
                Delivery::create($delivery_data);

            }

            # code...
        }
        $this->EmailRespository->SendEmail($budget->id);
        return response()->json($budget,200);
    }
     public function getBudgetById($id){
        $budget = Budget::where('id',$id)->with(['User','Stops'=>function($stop){
            $stop->with(['Deliverys.Box', 'Pickups.Box','City','Previous_city']);
        }])->first();

        return response()->json($budget, 200);

     }
}

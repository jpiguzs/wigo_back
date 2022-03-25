<?php
namespace App\Repositories;
use App\Models\Budget;
use Mail;

class EmailRepository {
    public function __construct()
    {

    }
    public function SendEmail($id){
        $budget = Budget::with(['User','Stops'=>function($stop){
            $stop->with(['Deliverys.Box', 'Pickups.Box','City','Previous_city']);
        }])->where('id', $id)->first();
        $subject = "Numero de orden".$id;
        $data = [
            'id'=>$budget->id,
            'client' => $budget->User->name,
            'email' => $budget->User->email,
            'payment' => $budget->payment->name,
            'total' => $budget->total,
            'stops' => $budget->Stops,
            'ref' =>'https://dev.wigo.services/budget/'.$id,


    ];

        $for = "wigoservices3@gmail.com";
        Mail::send('home',$data, function($msj) use($subject,$for){
            $msj->from("wigoservices3@gmail.com","wigo");
            $msj->subject($subject);
            $msj->to($for);
        });
        return ;
        //return response()->json($budget,200);
    }

}

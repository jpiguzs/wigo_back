<?php
namespace App\Repositories;
use App\Models\Budget;
use Twilio\Rest\Client;
use Mail;

class EmailRepository {
    public function __construct()
    {

    }
    public function SendEmail($id){
        $budget = Budget::with(['User','Stops'=>function($stop){
            $stop->with(['Deliverys.Box', 'Pickups.Box','City','Previous_city']);
        }])->where('id', $id)->first();
        $subject = "Numero de orden ".$id;
        $data = [
            'id'=>$budget->id,
            'client' => $budget->User->name,
            'email' => $budget->User->email,
            'tlf' => $budget->User->tlf,
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
        try {
            //code...
            $message = "Orden numero:".$data['id']."\nCliente:".$data['client']."\nCorreo del cliente:".$data["email"]."\nTotal a cobrar:".$data['total']."\nMetodo de pago:".$data['payment']."\nDestalles:".$data['ref']."";
            $twilio = new Client(config('services.twilio.sid'), config('services.twilio.token'));
            return $twilio->messages->create('whatsapp:+584121811478', [
            "from" => 'whatsapp:+584248660442' ,
            "body" =>$message
        ]);
        } catch (\Throwable $th) {
            throw $th;
        }

        return ;
        //return response()->json($budget,200);
    }

}

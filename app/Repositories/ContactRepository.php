<?php

namespace App\Repositories;

use  App\Models\Contact;
use Facade\FlareClient\Http\Response;
use Illuminate\Support\Facades\Auth;

class ContactRepository {
    public function __construct()
    {

    }
    public function register($contact){
        $newContac = null;
        if(!is_string($contact['id'])){
            $newContac = Contact::find($contact['id']);
        } else{
            $contactModel = Contact::where('front_id',$contact['id']);

            if(!$contactModel->exists())
            {
                $contac_data = [
                    'user_id' => Auth::user()->id,
                    'name' => $contact['name'],
                    'tlf' => $contact['tlf'],
                    'email' => $contact['email'],
                    'ci'=> $contact['ci'],
                    'front_id' => $contact['front_id']
                ];
                $newContac = Contact::create($contac_data);
            } else{
                $newContac = $contactModel->first();
            }
           
        }


        return $newContac;
    }

    public function index(){
        $contacs = Contact::where('user_id', Auth::user()->id)->get();
        return response()->json($contacs,200);
    }
}

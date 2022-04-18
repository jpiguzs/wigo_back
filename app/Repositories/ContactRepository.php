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
        if($contact['id']!=null){
            $newContac = Contact::find($contact['id']);
        } else{
            $contac_data = [
            'user_id' => Auth::user()->id,
            'name' => $contact['name'],
            'tlf' => $contact['tlf'],
            'email' => $contact['email'],
            'ci'=> $contact['ci'],
        ];
        $newContac = Contact::create($contac_data);
        }


        return $newContac;
    }

    public function index(){
        $contacs = Contact::where('user_id', Auth::user()->id)->get();
        return response()->json($contacs,200);
    }
}

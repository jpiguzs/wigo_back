<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ContactRepository;

class ContactController extends Controller
{
    //
    private $ContactRepository;
    public function __construct()
    {

        $this->ContactRepository = new ContactRepository();

    }
    public function register(request $request){
        return $this->ContactRepository->register($request);
     }
     public function index(){
        return $this->ContactRepository->index();
     }
}

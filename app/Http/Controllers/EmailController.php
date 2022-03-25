<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\EmailRepository;
class EmailController extends Controller
{
    //
    private $EmailRespository;
    public function __construct()
    {
        $this->EmailRespository = new EmailRepository();
    }
    public function SendEmail($id){
        return $this->EmailRespository->SendEmail($id);
     }


}

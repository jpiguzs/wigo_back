<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\BudgetRepository;
class BudgetController extends Controller
{
    //
    private $BudgetRespository;
    public function __construct()
    {
        $this->BudgetRespository = new BudgetRepository();
    }

    public function register(request $request){
       return $this->BudgetRespository->register($request);
    }
    public function index(){
       return $this->BudgetRespository->index();
    }
    public function list_user_budget(){
       return $this->BudgetRespository->list_user_budget();
    }
    public function getBudgetById($id){
        return $this->BudgetRespository->getBudgetById($id);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Password;

use stdClass;

use App\Repositories\UserRepository;
use Facade\FlareClient\Http\Response;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'recovery_password', 'verify_pin', 'change_password']]);
    }


    public function login(Request $request)

    {
        $user = User::where('email', $request->email)->exists();

        if ($user) {
            $credentials = request(['email', 'password']);

            if (!$token = auth()->attempt($credentials)) {
                return response([
                    'mensaje' => 'Correo o Contraseña Invalida'
                ], 400);
            }
            $tokenn = $this->respondWithToken($token);
        if (request(['token'])) {
            $UserRepository = new UserRepository();
            $UserRepository->save_token(request());
        }
            return response([
                'token' =>  $token,
                'user' => $this->user()
            ], 200)->header('Authorization', $token);
        }else{
            return response([
                'mensaje' => 'este usuario no posee cuenta, debe registrase'
            ], 400);
        }
    }


    public function me()
    {
        return response()->json($this->user());
    }

    public function check()
    {
        if (Auth::check()) {
            return response()->json("Verify");
        } else {
            return response()->json("Unauthorized", 401);
        }
    }

    public function logout()
    {
        auth()->logout();

        return response()->json([
            'mensaje' => 'Successfully logged out',
            'status' => 'success'
        ], 200);
    }

    public function refresh()
    {
        $token = $this->respondWithToken(auth()->refresh());

        return response()->json($token, 200);
    }

    protected function respondWithToken($token)
    {

        auth()->factory()->getTTL() * 7200;

        return $token;
    }

    public function register(Request $request)
    {
        $userRepository = new UserRepository();
        

        try {
            DB::beginTransaction();
            $resp = $userRepository->register($request);
            if ($resp != true) {
                return $resp;
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json($th, 400);
        }

        return $this->login($request);
    }

    public function user()
    {
        $user = User::where('id', Auth::id())->get()->last();
        //$user->getPermissionsViaRoles();
        return $user;
    }


    public function is_valid_email($str)
    {
        return (false !== strpos($str, "@") && false !== strpos($str, "."));
    }

    public function recovery_password(Request $request)
    {
        $UserRepository = new UserRepository();
        return $UserRepository->recovery_password($request);
    }

    public function verify_pin(Request $request)
    {
        $UserRepository = new UserRepository();
        return $UserRepository->verify_pin($request);
    }

    public function change_password(Request $request)
    {
        $UserRepository = new UserRepository();
        return $UserRepository->change_password_pin($request);
    }

    public function newUser(Request $request)
    {

        return $request;
        $userRepository = new UserRepository();
        $resp = $userRepository->validate($request);
        if ($resp !== true) {
            return response()->json($resp, 400);
        }

        try {
            DB::beginTransaction();
            $resp = $userRepository->register($request);
            if ($resp != true) {
                return $resp;
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json($th, 400);
        }
    }
}
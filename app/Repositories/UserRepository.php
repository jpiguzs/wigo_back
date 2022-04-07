<?php

namespace App\Repositories;


use App\Models\User;
use Facade\FlareClient\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use stdClass;

class UserRepository
{

    public function __construct()
    {

    }

    public function register($request)
    {
        $image = new stdClass;


        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'carnet_id' => $request->carnet_id,
            'tlf' => $request->tlf,
            'last_name' => $request->last_name,
            'type' => 2,
        ];

        $user = User::create($data);

        return true;
    }

    public function validate($request)
    {
        $user = User::where('email', $request->email)->get()->last();

        if (isset($user)) {
            return "El correo ya esta registrado";
        }

        return true;
    }

    public function verify_sms($request)
    {
        $user = User::where('code_sms', $request->pin)
            ->where('email', Auth::user()->email)->get()->last();

        if (isset($user)) {
            $user->verify_sms = true;
            $user->update();
            return response()->json("Verificado Correctamente", 200);
        }

        return response()->json("Codigo Erroneo", 400);
    }

    public function verify_email($request)
    {
        $user = User::where('code_email', $request->pin)
            ->where('email', Auth::user()->email)->get()->last();

        if (isset($user)) {
            $user->verify_email = true;
            $user->update();
            return response()->json("Verificado Correctamente", 200);
        }

        return response()->json("Codigo Erroneo", 400);
    }

    public function resend_sms()
    {
        $user = User::find(Auth::user()->id);
        $user->code_sms = rand(300000, 999999);
        $user->update();

        $message = "Bienvenido a Aboonda, Para validar tu numero de telefono ingresa este codigo: " . $user->code_sms . " en nuestra plataforma.";
        // SendSMS::dispatch($user->phone, $message);

        return response()->json("Reenviado correctamente", 200);
    }

    public function resend_email()
    {
        $user = User::find(Auth::user()->id);
        $user->code_email = rand(300000, 999999);
        $user->update();

        // VerifyEmail::dispatch($user);

        return response()->json("Reenviado correctamente, Revise su bandeja de entrada", 200);
    }

    public function profile($id)
    {
        $user = User::where('id', $id)
            ->with(['Comments.User', 'Services', 'City', 'CommentsRequest.User','Publicity'])
            ->get()->last();

        if (Auth::check()) {
            if (Auth::user()->id == $id) {
                $user->makeVisible('phone');
            }
        }


        if (isset($user))
            return response()->json($user, 200);
        else
            return response()->json("No exite este usuario", 400);
    }

    public function update($request)
    {
        $user = User::find(Auth::user()->id);
        $photo = false;
        if ($request->file('avatar')) {
            // $fileRepository = new FileRepository();
            // $image = $fileRepository->save($request);

            // if ($image->error) {
            //     return response()->json($image->validator, 400);
            // }

            $photo = true;
        }

        if ($request->name) {
            $user->name = $request->name;
        }

        if ($request->phone) {
            $user->phone = $request->tlf;
        }


        if ($user->avatar_path == '/images/defaultUser.jpg' && $photo) {
            $user->rating = $user->rating + 1;
            $user->rating_service = $user->rating_service + 1;
        }

        if ($request->city_id)
            $user->city_id = $request->city_id;

        // if ($photo) {
        //     $user->avatar_url = $image->url_img;
        //     $user->avatar_path = $image->path_img;
        // }

        if ($request->birthdate != "null" && $request->birthdate != null)
            $user->birthdate = $request->birthdate;

        if ($request->profesion)
            $user->profesion = $request->profesion;

        // $skills = explode(",", $request->skills);

        $user->update();

        return response()->json($user, 200);
    }


    public function change_password($request)
    {
        $user = User::find(Auth::user()->id);
        $user->password = Hash::make($request->password);
        $user->update();

        return response()->json("Contraseña Cambiada Correctamente", 200);
    }

    public function recovery_password($request)
    {
        $user = User::where('email', $request->email)->get()->last();

        if (!isset($user)) {
            return response()->json("No estas registrado en nuestra plataforma", 400);
        }

        $user->code_email = rand(300000, 999999);
        $user->update();

        config([
            'mail.driver' => 'smtp',
            'mail.host' => 'smtp.gmail.com',
            'mail.port' => '465',
            'mail.encryption' => 'ssl',
            'mail.username' => 'aboonda.venezuela@gmail.com',
            'mail.password' => "wrqtjhfglwphifqb",
            'mail.name' => "Aboonda",
        ]);

        // \Mail::to($user->email)->send(new RecoveryPasswordMail($user));

        return response()->json("Se ha enviado un correo a tu dirreccion de email", 200);
    }

    public function UpdateConfig($request)
    {
        // if (isset(Auth::user()->config)) {
        //     $config = Config::find(Auth::user()->config->id);
        //     $config->notify_email = $request->notify_email;
        //     $config->notify_sms = $request->notify_sms;
        //     $config->save();
        // }

        return response()->json("Actualizado Correctamente", 200);
    }

    public function verify_pin($request)
    {
        $user = User::where('email', $request->email)
            ->where('code_email', $request->pin)->get()->last();

        if (isset($user)) {
            return response()->json("Verificado Correctamente", 200);
        }

        return response()->json("Codigo Erroneo", 400);
    }

    public function change_password_pin($request)
    {
        $user = User::where('email', $request->email)
            ->where('code_email', $request->pin)->get()->last();

        $user->password = Hash::make($request->password);
        $user->update();

        return response()->json("Contraseña cambiada exitosamente", 200);
    }

    public function save_token($request)
    {

        $user = User::where('token', $request->token)
            ->update(["token" => null]);

        $user = User::find(Auth::user()->id);
        $user->token = $request->token;
        $user->save();

        return response()->json(true, 200);
    }

    public function reportar($request)
    {
        $idUser=$request->idUser;
        $user=User::where('id','=',$idUser)->get();

        if($user[0]->rating == 0)
        {
            $newRating=0;
        }
        else{
            $newRating =$user[0]->rating - 0.1;
        }


        $user=User::where('id','=',$idUser)->update(['rating'=> floatval($newRating)]);

        return response()->json($user,200);
    }

    public function registerUser($request)
    {
        $data = [
            'name' => $request->name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'carnet_id' => $request->carnet_id,
            'tlf' => $request->tlf,
        ];

        $user = User::create($data);

        return response()->json($user,200);
    }

    public function getAllUser(){

        return response()->json(User::all(), 200);

    }

    public function updateUser($request){

        $user = User::find($request->id);
        $data = [
            'name' => $request->name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            //'rol_id' => $request->role,
            'client_id' => $request->client_id,
            'department_id' => $request->departament,
            'password' => $request->password==null ? $user->password : Hash::make($request->password),
        ];

        $user->update($data);


        //$array = explode(",", $request->role );
        $user->roles()->detach();
        //$array = explode(",", $request->role );
        foreach ($request->role  as $role){
            $user->assignRole($role);
        }
        return response()->json($user,200);
    }

    public function destroy($id){

        return response()->json(User::find($id)->delete(),200);
    }
    public function set_signature($request){
        $user = User::find(Auth::user()->id);
        $photo = false;
        if ($request->file('file')) {
            $fileRepository = new FileRepository();
            $image = $fileRepository->save($request);

            if ($image->error) {
                return response()->json($image->validator, 400);
            }
        }
        $user->url_image = $image->url_img;
        $user->path_image = $image->path_img;
        $user->save();
        return response()->json($user,200);

}
}

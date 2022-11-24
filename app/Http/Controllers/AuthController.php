<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Humano;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class AuthController extends Controller {

    // este método sirve para guardar las características de los humanos que no van a ir en la tabla users.
    // El id actúa como clave primaria y foránea, luego tengo que recoger el id generado para el registro en la tabla users
    // y utilizar ese mismo id para poder identificar cada humano con sus campos recogidos en la tabla user.
    // este método también servirá para insertar el dios protector. la declaración entera de la función sería:
    // private function registrarCaractiristicasHumano($id, $destino, $idDios)

    private function registrarCaracteristicasHumano($id, $destino) {
        $h = new Humano;
        $cod = 0;

        $h->idHumano = $id;
        $h->destino = $destino;
        //$h->idDios = idDios;    esto en caso de que ya esté la simulación hecha

        try {
            $h->save();
        }
        catch (\Exception $e) {
            $cod = -1;
        }

        return $cod;
    }

    public function login(Request $request) {   // en vez de hacerlo con el correo lo hago con el nombre.
                                                // no es determinante, va un poco a gusto de cad uno.
                                                // con un or en el if podrían valer los dos.

        if(Auth::attempt(['name' => $request->name, 'password' => $request->password])){
            $auth = Auth::user();

            $auth->activo = true;
            switch ($auth->rol) { // dependiendo del rol le entrego un token u otro
                case 'user':
                    $token['nombre'] = 'tokenUser';
                    $token['hab'] = ['user'];
                    break;

                case 'dios':
                    $token['nombre'] = 'tokenDios';
                    $token['hab'] = ['dios'];
                    break;

                case 'hades':
                    $token['nombre'] = 'tokenHades';
                    $token['hab'] = ['hades'];
                    break;
            }

            $success['token'] =  $auth->createToken($token['nombre'], $token['hab'])->plainTextToken;
            $success['name'] =  $auth->name;

            return response()->json(["success"=>true,"data"=>$success, "message" => "Logged in!"],200);
        }
        else{
            return response()->json(["success"=>false, "message" => "Unauthorised"],202);
        }
    }


    public function register(Request $request) {
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);

        try {
            $user = User::create($input); // crea un registro en la tabla users
            $this->registrarCaracteristicasHumano($user->id, 0); // crea un registro en la tabla humanos
            $success['name'] =  $user->name;
            $resp = [
                'msg' => [
                    "success"=>true,
                    "data"=>$success,
                    "message" => "User successfully registered!"
                ],

                'cod' => 202
            ];
        }
        catch (\Exception $e) { // recojo la excepción en el caso de que el usuario que se quiera registrar ya exista
            $resp = [
                'msg' => [
                    "success"=>false,
                    "message" => "User already exists!"
                ],

                'cod' => 202
            ];
        }

        return response()->json($resp['msg'], $resp['cod']);
    }

    public function logout(Request $request) {
        if(Auth::attempt(['name' => $request->name, 'password' => $request->password])){
            $cantidad = Auth::user()->tokens()->delete();
            return response()->json(["success"=>$cantidad, "message" => "Tokens Revoked"],200);
        }
        else {
            return response()->json(["success"=>false, "message" => "Unauthorised"],202);
        }

    }
}

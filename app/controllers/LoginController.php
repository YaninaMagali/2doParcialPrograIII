<?php
require_once './models/Usuario.php';
require_once './utils/AutenticadorJWT.php';

class LoginController{


    public static function CrearToken($username, $profile){
        $usuario = new Usuario();
        $usuario->id = 1;
        $usuario->username = $username;
        $usuario->profile = $profile;
        return  AutenticadorJWT::CrearToken($usuario);

    }

    public static function ValidarPerfilToken($request, $response){

        $bearer =  $request->getHeaderLine('Authorization');
        if($bearer){
            $aux = explode(" ", $bearer);
            $token = $aux[1];
            $payloadJwt = AutenticadorJWT::ObtenerPayLoad($token);

            if($payloadJwt->data->profile == 'ADMIN' || $payloadJwt->data->profile =='CLIENTE' || $payloadJwt->data->profile =='EMPLEADO'){
                $payload = json_encode(array('OK' => $payloadJwt->data->profile));
                    $response->getBody()->write($payload);
            }
            else{
                $payload = json_encode(array('Error'));
                    $response->getBody()->write($payload);
            }

            return $response->withHeader('Content-Type', 'application/json');
            }
}

    public static function ValidarCredenciales($request, $response){

        $esValida = false;
        $parametros = $request->getParsedBody();
        $username = $parametros['username'];
        $password = $parametros['password'];
        $usuario = Usuario::ConsultarUsuario($username);
    
        if($usuario 
        && password_verify($password, $usuario->password)
        && $usuario->username == $username)
        {
            $t = LoginController::CrearToken($usuario->username, $usuario->profile);
            $payload = json_encode(array("Bearer" =>$t));
        }
        else{
            $payload = json_encode(array("Error" =>"Invalido"));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }


}
?>
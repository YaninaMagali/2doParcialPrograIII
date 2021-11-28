<?php
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as Response;
require_once './utils/AutenticadorJWT.php';
require_once './controllers/LoginController.php';

class ValidadorMdw{

    public function ValidarEsAdmin(Request $request, RequestHandler $handler){
       
        $response = new Response();
        $bearer =  $request->getHeaderLine('Authorization');
        if($bearer){
            $aux = explode(" ", $bearer);
            $token = $aux[1];
            $payloadJwt = AutenticadorJWT::ObtenerPayLoad($token);

            if($payloadJwt->data->profile == 'ADMIN')
            {
                $response = $handler->handle($request);
            }   
            else{
                $data = array('error_code' => '200', 'message' => 'No es admin');
                $payload = json_encode($data);
                $response->getBody()->write($payload);
            }
        }

        return $response;
    }

    public function ValidarExisteToken(Request $request, RequestHandler $handler){

        $token =  $request->getHeaderLine('Authorization');
        $response = new Response();
        if($token != null){
            $response = $handler->handle($request);
        }
        else{
            $data = array('error_code' => '404', 'message' => 'No esta autenticado');
            $payload = json_encode($data);
            $response->getBody()->write($payload);
        }

        return $response;
    }

public function ValidarEsUsuarioRegistrado(Request $request, RequestHandler $handler){
    
    $response = new Response();
    $bearer =  $request->getHeaderLine('Authorization');
    if($bearer){
        $aux = explode(" ", $bearer);
        $token = $aux[1];
        $payloadJwt = AutenticadorJWT::ObtenerPayLoad($token);

        if($payloadJwt->data->profile == 'ADMIN' || $payloadJwt->data->profile =='CLIENTE' || $payloadJwt->data->profile =='EMPLEADO'){
            $response = $handler->handle($request);
        }
        else{
            $data = array('error_code' => '404', 'message' => 'No esta habilitado');
            $payload = json_encode($data);
            $response->getBody()->write($payload);
        }
    }
    else{
        $data = array('error_code' => '404', 'message' => 'No esta autenticado');
            $payload = json_encode($data);
            $response->getBody()->write($payload);
    }
        return $response;

}


}
?>
<?php
require_once './models/Cabania.php';

class CabaniaController{

    public function CrearCabania($request, $response, $args){
    
        $parametros = $request->getParsedBody();
        $path = './FotosCabañas';
        $fileName = "/" .$parametros['nombre'] . "_" . $parametros['estilo'];

        $cabania = Cabania::CrearCabania($parametros['precio'], $parametros['nombre'], $path . $fileName, $parametros['estilo'], $parametros['cantidad_habitaciones'], $parametros['cantidad_personas']);
        
        //var_dump($cabania);
        try{
            $cabania->InsertarCabania();
            Archivador::GuardarArchivo('foto', $path, $fileName);
            $payload = json_encode(array("mensaje" => "cabania $cabania->nombre creada con exito"));
        }
        catch(Exception $e){
            $payload = json_encode(array("Error" => $e->message));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ListarCabanias($request, $response, $args){
        
        try{
            $cabanias = Cabania::Consultar();
            $payload = json_encode(array("Cabanias" => $cabanias));
        }
        catch(Exception $e){
            $payload = json_encode(array("Error" => $e->message));
        }
        
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ListarCabaniasPorCapacidad($request, $response, $args){
        
        $cantidad_personas = $args['cantidad_personas'];

        try{
            $cabanias = Cabania::ConsultarPorCapacidad($cantidad_personas);
            $payload = json_encode(array("Cabanias" => $cabanias));
        }
        catch(Exception $e){
            $payload = json_encode(array("Error" => $e->message));
        }
        
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ListarCabaniasPorId($request, $response, $args){
        
        $id = $args['id'];
        echo $id;
        try{
            $cabanias = Cabania::ConsultarPorId($id);
            $payload = json_encode(array("Cabanias" => $cabanias));
        }
        catch(Exception $e){
            $payload = json_encode(array("Error" => $e->message));
        }
        
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function EliminarCabania($request, $response, $args){

        $id = $args['id'];
        try{
            //$cabanias = 
            Cabania::EliminarLogicamenteCabaniaPorId($id);
            $payload = json_encode(array("Message" => "Cabaña id $id eliminada "));
        }
        catch(Exception $e){
            $payload = json_encode(array("Error" => $e->message));
        }
        
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public  function ModificarCabania($request, $response, $args){

        $id = $args['id'];
        $parametros = $request->getParsedBody();
        $path = './FotosCabañas';
        $destino = './FotosBackUp';
        
        try{
            $c = Cabania::ConsultarPorId($id);
            $fileName = "/" .$c->nombre . "_" . $c->estilo;
            if($c){
                if(Archivador::ValidarSiExisteArchivo($c->foto)){
                    Archivador::CambiarDeDirectorio($c->foto, $destino.$fileName);
                }
                Cabania::ModificarCabania($parametros['precio'], $parametros['nombre'], $parametros['foto'], $parametros['estilo'], $parametros['cantidad_habitaciones'], $parametros['cantidad_personas'], $parametros['estado'], $id);
            }
            
                $payload = json_encode(array("Message" => "Cabaña id $id modificada "));
            
        }
        catch(Exception $e){
            $payload = json_encode(array("Error" => $e->message));
        }
        
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}


?>
<?php
require_once './models/Alquiler.php';
require_once './utils/Archivador.php';
require('./fpdf/fpdf.php');

class AlquilerController{

    public function CrearAlquiler($request, $response, $args){

        $path = './FotosAlquiler';
        
        $parametros = $request->getParsedBody();
        $fileName = $parametros['nombre_cabania'] . "_". $parametros['username'] . "_". $parametros['fecha'];
        $alquiler = Alquiler::CrearAlquiler($parametros['fecha'], $parametros['cantidad_dias'], $parametros['nombre_cabania'], $parametros['estilo'], $path . $fileName, $parametros['username']);

        try{
            $alquiler->InsertarAlquiler();
            Archivador::GuardarArchivo('foto', $path, $fileName);
            $payload = json_encode(array("mensaje" => "alquiler de $alquiler->nombre_cabania creado con exito"));
        }
        catch(Exception $e){
            $payload = json_encode(array("Error" => $e->message));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ListarAlquilerPorFechaEstilo($request, $response, $args){
        
        $f1 = $args['f1'];
        $f2 = $args['f2'];
        $estilo = $args['estilo'];
        
        try{
            $alquileres = Alquiler::ConsultarPorFechas($f1, $f2,$estilo);
            //var_dump($alquileres);
            if($alquileres){
                $payload = json_encode(array("Alquileres" => $alquileres));
            }
            else{
                $payload = json_encode(array("message" => "Ni hay alquileres para la fecha"));
            }
            
        }
        catch(Exception $e){
            $payload = json_encode(array("Error" => $e->message));
        }
        
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ListarUsuariosAlquilerPorEstilo($request, $response, $args){

        $estilo = $args['estilo'];
        try{
            $usuarios = Alquiler::ConsultarUsuariosPorEstilo($estilo);
            if($usuarios){
                $payload = json_encode(array("Usuarios" => $usuarios));
            }
            else{
                $payload = json_encode(array("message" => "No hay alquileres para ese estilo de cabaña"));
            }
            
        }
        catch(Exception $e){
            $payload = json_encode(array("Error" => $e->message));
        }
        
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ListarAlquileresPdf($request, $response, $args){

        $f1 = $args['f1'];
        $f2 = $args['f2'];
        try{
            $alquileres = Alquiler::ConsultarSoloPorFechas($f1, $f2);
            $pdf = new FPDF();
            if($alquileres != null && $pdf != null){
                
                $pdf->AddPage();
                $pdf->SetFont('Arial','B',16);
                foreach($alquileres as $a){
     
                    $dato = $a->fecha . "|" . $a->cantidad_dias . "|" . $a->nombre_cabania . "|" . $a->estilo  . "|" . $a->username;
                    $pdf->Cell(40,10, $dato);
                    $pdf->Ln();
                    
                }
                $pdf->Output('F', './pdf/alquileres.pdf', 'I');
                $payload = json_encode(array("Messsage" => "DEscargado"));
                
            }
            
        }
        catch(Exception $e){
            $payload = json_encode(array("Error" => "error"));
        } 
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/pdf');
    }


}


?>
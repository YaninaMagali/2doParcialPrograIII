<?php

class Alquiler{

    public $id;
    public $fecha;
    public $cantidad_dias;
    public $nombre_cabania;
    public $estilo;
    public $foto;
    public $username;


    public static function CrearAlquiler($fecha, $cantidad_dias, $nombre_cabania, $estilo, $foto, $username){

        $a = new Alquiler();
        $a->fecha = $fecha;
        $a->cantidad_dias = $cantidad_dias;
        $a->nombre_cabania = $nombre_cabania;
        $a->foto = $foto;
        $a->estilo = $estilo;
        $a->username = $username;
    
        return $a;
    }

    public function InsertarAlquiler(){

        try{
            $dao = new DAO();
            $query = $dao->prepararConsulta("INSERT INTO `alquiler`(`fecha`, `cantidad_dias`, `nombre_cabania`, `imagen`, `estilo` , `username`) 
            VALUES (:fecha, :cantidad_dias, :nombre_cabania, :imagen, :estilo, :username)");
            
            $query->bindValue(':fecha', $this->fecha, PDO::PARAM_STR);
            $query->bindValue(':cantidad_dias', $this->cantidad_dias, PDO::PARAM_INT);      
            $query->bindValue(':nombre_cabania', $this->nombre_cabania, PDO::PARAM_STR);
            $query->bindValue(':estilo', $this->estilo, PDO::PARAM_STR);
            $query->bindValue(':imagen', $this->foto, PDO::PARAM_STR);
            $query->bindValue(':username', $this->username, PDO::PARAM_STR); 
            $query->execute();
    
            return $dao->obtenerUltimoId();
        }
        catch(Exception $e){
            throw $e;
        }
    }

    public static function ConsultarPorFechas($f1, $f2, $estilo){

        try{
            $dao = new DAO();
            $query = $dao->prepararConsulta("SELECT * FROM alquiler WHERE fecha BETWEEN :f1 
            AND :f2 AND estilo = :estilo;");
            $query->bindValue(':f1', $f1, PDO::PARAM_STR);
            $query->bindValue(':f2', $f2, PDO::PARAM_STR);
            $query->bindValue(':estilo', $estilo, PDO::PARAM_STR);
            $query->execute();
    
            return $query->fetchAll(PDO::FETCH_CLASS, 'Cabania');
        }
        catch(Exception $e){
            throw $e;
        }
    }

    public static function ConsultarUsuariosPorEstilo($estilo){

        try{
            $dao = new DAO();
            $query = $dao->prepararConsulta("SELECT username FROM alquiler WHERE  estilo = :estilo;");
            $query->bindValue(':estilo', $estilo, PDO::PARAM_STR);
            $query->execute();
    
            return $query->fetchAll(PDO::FETCH_ASSOC); 
        }
        catch(Exception $e){
            throw $e;
        }
    }

    public static function ConsultarSoloPorFechas($f1, $f2){

        try{
            $dao = new DAO();
            $query = $dao->prepararConsulta("SELECT * FROM alquiler WHERE fecha BETWEEN :f1 
            AND :f2 ;");
            $query->bindValue(':f1', $f1, PDO::PARAM_STR);
            $query->bindValue(':f2', $f2, PDO::PARAM_STR);
            $query->execute();
    
            return $query->fetchAll(PDO::FETCH_CLASS, 'Cabania');
        }
        catch(Exception $e){
            throw $e;
        }
    }
}


?>
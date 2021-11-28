<?php

class Cabania{

    public $precio;
    public $nombre;
    public $foto;
    public $estilo;//(‘Suizo”, ‘Canadience”,”Patagónica”),
    public $cantidad_habitaciones;
    public $cantidad_personas;


    public function InsertarCabania(){

        try{
            $dao = new DAO();
            $query = $dao->prepararConsulta("INSERT INTO `cabania`(`nombre`, `foto`, `estilo`, `cantidad_habitaciones`, `cantidad_personas`, `precio`) 
            VALUES (:nombre, :foto, :estilo, :cantidad_habitaciones, :cantidad_personas, :precio)");
            
            $query->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
            $query->bindValue(':foto', $this->foto, PDO::PARAM_STR);
            $query->bindValue(':estilo', $this->estilo, PDO::PARAM_STR);
            $query->bindValue(':cantidad_habitaciones', $this->cantidad_habitaciones, PDO::PARAM_INT);      
            $query->bindValue(':cantidad_personas', $this->cantidad_personas, PDO::PARAM_INT);
            $query->bindValue(':precio', $this->precio, PDO::PARAM_INT);
            $query->execute();

            return $dao->obtenerUltimoId();
        }
        catch(Exception $e){
            throw $e;
        }
    }

    public static function CrearCabania($precio, $nombre, $foto, $estilo, $cantidad_habitaciones, $cantidad_personas){

        $c = new Cabania();
        $c->precio = $precio;
        $c->nombre = $nombre;
        $c->foto = $foto;
        $c->estilo = $estilo;
        $c->cantidad_habitaciones = $cantidad_habitaciones;
        $c->cantidad_personas = $cantidad_personas;

        return $c;
    }

    public static function Consultar(){

        try{
            $dao = new DAO();
            $query = $dao->prepararConsulta("SELECT * FROM cabania;");
            $query->execute();

            return $query->fetchAll(PDO::FETCH_CLASS, 'Cabania');
        }
        catch(Exception $e){
            throw $e;
        }
    }

    public static function ConsultarPorCapacidad($cantidad_personas){

        try{
            $dao = new DAO();
            $query = $dao->prepararConsulta("SELECT * FROM cabania WHERE cantidad_personas >= :cantidad_personas;");
            $query->bindValue(':cantidad_personas', $cantidad_personas, PDO::PARAM_INT);
            $query->execute();

            return $query->fetchAll(PDO::FETCH_CLASS, 'Cabania');
        }
        catch(Exception $e){
            throw $e;
        }
    }

    public static function ConsultarPorId($id){

        try{
            $dao = new DAO();
            $query = $dao->prepararConsulta("SELECT * FROM cabania WHERE id = :id;");
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            $query->execute();

            return $query->fetchObject('Cabania');
        }
        catch(Exception $e){
            throw $e;
        }
    }

    public static function EliminarLogicamenteCabaniaPorId($id){

        try{
            $dao = new DAO();
            $query = $dao->prepararConsulta("UPDATE cabania SET estado = 'BAJA' WHERE id = :id;");
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            return $query->execute();

            //return $query->fetchAll(PDO::FETCH_CLASS, 'Cabania');
        }
        catch(Exception $e){
            throw $e;
        }
    }

    public static function EliminarFisicamenteCabaniaPorId($id){

        try{
            $dao = new DAO();
            $query = $dao->prepararConsulta("DELETE FROM cabania WHERE id = :id;");
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            return $query->execute();

            //return $query->fetchAll(PDO::FETCH_CLASS, 'Cabania');
        }
        catch(Exception $e){
            throw $e;
        }
    }

    public static function ModificarCabania($precio, $nombre, $foto, $estilo, $cantidad_habitaciones, $cantidad_personas, $estado, $id){

        try{
            $dao = new DAO();
            $query = $dao->prepararConsulta("UPDATE cabania SET 
            precio = :precio,
            nombre = :nombre,
            foto = :foto,
            estilo = :estilo,
            cantidad_habitaciones =  :cantidad_habitaciones,
            cantidad_personas = :cantidad_personas,
            estado = :estado 
            WHERE id = :id;");
            $query->bindValue(':precio', $precio, PDO::PARAM_INT);
            $query->bindValue(':nombre', $nombre, PDO::PARAM_STR);
            $query->bindValue(':foto', $foto, PDO::PARAM_STR);
            $query->bindValue(':estilo', $estilo, PDO::PARAM_STR);
            $query->bindValue(':cantidad_habitaciones', $cantidad_habitaciones, PDO::PARAM_INT);
            $query->bindValue(':cantidad_personas', $cantidad_personas, PDO::PARAM_INT);
            $query->bindValue(':estado', $estado, PDO::PARAM_STR);
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            return $query->execute();
            //return $query->fetchAll(PDO::FETCH_CLASS, 'Cabania');
        }
        catch(Exception $e){
            throw $e;
        }
    }
}


?>
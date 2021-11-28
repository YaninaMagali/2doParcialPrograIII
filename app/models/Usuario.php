<?php
require_once './db/DAO.php';

class Usuario{
    public $id;
    public $username;
    public $password;
    public $profile;

    public static function ConsultarUsuario($username){

        try{
            $dao = new DAO();
            $query = $dao->prepararConsulta("SELECT * FROM usuarios WHERE username = :username;");
            $query->bindValue(':username', $username, PDO::PARAM_STR);
            $query->execute();
    
            return $query->fetchObject('Usuario');
        }
        catch(Exception $e){
            throw $e;
        }
    }








}




?>
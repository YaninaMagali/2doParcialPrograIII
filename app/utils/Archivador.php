<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

class Archivador
{
    public static function GuardarArchivo($archivo, $dir_subida, $newName){

        $pudoGuardar = false;

        if (!file_exists($dir_subida)) 
        {
            mkdir($dir_subida, 0777, true);
            
        }

        if (move_uploaded_file($_FILES[$archivo]['tmp_name'], "$dir_subida/$newName")) 
        {
            $pudoGuardar = true;
            echo "Se subió con éxito.\n";
        } 
        else  
        {
            echo "Error!\n";
        }
        return $pudoGuardar;
    }

    public static function CambiarDeDirectorio($origen, $destino)
    {
        if(is_file($origen))
        {
            try
            {
                //echo "origen: ".$origen;
                //echo "destino: ".$destino;
                copy($origen, $destino);
                unlink($origen);
            }
            catch(Exception $e)
            {
                echo "Error en CambiarDeDirectorio<br>";
                var_dump($e);
            }
        }

    }


    public static function ValidarSiExisteArchivo($path){

        $existe = false;
        if (file_exists($path))
        {
            $existe = true;
        }

        return $existe;
    }

}
?>

<?php
require_once 'model.php';

class UsersModel extends Model{

    public function obtenerUsuario($nombre){
        $query = $this->db->prepare("SELECT * FROM `usuarios` WHERE nombre = ?");
        $query->execute([$nombre]);
        $res = $query->fetch(PDO::FETCH_OBJ);
        return $res;
    }

    public function registrar($nombre,$clave){
        $query = $this->db->prepare("INSERT INTO `usuarios` (nombre_usuario,contraseña) VALUES (?,?)");
        $query->execute([$nombre,$clave]);
        return $query;
    }

}
?>
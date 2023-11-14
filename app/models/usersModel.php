<?php
require_once 'model.php';

class UsersModel extends Model{

    public function obtenerUsuario($nombre){
        $query = $this->db->prepare("SELECT * FROM `usuarios` WHERE nombre = ?");
        $query->execute([$nombre]);
        $res = $query->fetch(PDO::FETCH_OBJ);
        return $res;
    }
}
?>
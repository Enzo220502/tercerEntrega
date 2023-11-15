<?php
require_once 'model.php';

class categoriaModel extends Model{

    public function obtenerCategorias(){
        $query = $this->db->prepare("SELECT * FROM categorias");
        $query->execute();
        $categorias = $query->fetchAll(PDO::FETCH_OBJ);
        return $categorias;
    }


    public function addCategoria($nombre,$desc){
        $query = $this->db->prepare("INSERT INTO categorias (nombre_categoria,descripcion_categoria) VALUES (?,?)");
        $query->execute([$nombre,$desc]);
        return $query;
    }

    public function obtenerPorId($id){
        $query = $this->db->prepare('SELECT * FROM categorias WHERE id=?');
        $query->execute([$id]);
        $cat = $query->fetch(PDO::FETCH_OBJ);
        return $cat;
    }

    public function borrarCategoria($id){
        $query = $this->db->prepare('DELETE FROM categorias WHERE categorias.id = ?');
        $query->execute([$id]);
        return;
    }

    public function subirCambios($id,$nombre,$desc){
        $query = $this->db->prepare('UPDATE categorias SET nombre_categoria = ?, descripcion_categoria = ? WHERE id= ?');
        $query->execute([$nombre,$desc,$id]);
        return $query;
    }

}

?>
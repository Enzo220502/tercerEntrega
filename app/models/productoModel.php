<?php
require_once 'model.php';

class productoModel extends Model{

    public function obtenerProductos() {
        $query = $this->db->prepare("SELECT * FROM productos INNER JOIN categorias ON productos.id_categoria = categorias.id");
        $query->execute();
        $productos = $query->fetchAll(PDO::FETCH_OBJ);
        return $productos;
    }

    public function obtenerCampos(){
        $query = $this->db->prepare("SHOW FIELDS FROM productos");
        $query->execute();
        $campos = $query->fetchAll(PDO::FETCH_OBJ);
        return $campos;
    }

    public function obtenerOrdenado($final){
        $query = $this->db->prepare("SELECT * FROM  productos $final");
        $query->execute();
        $productos = $query->fetchAll(PDO::FETCH_OBJ);
        return $productos;
    }
    
    public function obtenerPorId($id){
        $query = $this->db->prepare("SELECT * FROM `productos` WHERE ID = ?");
        $query->execute([$id]);
        $producto = $query->fetch(PDO::FETCH_OBJ);
        return $producto;
    }

    public function agregarProducto($nom,$desc,$precio,$marca,$cat,$imagen = null){
        $query = $this->db->prepare("INSERT INTO productos (nombre,descripcion,precio,marca,id_categoria,imagen) VALUES (?,?,?,?,?,?)");
        $query->execute([$nom,$desc,$precio,$marca,$cat,$imagen]);
        return $this->db->lastInsertId();
    }

    public function eliminarProducto($id){
        $query = $this->db->prepare('DELETE FROM `productos` WHERE id = ?');
        $query->execute([$id]);
        return $query;
    }

    public function actualizarProducto($id,$nombre,$descripcion,$precio,$marca,$cat, $imagen = null){
        $query = $this->db->prepare("UPDATE `productos` SET nombre=?,descripcion=?,precio=?,marca=?,id_categoria=?,imagen=? WHERE ID=?");
        $query->execute([$nombre,$descripcion,$precio,$marca,$cat,$imagen,$id]);
        return $query;
    }


}

?>
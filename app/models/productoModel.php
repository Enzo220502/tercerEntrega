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

    public function obtenerProductosOrdenado($final){
        $query = $this->db->prepare("SELECT * FROM  productos $final");
        $query->execute();
        $productos = $query->fetchAll(PDO::FETCH_OBJ);
        return $productos;
    }
    
    public function obtenerUnProductoPorId($id){
        $query = $this->db->prepare("SELECT * FROM `productos` INNER JOIN `categorias` ON productos.id_categoria = categorias.id WHERE productos.ID = ?");
        $query->execute([$id]);
        $producto = $query->fetch(PDO::FETCH_OBJ);
        return $producto;
    }

    public function obtenerProductosPorId($id){
        $query = $this->db->prepare("SELECT * FROM `productos` INNER JOIN `categorias` ON productos.id_categoria = categorias.id WHERE productos.id_categoria = ?");
        $query->execute([$id]);
        $registroDeProducto = $query->fetchAll(PDO::FETCH_OBJ);
        return $registroDeProducto;
    }

    public function agregarProducto($nom,$desc,$precio,$marca,$cat,$imagen = null){
        $rutaImg = null;
        if($imagen){
            $rutaImg = $this->subirImagen($imagen);
        }
        $query = $this->db->prepare("INSERT INTO productos (nombre,descripcion,precio,marca,id_categoria,imagen) VALUES (?,?,?,?,?,?)");
        $query->execute([$nom,$desc,$precio,$marca,$cat,$rutaImg]);
        return $this->db->lastInsertId();
    }

    public function subirImagen($imagen){
        $destino = "./img/productos/" . uniqid() . "." . strtolower(pathinfo($imagen['name'], PATHINFO_EXTENSION));  
        move_uploaded_file($imagen['tmp_name'], $destino);
        $destino = ".".$destino;
        return $destino;
    }

    public function eliminarProducto($id){
        $query = $this->db->prepare('DELETE FROM `productos` WHERE id = ?');
        $query->execute([$id]);
        return $query;
    }

    public function actualizarProducto($id,$nombre,$descripcion,$precio,$marca,$cat, $imagen = null){
        $rutaImg = null;
        if($imagen){
            $rutaImg = $this->subirImagen($imagen);
            $query = $this->db->prepare("UPDATE `productos` SET nombre=?,descripcion=?,precio=?,marca=?,id_categoria=?,imagen=? WHERE ID=?");
            $query->execute([$nombre,$descripcion,$precio,$marca,$cat,$rutaImg,$id]);
        }else{
            $query = $this->db->prepare("UPDATE `productos` SET nombre=?,descripcion=?,precio=?,marca=?,id_categoria=? WHERE ID=?");
            $query->execute([$nombre,$descripcion,$precio,$marca,$cat,$id]);
        }
        return $query;
    }

    public function obtenerProductosPorCategoria($id){
        $query = $this->db->prepare("SELECT * FROM `productos` WHERE id_categoria = ?");
        $query->execute([$id]);
        $res = $query->fetchAll(PDO::FETCH_OBJ);
        return $res;
    }


}

?>
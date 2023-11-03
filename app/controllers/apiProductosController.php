<?php  
require_once './app/controllers/apiController.php';
require_once './app/models/productoModel.php';

class ProductosApiController extends ApiController{

    private $model;

    function __construct(){
        parent::__construct();
        $this->model = new productoModel();
    }

    function get($params = []){
        // DOMINIO DE LOS POSIBLES GET PARAMETERS
        $camposProductos = ['ID','nombre','descripcion','precio','marca','id_categoria'];
        $ordenes = ['ASC','DESC'];
        
        if(empty($params)){
            if(isset($_GET['sort']) && isset($_GET['order'])){
                if(in_array($_GET['sort'],$camposProductos) && in_array(strtoupper($_GET['order']),$ordenes)){
                    $atributo = $_GET['sort'];
                    $orden = strtoupper($_GET['order']);
                    
                    //ORDER BY nombre ASC
                    $concat = "ORDER BY $atributo $orden";
                    
                    $productos = $this->model->obtenerProductosOrdenado($concat);

                    if($productos){
                        $this->view->response($productos,200);
                        return;
                    }else{
                        $this->view->response("No hemos encontrado productos que contengan $atributo",404);
                        return;
                    }
                }else{
                    $this->view->response("Por favor seleccione un campo y orden adecuado",400);
                    return;
                }
            }else if(isset($_GET['sort'])&&isset($_GET['value'])){
                if(in_array($_GET['sort'],$camposProductos) && !empty($_GET['value'])){
                    $atributo = $_GET['sort'];
                    $valor = $_GET['value'];
                    $concat = "WHERE $atributo = '$valor'";
                    
                    $productos = $this->model->obtenerProductosOrdenado($concat);
                    $this->view->response($productos,200);
                    return;
                }else{
                    $this->view->response("Completa correctamente el endpoint",400);
                    return;
                }
            }else if(isset($_GET['page'])){
                if(is_numeric($_GET['page'])){
                    $page = $_GET['page'];
                    if(isset($_GET['limit']) && is_numeric($_GET['limit'])){
                        $limit = $_GET['limit'];
                    }else{
                        $limit = 3;
                    }

                    $inicio = ((int)$page - 1) * (int)$limit;
                    $concat = "LIMIT $inicio,$limit";

                    $productos = $this->model->obtenerProductosOrdenado($concat);
                    $this->view->response($productos,200);
                    return;
                }else{
                    $this->view->response("Debe seleccionar un valor numerico",400);
                    return;
                }
            }else{
                $productos = $this->model->obtenerProductos();
                if($productos){
                    $this->view->response($productos,200);
                    return;
                }else{
                    $this->view->response("No hay elementos",404);
                    return;
                }
            }
        }else{
            if($params[':ID']){
                $id = $params[':ID'];
                $producto = $this->model->obtenerUnProductoPorId($id);
                if($producto){
                    $this->view->response($producto,200);
                    return;
                }else{
                    $this->view->response("No existe el producto con id = $id",404);
                    return;
                }
            }else{
               $this->view->response("Sintaxis de endpoint invalida",400);
               return;
            }    
        }
    }

    function add(){
        $body = $this->getData();

        $nombre = $body->nombre;
        $descripcion = $body->descripcion;
        $precio = $body->precio;
        $marca = $body->marca;
        $categoria = $body->id_categoria;

        if(empty($nombre)||empty($descripcion)||empty($precio)||empty($categoria)||empty($marca)){
            $this->view->response('Por favor,complete todo los campos',400);
            return;
        }else{
            
            $id = $this->model->agregarProducto($nombre,$descripcion,$precio,$marca,$categoria,null);

            $ultimoCreado = $this->model->obtenerUnProductoPorId($id);
            $this->view->response($ultimoCreado, 201);
            return;
        }
    }

    function delete($params = []){
        $id = $params[':ID'];
        $producto = $this->model->obtenerUnProductoPorId($id);
        if($producto) {
            $this->model->eliminarProducto($id);
            $this->view->response('El producto con id='.$id.' ha sido eliminado.', 200);
            return;
        } else {
            $this->view->response('El producto con id='.$id.' no existe.', 404);
            return;
        }
    }

    function update($params = []){
        
        $id = $params[':ID'];
        $producto = $this->model->obtenerUnProductoPorId($id);
        
        if($producto) {
            $body = $this->getData();
            
            if(!empty($body->nombre)||!empty($body->descripcion)||!empty($body->precio)||!empty($body->marca)||!empty($body->categoria)){
                $nombre = $body->nombre;
                $descripcion = $body->descripcion;
                $precio = $body->precio;
                $marca = $body->marca;
                if(!empty($body->imagen)){
                    $imagen = $body->imagen;
                }else{
                    $imagen = null;
                }
                $categoria = $body->id_categoria;

                $this->model->actualizarProducto($id,$nombre,$descripcion,$precio,$marca,$categoria,$imagen);
                $this->view->response('El producto con id='.$id.' ha sido modificado.', 200);
                return;
            }else{
                $this->view->response('Por favor,complete correctamente los campos',400);
                return;
            }
        } else {
            $this->view->response("El producto con id= $id no existe.", 404);
            return;
        }
                
    }
}


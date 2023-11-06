<?php  
require_once './app/controllers/apiController.php';
require_once './app/models/productoModel.php';
require_once './app/helpers/authHelper.php';

class ProductosApiController extends ApiController{

    private $model;
    private $auth;

    function __construct(){
        parent::__construct();
        $this->model = new productoModel();
        $this->auth = new AuthHelper();
    }

    function obtener($params = []){
        // DOMINIO DE LOS POSIBLES GET PARAMETERS
        $camposProductos = ['ID','nombre','descripcion','precio','marca','id_categoria'];
        $ordenes = ['ASC','DESC'];
        
        if(empty($params)){
            if(isset($_GET['campo']) && isset($_GET['orden'])){
                if(in_array($_GET['campo'],$camposProductos) && in_array(strtoupper($_GET['orden']),$ordenes)){
                    $atributo = $_GET['campo'];
                    $orden = strtoupper($_GET['orden']);
                    
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
            }else if(isset($_GET['campo'])&&isset($_GET['valor'])){
                if(in_array($_GET['campo'],$camposProductos) && !empty($_GET['valor'])){
                    $atributo = $_GET['campo'];
                    $valor = $_GET['valor'];
                    $concat = "WHERE $atributo = '$valor'";
                    
                    $productos = $this->model->obtenerProductosOrdenado($concat);
                    if($productos){
                        $this->view->response($productos,200);
                        return;
                    }
                    else{
                        $this->view->response("No se ha encontrado resultados asociados a esos valores",404);
                        return;
                    }
                }else{
                    $this->view->response("Completa correctamente el endpoint",400);
                    return;
                }
            }else if(isset($_GET['pagina'])){
                if(is_numeric($_GET['pagina'])){
                    $pagina = $_GET['pagina'];
                    if(isset($_GET['limite']) && is_numeric($_GET['limite'])){
                        $limite = $_GET['limite'];
                    }else{
                        $limite = 3;
                    }

                    $inicio = ((int)$pagina - 1) * (int)$limite;
                    $concat = "LIMIT $inicio,$limite";

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

    function nuevo(){
        if($this->auth->verificarCliente()){
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
        }else{
            $this->view->response("Debe entregar un token de autorizacion",401);
            return;
        }
    }

    function borrar($params = []){
        if($this->auth->verificarCliente()){
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
        }else{
            $this->view->response("Debe entregar un token de autorizacion",401);
            return;
        }
    }

    function actualizar($params = []){
        if($this->auth->verificarCliente()){    
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
        }else{
            $this->view->response("Debe entregar un token de autorizacion",401);
            return;
        }           
    }
}


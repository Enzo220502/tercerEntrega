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
        if(empty($params)){
            $productos = $this->model->obtenerProductos();
            $this->view->response($productos, 200);
        }
        else{
            $producto = $this->model->obtenerUnProductoPorId($params[':ID']);
            if(!empty($producto)){
                if(!empty($params[':subrecurso'])) {
                    switch ($params[':subrecurso']) {
                        case 'nombre':
                            $this->view->response($producto->nombre, 200);
                            break;
                        case 'descripcion':
                            $this->view->response($producto->descripcion, 200);
                            break;
                        case 'precio':
                            $this->view->response($producto->precio,200);
                            break;
                        case 'marca':
                            $this->view->response($producto->marca,200);
                            break;
                        case 'imagen':
                            $this->view->response($producto->imagen,200);
                            break;
                        case 'nombre_categoria':
                            $this->view->response($producto->nombre_categoria,200);
                            break;
                        default:
                            $this->view->response('El producto no contiene '.$params[':subrecurso'].'.', 404);
                            break;
                    }
                }
                else{
                    $this->view->response($producto, 200);
                }
            }
            else{
                $this->view->response('El producto con el id='.$params[':ID'].' no existe.', 404);
            }
        }
    }

    function add(){
        $body = $this->getData();

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

        if(empty($nombre)||empty($descripcion)||empty($precio)||empty($categoria)||empty($marca)){
            $this->view->response('Por favor,complete todo los campos',400);
        }else{
            
            $id = $this->model->agregarProducto($nombre,$descripcion,$precio,$marca,$categoria,$imagen);

            $ultimoCreado = $this->model->obtenerUnProductoPorId($id);
            $this->view->response($ultimoCreado, 201);
        }
    }

    function delete($params = []){
        $id = $params[':ID'];
        $producto = $this->model->obtenerUnProductoPorId($id);
        if($producto) {
            $this->model->eliminarProducto($id);
            $this->view->response('El producto con id='.$id.' ha sido eliminado.', 200);
        } else {
            $this->view->response('El producto con id='.$id.' no existe.', 404);
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
            }else{
                $this->view->response('Por favor,complete correctamente los campos',400);
            }
        } else {
            $this->view->response('El producto con id='.$id.' no existe.', 404);
        }
                
    }
}


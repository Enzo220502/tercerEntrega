<?php  
require_once './app/controllers/apiController.php';
require_once './app/models/productoModel.php';
require_once './app/helpers/authHelper.php';

class ProductosApiController extends ApiController{

    private $model;
    private $auth;
    private $camposProductos;

    function __construct(){
        parent::__construct();
        $this->model = new productoModel();
        $this->auth = new AuthHelper();
        $this->camposProductos = [];
    }

    function generarCampos(){
        $aux = $this->model->obtenerCampos();
        for($i = 0;$i<count($aux);$i++){
            array_push($this->camposProductos,$aux[$i]->Field);
        }
        return $this->camposProductos;
    }

    function obtener($params = []){

        // DOMINIO DE LOS POSIBLES QUERY PARAMS
        $this->generarCampos();
        $ordenes = ['ASC','DESC'];

        if(empty($params)){

            $consultaFinal = "";
            $parcialCampo = "";
            $ordenPorParcial = "";
            $paginadoParcial = "";

            if(isset($_GET['campo']) && isset($_GET['valor'])){

                if(in_array($_GET['campo'],$this->camposProductos)){
                    
                    $campo = $_GET['campo'];
                    $valor = $_GET['valor'];

                    $parcialCampo = "WHERE $campo = '$valor'";

                }else{
                    $this->view->response("Campo incorrecto,seleccione un valor del dominio.",400);
                    return;
                }

            }
            
            if(isset($_GET['ordenPor'])&& isset($_GET['orden'])){

                if(in_array(($_GET['ordenPor']),$this->camposProductos)){
                    $ordenPor = $_GET['ordenPor'];
                    
                    if(in_array($_GET['orden'],$ordenes)){
                        $orden = $_GET['orden'];
                    }
                    else{
                        $this->view->response("Debe seleccionar un orden adecuado",400);
                        return;
                    }

                    $ordenPorParcial = "ORDER BY $ordenPor $orden ";
                }else{
                    $this->view->response("ordenPor invalido,por favor seleccione uno adecuado",400);
                    return;
                }
            }
            
            if(isset($_GET['pagina'])){

                if(is_numeric($_GET['pagina'])){
                    $pagina = $_GET['pagina'];

                    if(isset($_GET['limite'])){
                        $limite = $_GET['limite'];
                    }else{
                        $limite = 3;
                    }

                    $inicio = ((int)$pagina - 1) * ((int)$limite);

                    $paginadoParcial = "LIMIT $inicio,$limite ";

                }else{
                    $this->view->response("Pagina invalida,por favor seleccione un valor numerico",400);
                    return;
                }

            }
            
            $consultaFinal = $parcialCampo.$ordenPorParcial.$paginadoParcial; //PREPARO : WHERE nombre = 'valor' ORDER BY precio ASC LIMIT 1,3

            $productos = $this->model->obtenerOrdenado($consultaFinal);
            
            if($productos){
                $this->view->response($productos,200);
                return;
            }else{
                $this->view->response("No se han encontrado productos relacionados con su busqueda",404);
                return;
            }

            
        }else{
            if($params[':ID']){
                $id = $params[':ID'];
                $producto = $this->model->obtenerPorId($id);
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
            $img = $body->imagen;

            if(empty($body->imagen)){
                $img = null;
            }


            if(empty($nombre)||empty($descripcion)||empty($precio)||empty($categoria)||empty($marca)){
                $this->view->response('Por favor,complete todo los campos',400);
                return;
            }else{

                $id = $this->model->agregarProducto($nombre,$descripcion,$precio,$marca,$categoria,$img);

                $ultimoCreado = $this->model->obtenerPorId($id);
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
            $producto = $this->model->obtenerPorId($id);
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
            $producto = $this->model->obtenerPorId($id);
            
            if($producto) {
                $body = $this->getData();
                
                if(!empty($body->nombre)||!empty($body->descripcion)||!empty($body->precio)||!empty($body->marca)||!empty($body->categoria)){
                    $nombre = $body->nombre;
                    $descripcion = $body->descripcion;
                    $precio = $body->precio;
                    $marca = $body->marca;
                    $categoria = $body->id_categoria;
                    $imagen = $body->imagen;

                    if(empty($body->imagen)){
                        $imagen = null;
                    }
                
                    $prod = $this->model->actualizarProducto($id,$nombre,$descripcion,$precio,$marca,$categoria,$imagen);
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


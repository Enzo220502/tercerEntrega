<?php
require_once './app/models/categoriaModel.php';
require_once './app/views/apiView.php';

class CategoriasApiController extends ApiController{

    private $model;

    function __construct(){
        parent::__construct();
        $this->model = new categoriaModel();
    }

    public function obtener(){
        $cats = $this->model->obtenerCategorias();
        if($cats){
            $this->view->response($cats,200);
            return;
        }else{
            $this->view->response("No hemos encontrado categorias",404);
            return;
        }
    }



}
<?php

require_once './app/models/usersModel.php';
require_once './app/helpers/authHelper.php';

class UserApiController extends ApiController{

    private $model;
    private $helper;

    function __construct(){
        parent::__construct();
        $this->model = new UsersModel();
        $this->helper = new AuthHelper();
    }

    function obtenerToken($params = []) {
        $basic = $this->helper->obtenerAuthHeaders();

        if(empty($basic)) {
            $this->view->response('No envió encabezados de autenticación.', 401);
            return;
        }

        $basic = explode(" ", $basic); // quedaria ["Basic", "base64(usr:pass)"]

        if($basic[0]!="Basic") {
            $this->view->response('Los encabezados de autenticación son incorrectos.', 401);
            return;
        }

        $userpass = base64_decode($basic[1]); // usr:pass
        $userpass = explode(":", $userpass); // ["usr", "pass"]

        $nombreUsuario = $userpass[0];
        $password = $userpass[1];

        
        $usuario = $this->model->obtenerUsuario($nombreUsuario);

        if($usuario&&password_verify($password,$usuario->clave)) {
            $usuario = [ "nombre" => $usuario->nombre, "id" => $usuario->id];
            $token = $this->helper->crearToken($usuario);
            $this->view->response($token,200);
            return;
        } else {
            $this->view->response('El usuario o contraseña son incorrectos.', 401);
            return;
        }
    }

}
?>
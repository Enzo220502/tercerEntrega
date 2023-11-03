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

    function getToken($params = []) {
        $basic = $this->helper->getAuthHeaders();

        if(empty($basic)) {
            $this->view->response('No envió encabezados de autenticación.', 401);
            return;
        }

        $basic = explode(" ", $basic); // ["Basic", "base64(usr:pass)"]

        if($basic[0]!="Basic") {
            $this->view->response('Los encabezados de autenticación son incorrectos.', 401);
            return;
        }

        $userpass = base64_decode($basic[1]); // usr:pass
        $userpass = explode(":", $userpass); // ["usr", "pass"]

        $nombreUsuario = $userpass[0];
        $password = $userpass[1];

        
        $user = $this->model->obtenerUsuario($nombreUsuario);
        
        if($user&&password_verify($password,$user->clave)) {
            $userdata = [ "nombre" => $user->nombre, "id" => $user->id];
            $token = $this->helper->createToken($userdata);
            $this->view->response($token,200);
            return;
        } else {
            $this->view->response('El usuario o contraseña son incorrectos.', 401);
            return;
        }
    }

}
?>
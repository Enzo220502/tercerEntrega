<?php
    require_once './db/config.php';

    function base64url_encode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    class AuthHelper {
        
        function obtenerAuthHeaders() {
            $encabezado = "";
            if(isset($_SERVER['HTTP_AUTHORIZATION']))
                $encabezado = $_SERVER['HTTP_AUTHORIZATION'];
            if(isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION']))
                $encabezado = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
            return $encabezado;
        }

        function crearToken($cuerpo) {
            $encabezado = array(
                'alg' => 'HS256',
                'typ' => 'JWT'
            );

            $encabezado = base64url_encode(json_encode($encabezado));
            $cuerpo = base64url_encode(json_encode($cuerpo));
            
            $firma = hash_hmac('SHA256', "$encabezado.$cuerpo", JWT_KEY, true);
            $firma = base64url_encode($firma);

            $token = "$encabezado.$cuerpo.$firma";
            
            return $token;
        }

        function verificarToken($token) {
            // $encabezado.$cuerpo.$firma

            $token = explode(".", $token); // [$encabezado, $cuerpo, $firma]
            $encabezado = $token[0];
            $cuerpo = $token[1];
            $firma = $token[2];

            $firmaNueva = hash_hmac('SHA256', "$encabezado.$cuerpo", JWT_KEY, true);
            $firmaNueva = base64url_encode($firmaNueva);

            if($firma!=$firmaNueva) {
                return false;
            }

            $cuerpo = json_decode(base64_decode($cuerpo));

            return $cuerpo;
        }

        function verificarCliente() {
            $auth = $this->obtenerAuthHeaders(); // "Bearer $token"
            $auth = explode(" ", $auth); // ["Bearer", "$token"]

            if($auth[0] != "Bearer") {
                return false;
            }

            return $this->verificarToken($auth[1]); // Si está bien nos devuelve el payload
        }
    }
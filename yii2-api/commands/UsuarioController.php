<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\Usuario;

class UsuarioController extends Controller
{
    public function actionCrearAdmin($usuario, $contrasena){
        $user = new Usuario();
        $user->nombre_usuario = $usuario;
        $user->rol = Usuario::ROL_ADMIN;
        $user->estado = 'activo';
        $user->setPassword($contrasena);
        
        if ($user->save()) {
            echo "Usuario administrador creado correctamente.\n";
        } else {
            print_r($user->getErrors());
        }
    }
}

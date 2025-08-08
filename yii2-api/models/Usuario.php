<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class Usuario extends ActiveRecord implements IdentityInterface
{
    const ROL_ADMIN = 'administrador'; // ✅ Debe coincidir con ENUM de la DB
    const ROL_BASICO = 'basico';

    public static function tableName(){
        return 'usuario';
    }

    public function rules(){
        return [
            [['nombre_usuario', 'pass', 'rol', 'estado'], 'required'],
            [['nombre_usuario'], 'unique'],
            [['estado'], 'in', 'range' => ['activo', 'inactivo']], // ✅ no boolean
            [['rol'], 'in', 'range' => [self::ROL_ADMIN, self::ROL_BASICO]]
        ];
    }

    public function fields(){
        $fields = parent::fields();
        unset($fields['pass']);
        return $fields;
    }

    public static function findIdentity($id){
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null){
        return null;
    }

    public function getId(){
        return $this->id;
    }

    public function getAuthKey(){
        return null;
    }

    public function validateAuthKey($authKey){
        return true;
    }

    public static function findByUsername($username){
        return static::findOne(['nombre_usuario' => $username]);
    }

    public function validatePassword($password){
        return Yii::$app->getSecurity()->validatePassword($password, $this->pass);
    }

    public function setPassword($password){
        $this->pass = Yii::$app->getSecurity()->generatePasswordHash($password);
    }
}

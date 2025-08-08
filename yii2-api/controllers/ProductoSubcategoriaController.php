<?php

namespace app\controllers;

use yii\rest\ActiveController;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use app\components\JwtAuth;

class ProductoSubcategoriaController extends ActiveController
{
    public $modelClass = 'app\models\ProductoSubcategoria';

    public function behaviors(){
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => JwtAuth::class,
            'except' => ['options'],
        ];

        $behaviors['access'] = [
            'class' => AccessControl::class,
            'ruleConfig' => [
                'class' => \yii\filters\AccessRule::class,
            ],
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
            'denyCallback' => function () {
                throw new ForbiddenHttpException('NO AUTORIZADO');
            },
        ];

        return $behaviors;
    }
}

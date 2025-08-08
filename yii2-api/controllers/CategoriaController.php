<?php

namespace app\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\components\JwtAuth;
use app\models\Categoria;

class CategoriaController extends ActiveController
{
    public $modelClass = 'app\models\Categoria';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => JwtAuth::class,
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
                throw new ForbiddenHttpException("NO AUTENTICADO");
            },
        ];

        // Habilitamos POST para delete-categoria
        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'delete-categoria' => ['POST'],
            ],
        ];

        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['delete']);

        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
    }

    public function actionDeleteCategoria()
    {
        $request = Yii::$app->request;
        $id = $request->post('id'); // Recibimos el ID desde el cuerpo

        if (!$id) {
            throw new NotFoundHttpException("ID no proporcionado.");
        }

        $categoria = Categoria::findOne($id);
        if (!$categoria) {
            throw new NotFoundHttpException("Categoría no encontrada.");
        }

        $categoria->inactivarConRelacion();

        Yii::$app->response->statusCode = 204;
        return null;
    }

    public function prepareDataProvider()
    {
        return Categoria::find()
            ->where(['estado' => Categoria::ESTADO_ACTIVA])
            ->all();
    }
}

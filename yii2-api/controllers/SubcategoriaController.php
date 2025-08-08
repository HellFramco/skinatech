<?php

namespace app\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\components\JwtAuth;
use app\models\Subcategoria;

class SubcategoriaController extends ActiveController
{
    public $modelClass = 'app\models\Subcategoria';

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

        // ✅ Permitimos POST en vez de DELETE
        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'delete-subcategoria' => ['POST'],
            ],
        ];

        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['delete']); // Quitamos DELETE por defecto

        // ✅ Hacemos que el GET use solo las activas
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

        return $actions;
    }

    // ✅ POST en lugar de DELETE
    public function actionDeleteSubcategoria()
    {
        $id = Yii::$app->request->post('id');

        if (!$id) {
            throw new NotFoundHttpException("ID no proporcionado.");
        }

        $subcategoria = Subcategoria::findOne($id);
        if (!$subcategoria) {
            throw new NotFoundHttpException("Subcategoría no encontrada.");
        }

        $subcategoria->inactivarConRelacion();

        Yii::$app->response->statusCode = 204;
        return null;
    }

    // ✅ Solo listar activas
    public function prepareDataProvider()
    {
        return new \yii\data\ActiveDataProvider([
            'query' => Subcategoria::find()
                ->where(['estado' => Subcategoria::ESTADO_ACTIVA])
                ->with('categoria'), // 👉 incluimos la relación para mostrar el nombre
        ]);
    }
}

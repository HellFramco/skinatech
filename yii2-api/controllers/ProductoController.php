<?php

namespace app\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use app\components\JwtAuth;
use app\models\Usuario;
use app\models\Producto;
use app\models\ProductoSubcategoria;

class ProductoController extends ActiveController
{
    public $modelClass = 'app\models\Producto';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => JwtAuth::class,
            'except' => ['options'],
        ];

        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'delete-producto' => ['POST'],
                'update-producto' => ['PUT', 'POST'],
            ],
        ];

        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['delete'], $actions['update']); // quitamos update para personalizarlo
        return $actions;
    }

    public function actionDeleteProducto()
    {
        $user = Yii::$app->user->identity;

        if ($user->rol !== Usuario::ROL_ADMIN) {
            throw new ForbiddenHttpException('No tienes permiso para eliminar productos.');
        }

        $id = Yii::$app->request->post('id');
        if (!$id) {
            throw new NotFoundHttpException("ID no proporcionado.");
        }

        $producto = Producto::findOne($id);
        if (!$producto) {
            throw new NotFoundHttpException("Producto no encontrado.");
        }

        $producto->setEstadoToInactiva();
        $producto->save(false);

        Yii::$app->response->statusCode = 204;
        return null;
    }

    public function actionUpdateProducto($id)
    {
        $producto = Producto::findOne($id);
        if (!$producto) {
            throw new NotFoundHttpException("Producto no encontrado.");
        }

        $body = Yii::$app->request->getBodyParams();

        $transaction = Yii::$app->db->beginTransaction();
        try {
            // Cargar datos simples
            $producto->load($body, '');
            
            if (!$producto->save(false)) {
                throw new \RuntimeException("No se pudo guardar el producto.");
            }

            // Actualizar subcategoría y cantidad
            if (isset($body['subcategoria_id']) && isset($body['cantidad'])) {
                $psc = ProductoSubcategoria::findOne([
                    'producto_id' => $producto->id,
                    'subcategoria_id' => $body['subcategoria_id']
                ]);

                if (!$psc) {
                    $psc = new ProductoSubcategoria();
                    $psc->producto_id = $producto->id;
                    $psc->subcategoria_id = $body['subcategoria_id'];
                }

                $psc->cantidad = $body['cantidad'];
                if (!$psc->save(false)) {
                    throw new \RuntimeException("No se pudo guardar ProductoSubcategoria.");
                }
            }

            $transaction->commit();
            $producto->refresh();
            return $producto;

        } catch (\Throwable $e) {
            $transaction->rollBack();
            Yii::error("Error update-producto id={$id}: " . $e->getMessage(), __METHOD__);
            Yii::$app->response->statusCode = 500;
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }
}

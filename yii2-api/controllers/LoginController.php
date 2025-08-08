<?php

namespace app\controllers;

use Yii;
use yii\rest\Controller;
use yii\filters\VerbFilter;
use app\models\Usuario;
use yii\web\Response;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use DateTimeImmutable;

class LoginController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // Solo permite POST en actionIndex
        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'index' => ['POST', 'OPTIONS'],
            ],
        ];

        return $behaviors;
    }

    public function actionIndex()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $request = Yii::$app->request;
        $username = $request->post('nombre_usuario');
        $password = $request->post('pass');

        $user = Usuario::findOne(['nombre_usuario' => $username]);

        if (!$user || !$user->validatePassword($password)) {
            return ['error' => 'Usuario o contraseña incorrectos'];
        }

        $config = Configuration::forSymmetricSigner(
            new Sha256(),
            InMemory::plainText('pruebaSkinatech12345678901234567890')
        );

        $now = new DateTimeImmutable();

        $token = $config->builder()
            ->issuedBy('http://localhost')
            ->permittedFor('http://localhost')
            ->identifiedBy(bin2hex(random_bytes(8)))
            ->issuedAt($now)
            ->canOnlyBeUsedAfter($now)
            ->expiresAt($now->modify('+1 hour'))
            ->withClaim('uid', $user->id)
            ->getToken($config->signer(), $config->signingKey());

        return [
            'access_token' => $token->toString(),
            'expires' => $now->modify('+1 hour')->getTimestamp(),
            'nombre_usuario' => $user->nombre_usuario,
            'rol' => $user->rol,
        ];
    }
}

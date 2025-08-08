<?php

namespace app\components;

use Yii;
use yii\filters\auth\AuthMethod;
use yii\web\UnauthorizedHttpException;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Constraint\ValidAt;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\Clock\SystemClock;
use app\models\Usuario;

class JwtAuth extends AuthMethod
{
    public function authenticate($user, $request, $response){
        $authHeader = $request->getHeaders()->get('Authorization');
        if (!$authHeader || !preg_match('/^Bearer\s+(.*?)$/', $authHeader, $matches)) {
            return null;
        }

        $tokenString = $matches[1];

        $signer = new Sha256();
        $key = InMemory::plainText('pruebaSkinatech12345678901234567890');

        $config = Configuration::forSymmetricSigner($signer, $key);

        $config->setValidationConstraints(
            new SignedWith($signer, $key),
            new ValidAt(SystemClock::fromUTC())
        );

        try {
            $parsedToken = $config->parser()->parse($tokenString);

            $constraints = $config->validationConstraints();
            if (!$config->validator()->validate($parsedToken, ...$constraints)) {
                throw new UnauthorizedHttpException('Token inválido.');
            }

            $uid = $parsedToken->claims()->get('uid');

            $identity = Usuario::findOne($uid);
            if ($identity === null) {
                throw new UnauthorizedHttpException('Usuario no encontrado.');
            }

            Yii::$app->user->setIdentity($identity);
            return $identity;

        } catch (\Throwable $e) {
            throw new UnauthorizedHttpException('Error al procesar token: ' . $e->getMessage());
        }
    }

    public function challenge($response){
        $response->getHeaders()->set('WWW-Authenticate', 'Bearer realm="api"');
    }
}

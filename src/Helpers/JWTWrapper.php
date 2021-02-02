<?php

namespace App\Helpers;

use Exception;
use \Firebase\JWT\JWT;


/**
 * Gerenciamento de tokens JWT
 */
class JWTWrapper
{
    const KEY = '123'; // chave

    /**
     * Geracao de um novo token jwt
     */
    public static function encode(array $options)
    {
        $issuedAt = time();
        $expire = $issuedAt + $options['expiration_sec']; // tempo de expiracao do token

        $tokenParam = [
            'iat'  => $issuedAt,            // timestamp de geracao do token
            'iss'  => $options['iss'],      // dominio, pode ser usado para descartar tokens de outros dominios
            'exp'  => $expire,              // expiracao do token
            'nbf'  => $issuedAt - 1,        // token nao eh valido Antes de
            'data' => $options['userdata'], // Dados do usuario logado
        ];

        return JWT::encode($tokenParam, self::KEY);
    }

    /**
     * Decodifica token jwt
     */
    public static function decode($jwt)
    {
        return JWT::decode($jwt, self::KEY, ['HS256']);
    }

    public function auth($route)
    {
        $http_header = apache_request_headers();

        if (isset($http_header['Authorization']) && $http_header['Authorization'] != null) {
            // $bearer = explode(' ', $http_header['Authorization']);

            list($jwt) = sscanf($http_header['Authorization'], 'Bearer %s');

            if ($jwt) {
                try {
                    return $route->inApp = JWTWrapper::decode($jwt);
                } catch (Exception $ex) {
                    // nao foi possivel decodificar o token jwt
                    return ["error" => true, "message" => "Acesso nao autorizado"];
                }
            } else {
                // nao foi possivel extrair token do header Authorization
                return ["error" => true, "message" => "Token nao informado"];
            }
        }

        return ["error" => true, "message" => "Header Authorization não encontrado"];
    }
}

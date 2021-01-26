<?php


namespace App\Controllers;

use App\Helpers\Helper;
use App\Models\User;

class UserController
{

    use Helper;

    public function loginUser(array $data)
    {
        $inputs = ["email", "password"];

        //Verifico se os campos de $inputs existem e estão preenchidos
        if (Helper::isEmpty($inputs, $data)) {
            // Estão vazios
            echo Helper::jsonSend("Preencha todos os campos!", "error");
            return;
        }

        //Verifica se usuario e senha conferem        
        $authenticate = (new User)->authenticateUser($data["email"], $data["password"]);

        if ($authenticate) {

            echo Helper::jsonSend("Vamos te logar agora, um momento.", "success", "/");
        } else {

            echo Helper::jsonSend("Email ou senha incorretos!", "error");
        }
    }

    public function registerUser(array $data)
    {
        $inputs = ["email", "password", "confirmpassword", "name"];

        //Verifico se os campos estão preenchidos
        if (Helper::isEmpty($inputs, $data)) {
            echo Helper::jsonSend("Preencha todos os campos!", "error");
            return;
        }
        // Confirma a igualdade das senhas
        if ($data['password'] != $data['confirmpassword']) {
            echo Helper::jsonSend("Senhas diferentes!", "error");
            return;
        }

        unset($data['confirmpassword']);

        $user = new User();

        $userHasEmail = $user->findByEmail($data['email']);

        if (!$userHasEmail) {

            $create = $user->createUser($data);

            if ($create) {

                echo Helper::jsonSend("Bem-Vindo", "success", "/");
            } else {

                echo Helper::jsonSend("Desculpe, tivemos um erro inesperado!", "error");
            }
        } else {

            echo Helper::jsonSend("Email já cadastrado!", "error");
        }
    }
}

<?php


namespace App\Controllers;

use App\Models\User;

class UserController
{
    public function loginUser(array $data)
    {
        //Verifico se os campos estão preenchidos
        if (!empty($data["email"]) && !empty($data["password"])) {

            //Verifica se usuario pode se autenticar
            $authenticate = (new User)->authenticateUser($data["email"], $data["password"]);

            if ($authenticate) {
                echo json_encode(array(
                    "message" => "Vamos te logar agora, um momento.",
                    "url" => URL_BASE . "/",
                    "status" => "success"
                ));
            } else {

                echo json_encode(array(
                    "message" => "Email ou senha incorretos!",
                    "status" => "error"
                ));
            }
        } else {
            echo json_encode(array(
                "message" => "Preencha todos os campos!",
                "status" => "error"
            ));
        }
    }

    public function registerUser(array $data)
    {
        //Verifico se os campos estão preenchidos
        if (!empty($data["email"]) && !empty($data["password"]) && !empty($data["confirmpassword"]) && !empty($data["name"])) {

            // Confirma a igualdade das senhas
            if ($data['password'] == $data['confirmpassword']) {

                unset($data['confirmpassword']);

                $user = new User();

                $userHasEmail = $user->findByEmail($data['email']);

                if (!$userHasEmail) {

                    $create = $user->createUser($data);

                    if ($create) {
                        echo json_encode(array(
                            "msg" => "Bem-Vindo",
                            "status" => "success",
                            "url" => URL_BASE . "/"
                        ));
                    } else {
                        echo json_encode(array(
                            "message" => "Desculpe, tivemos um erro inesperado!",
                            "status" => "error"
                        ));
                    }
                } else {
                    echo json_encode(array(
                        "message" => "Email já cadastrado!",
                        "status" => "error"
                    ));
                }
            } else {
                echo json_encode(array(
                    "message" => "Senhas diferentes!",
                    "status" => "error"
                ));
            }
        } else {
            echo json_encode(array(
                "message" => "Preencha todos os campos!",
                "status" => "error"
            ));
        }
    }
}

<?php


namespace App\Controllers;

use App\Helpers\Helper;
use App\Helpers\HttpStatusCode;
use App\Models\User;

class UserController
{

    use Helper;

    public function loginUser(array $data)
    {
        $inputs = ["email", "password"];

        //Verifico se os campos de $inputs existem e estão preenchidos
        if (Helper::isEmpty($inputs, $data))
            return Helper::jsonSend("Preencha todos os campos!", HttpStatusCode::BAD_REQUEST); // Estão vazios

        //Verifica se usuario e senha conferem        
        $authenticate = (new User)->authenticateUser($data["email"], $data["password"]);

        if ($authenticate)
            return Helper::jsonSend("Vamos te logar agora, um momento.", HttpStatusCode::ACCEPTED);

        return Helper::jsonSend("Email ou senha incorretos!", HttpStatusCode::BAD_REQUEST);
    }

    public function registerUser(array $data)
    {
        $inputs = ["email", "password", "confirmpassword", "name"];

        //Verifico se os campos estão preenchidos
        if (Helper::isEmpty($inputs, $data)) return Helper::jsonSend("Preencha todos os campos!", HttpStatusCode::BAD_REQUEST);

        // Confirma a igualdade das senhas
        if ($data['password'] != $data['confirmpassword']) return Helper::jsonSend("Senhas diferentes!", HttpStatusCode::BAD_REQUEST);

        unset($data['confirmpassword']);

        $user = new User();

        $userHasEmail = $user->findByEmail($data['email']);

        if (!$userHasEmail) {

            $create = $user->createUser($data);

            if ($create) return Helper::jsonSend("Bem-Vindo", "success", "/");

            return Helper::jsonSend("Desculpe, tivemos um erro inesperado!", HttpStatusCode::INTERNAL_SERVER_ERROR);
        }

        return Helper::jsonSend("Email já cadastrado!", HttpStatusCode::BAD_REQUEST);
    }

    public function getDataUser(int $id)
    {
        # code...
    }

    public function setDataUser(array $data)
    {
        $inputs = ["email", "password", "name", "bio", "slug"];

        //Verifico se os campos estão preenchidos
        if (Helper::isEmpty($inputs, $data)) return Helper::jsonSend("Preencha todos os campos!", HttpStatusCode::BAD_REQUEST);

        $user = new User();

        $authenticate = $user->verifyToken();

        if (!$authenticate) return Helper::jsonSend("Não autenticado", HttpStatusCode::NOT_ACCEPTABLE); // usuário não autenticado

        $userHasEmail = $user->findByEmail($data['email']);

        if ($userHasEmail) return Helper::jsonSend("Email já cadastrado!", HttpStatusCode::BAD_REQUEST);

        $userHasSlug = $user->findBySlug($data["slug"]);

        if ($userHasSlug) return Helper::jsonSend("Nome de Usuário já cadastrado!", HttpStatusCode::BAD_REQUEST);

        $update = $user->updateUser($authenticate->id, ((object) $data));

        if ($update) return Helper::jsonSend("Bem-Vindo", "success", "/");

        return Helper::jsonSend("Desculpe, tivemos um erro inesperado!", HttpStatusCode::INTERNAL_SERVER_ERROR);
    }

    // Seguir Usuário 
    public function followUser(int $id)
    {
        $user = new User();

        $authenticate = $user->verifyToken();

        // usuário não autenticado
        if (!$authenticate)
            return Helper::jsonSend("Você precisa se logar para seguir alguém!", HttpStatusCode::BAD_REQUEST);

        if (!$user->createFollow($id, $authenticate->id))
            return Helper::jsonSend("Você já segue essa pessoa!", HttpStatusCode::BAD_REQUEST);

        return Helper::jsonSend("Agora você está seguindo essa pessoa!", HttpStatusCode::CREATED);
    }

    // Deixar de seguir Usuário
    public function unfollowUser(int $id)
    {
        $user = new User();

        $authenticate = $user->verifyToken();

        if (!$authenticate)
            return Helper::jsonSend("Você precisa se logar para deixa de seguir alguém!", HttpStatusCode::BAD_REQUEST);

        if (!$user->deleteFollow($id, $authenticate->id))
            return Helper::jsonSend("Desculpe, tente de novo mais tarde!", HttpStatusCode::INTERNAL_SERVER_ERROR);

        return Helper::jsonSend("Agora você não segue mais essa pessoa!", HttpStatusCode::OK);
    }

    // Seguindo usuários
    public function followingUsers(string $slug)
    {
        $user = new User();

        $authenticate = $user->verifyToken();

        if (!$authenticate) return Helper::jsonSend("Não autenticado", HttpStatusCode::NOT_ACCEPTABLE); // usuário não autenticado

        // Verifico se usuário com esse slug existe
        $userHasSlug = $user->findBySlug($slug);

        //Se não existir esse user
        if ($userHasSlug) return Helper::jsonSend("Nenhuma informação encontrada!", HttpStatusCode::NOT_ACCEPTABLE);

        // Se exitir trago os usuários que ele está seguindo
        return json_encode($user->findByFollow($userHasSlug->id));
    }
}

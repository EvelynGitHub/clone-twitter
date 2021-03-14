<?php


namespace App\Controllers;

use App\Helpers\Helper;
use App\Helpers\HttpStatusCode;
use App\Helpers\JWTWrapper;
use App\Models\User;

class UserController
{

    use Helper;

    private $route;

    public function __construct($route)
    {
        $this->route = $route;
    }

    public function testeIn()
    {

        return json_encode("testeIn");
        return json_encode($this->route);
    }

    public function loginUser(array $data)
    {
        $inputs = ["email", "password"];

        //Verifico se os campos de $inputs existem e estão preenchidos
        if (Helper::isEmpty($inputs, $data))
            return Helper::jsonSend("Preencha todos os campos!", HttpStatusCode::BAD_REQUEST); // Estão vazios

        //Verifica se usuario e senha conferem        
        $authenticate = (new User)->authenticateUser($data["email"], $data["password"]);

        if (!$authenticate)
            return Helper::jsonSend("Email ou senha incorretos!", HttpStatusCode::BAD_REQUEST);


        $jwt = JWTWrapper::encode([
            'expiration_sec' => 3600,
            'iss' => URL_BASE,
            'userdata' => [
                'id' => $authenticate->id,
                'name' => $authenticate->name,
                "token" => $authenticate->token
            ]
        ]);

        return Helper::jsonSend("Um momento.", HttpStatusCode::ACCEPTED, $jwt);
    }

    public function registerUser(array $data)
    {
        $inputs = ["email", "password", "confirmpassword", "name"];

        //Verifico se os campos estão preenchidos
        if (Helper::isEmpty($inputs, $data))
            return Helper::jsonSend("Preencha todos os campos!", HttpStatusCode::BAD_REQUEST);

        // Confirma a igualdade das senhas
        if ($data['password'] != $data['confirmpassword'])
            return Helper::jsonSend("Senhas diferentes!", HttpStatusCode::BAD_REQUEST);

        unset($data['confirmpassword']);

        $user = new User();

        $userHasEmail = $user->findByEmail($data['email']);

        if ($userHasEmail) {
            return Helper::jsonSend("Email já cadastrado!", HttpStatusCode::BAD_REQUEST);
        }

        $create = $user->createUser($data);

        if (!$create)
            return Helper::jsonSend("Desculpe, tivemos um erro inesperado!", HttpStatusCode::INTERNAL_SERVER_ERROR);


        $authenticate = $user->findById($create);
        // return json_encode($create);

        $jwt = JWTWrapper::encode([
            'expiration_sec' => 3600,
            'iss' => URL_BASE,
            'userdata' => [
                'id' => $authenticate->id,
                'name' => $authenticate->name,
                "token" => $authenticate->token
            ]
        ]);

        return Helper::jsonSend("Bem-Vindo.", HttpStatusCode::ACCEPTED, $jwt);
    }

    public function getDataUser($slugOuid)
    {
        $user = new User;

        $result = $user->findBySlug($slugOuid);
        if (!$result)
            return Helper::jsonSend("Nenhuma informação encontrada!", HttpStatusCode::NOT_ACCEPTABLE);

        unset($result->password);

        return json_encode($result);
    }

    public function getDataUserAuth()
    {
        $user = new User;

        $result = $user->findById($this->route->inApp->data->id);

        if (!$result)
            return Helper::jsonSend("Dados de Usuário Logado não encontrado!", HttpStatusCode::NOT_ACCEPTABLE);

        unset($result->password);

        return json_encode($result);
    }

    public function setDataUser(array $data)
    {
        $inputs = ["email", "name", "bio", "slug"];

        //Verifico se os campos estão preenchidos
        if (Helper::isEmpty($inputs, $data))
            return Helper::jsonSend("Preencha todos os campos!", HttpStatusCode::BAD_REQUEST);

        $user = new User();

        $authenticatedUser = $user->findByIdAndToken($this->route->inApp->data->id, $this->route->inApp->data->token);

        if ($data["email"] != $authenticatedUser->email) {

            $userHasEmail = $user->findByEmail($data['email']);

            if ($userHasEmail)
                return Helper::jsonSend("Email já cadastrado!", HttpStatusCode::BAD_REQUEST);
        }

        if ($data["slug"] != $authenticatedUser->slug) {
            $userHasSlug = $user->findBySlug($data["slug"]);

            if ($userHasSlug)
                return Helper::jsonSend("Nome de Usuário já cadastrado!", HttpStatusCode::BAD_REQUEST);
        }

        if (!empty($data["password"])) {

            $data["password"] = $user->generatePassword($data["password"]);
        }

        if (!isset($data["password"])) {
            $data["password"] = $authenticatedUser->password;
        }

        $update = $user->updateUser($authenticatedUser->id, ((object) $data));

        if ($update) return Helper::jsonSend("Dados alterados com sucesso", HttpStatusCode::OK);

        return Helper::jsonSend("Desculpe, tivemos um erro inesperado!", HttpStatusCode::INTERNAL_SERVER_ERROR);
    }

    // Seguir Usuário 
    public function followUser(int $id)
    {
        if ($id == $this->route->inApp->data->id)
            return Helper::jsonSend("Você não pode seguir a si mesmo!", HttpStatusCode::BAD_REQUEST);

        $user = new User();

        $authenticate = $user->findById($this->route->inApp->data->id);

        if (!$user->createFollow($id, $authenticate->id))
            return Helper::jsonSend("Você já segue essa pessoa!", HttpStatusCode::BAD_REQUEST);

        return Helper::jsonSend("Agora você está seguindo essa pessoa!", HttpStatusCode::CREATED);
    }

    // Deixar de seguir Usuário
    public function unfollowUser(int $id)
    {
        $user = new User();

        if (!$user->deleteFollow($id, $this->route->inApp->data->id))
            return Helper::jsonSend("Desculpe, tente de novo mais tarde!", HttpStatusCode::INTERNAL_SERVER_ERROR);

        return Helper::jsonSend("Agora você não segue mais essa pessoa!", HttpStatusCode::OK);
    }

    // Equivalente a apagar a conta
    public function deleteUser()
    {
        $user = new User();

        if (!$user->deleteUser($this->route->inApp->data->id))
            return Helper::jsonSend("Desculpe, tente de novo mais tarde!", HttpStatusCode::INTERNAL_SERVER_ERROR);

        return Helper::jsonSend("Agora você não segue mais essa pessoa!", HttpStatusCode::OK);
    }

    // Seguindo usuários
    public function followingUsers(string $slug)
    {
        $user = new User();

        // Verifico se usuário com esse slug existe
        $userHasSlug = $user->findBySlug($slug);

        //Se não existir esse user
        if (!$userHasSlug) return Helper::jsonSend("Nenhuma informação encontrada!", HttpStatusCode::NOT_ACCEPTABLE);

        // Se exitir trago os usuários que ele está seguindo
        return json_encode($user->findByFollow($userHasSlug->id));
    }


    public function followingUsersAuth()
    {
        $user = new User();

        $userFollow = $user->findByFollow($this->route->inApp->data->id);

        //Se não existir esse user
        if (!$userFollow) return Helper::jsonSend("Você ainda não segue ninguém!", HttpStatusCode::NOT_ACCEPTABLE);

        // Se exitir trago os usuários que ele está seguindo
        return json_encode($userFollow);
    }
}

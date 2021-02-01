<?php


namespace App\Models;

use App\SimpleCrudPhp\Crud;

class User extends Crud
{

    //INICIO: Funções apenas de User

    private function buildUser(array $data)
    {
        # code...
    }

    private function generatePassword(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);;
    }

    private function generateToken(): string
    {
        return bin2hex(random_bytes(50));
    }

    private function setTokenSession(string $token)
    {
        $_SESSION['token'] = $token;
    }

    public function verifyToken()
    {
        if (empty($_SESSION['token'])) return false;

        $token = $_SESSION['token'];

        $user = $this->findByToken($token);

        if (!$user) return false;

        return $user;
    }

    //FIM: Funções apenas de User


    // INICIO: Funções realicionas ao Banco de Dados

    public function createUser(array $data)
    {
        $data['password'] = $this->generatePassword($data['password']);
        $data['token'] = $this->generateToken();

        // Garrantindo a ordem da inserção ao ordenar o array $data
        ksort($data);

        $user = $this->insert("users", $data)->execute();

        if ($user) $this->setTokenSession($data['token']);

        return $user;
    }

    public function createFollow(int $user_id, int $user_id_follower)
    {
        $follows = $this->insert(
            "follows",
            [$user_id, $user_id_follower],
            "user_id, user_id_followers"
        )->execute();

        return $follows;
    }

    public function updateUser(int $id, object $user)
    {
        //Trasformo o object em array
        $data = get_object_vars($user);

        unset($data['id']);
        unset($data['create_at']);
        unset($data['slug']);

        ksort($data);

        $update = $this->update("users", "
                                bio = ?,
                                email = ?,
                                name = ?,
                                password = ?,    
                                token = ?
                            ", $data)
            ->where("id = ?", [$id])
            ->execute();

        return $update;
    }

    public function findByEmail(string $email)
    {
        $user = $this->select()->from("users")->where("email = ?", [$email])->execute("fetch");

        return $user;
    }

    public function findBySlug(string $slug)
    {
        $user = $this->select()->from("users")->where("slug = ?", [$slug])->execute("fetch");

        return $user;
    }

    public function findByToken(string $token)
    {
        $user = $this->select()
            ->from("users")
            ->where("token = ?", [$token])
            ->execute("fetch");

        return $user;
    }

    public function findByFollow(int $user_id_follower)
    {
        $crud = $this->select()
            ->from('follows')
            ->where("user_id_followers = ? ", [$user_id_follower])
            ->execute("fetchAll");

        return $crud;
    }

    public function deleteUser(int $id)
    {
        $crud = $this->delete()->from('users')->where("id = ?", [$id])->execute();

        return $crud;
    }

    public function deleteFollow(int $user_id_follower, int $user_id)
    {
        $crud = $this->delete()
            ->from('follows')
            ->where("user_id_followers = ? AND user_id = ?", [$user_id_follower, $user_id])
            ->execute();

        return $crud;
    }


    public function authenticateUser(string $email, string $password)
    {
        $user = $this->findByEmail($email);

        if ($user) {
            //as senhas conferem
            if (password_verify($password, $user->password)) {
                $user->token = $this->generateToken();

                // Se a atualização do token ter sucesso
                if ($this->updateUser($user->id, $user)) {
                    $this->setTokenSession($user->token);
                    return true;
                }

                return false;
            }
        } else {
            return false;
        }
    }

    // FIM: Funções realicionas ao Banco de Dados

}
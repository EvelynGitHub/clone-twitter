<?php


namespace App\Models;

use App\SimpleCrudPhp\Crud;

class User extends Crud
{

    //INICIO: Funções apenas de User

    public function generatePassword(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);;
    }

    private function generateToken(): string
    {
        return bin2hex(random_bytes(50));
    }

    private function generateSlug(string $name)
    {
        return $name;
    }

    //FIM: Funções apenas de User


    // INICIO: Funções realicionas ao Banco de Dados

    public function createUser(array $data)
    {
        $data['password'] = $this->generatePassword($data['password']);
        $data['token'] = $this->generateToken();
        $data['slug'] = $this->generateSlug($data["name"]);

        // Garrantindo a ordem da inserção ao ordenar o array $data
        ksort($data);

        $user = $this->insert("users", $data)->execute("lastIdInsert");

        return $user;
    }

    public function createFollow(int $user_id, int $user_id_follower)
    {
        $data = array(
            "user_id" => $user_id,
            "user_id_followers" => $user_id_follower
        );

        $follows = $this->insert(
            "follows",
            $data
        )->execute();

        return $follows;
    }

    public function updateUser(int $id, object $user)
    {
        //Trasformo o object em array
        $data = get_object_vars($user);

        unset($data['id']);
        unset($data['create_at']);
        unset($data['token']);

        ksort($data);

        $update = $this->update("users", "
                                bio = ?,
                                email = ?,
                                name = ?,
                                password = ?,    
                                slug = ?
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

    public function findById(string $id)
    {
        $user = $this->select()
            ->from("users")
            ->where("id = ?", [$id])
            ->execute("fetch");

        return $user;
    }
    public function findByIdAndToken(string $id, string $token)
    {
        $user = $this->select()
            ->from("users")
            ->where("id = ? AND token = ?", [$id, $token])
            ->execute("fetch");

        return $user;
    }

    public function findByFollow(int $user_id_follower, int $user_id = 0)
    {
        $crud = $this->select("f.user_id, u.name, u.slug, f.create_at")
            ->from("follows f 
                    INNER JOIN users u ON f.user_id = u.id ")
            ->where("f.user_id_followers = ? ", [$user_id_follower])
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

        if (!$user) return false;

        //as senhas conferem
        if (password_verify($password, $user->password)) {
            return $user;
        }
        // senhas erradas
        return false;
    }

    // FIM: Funções realicionas ao Banco de Dados

}

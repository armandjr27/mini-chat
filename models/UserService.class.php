<?php
namespace models;
use models\base\Services;
use models\classes\User;
use PDO;

class UserService extends Services
{
    private $table = 'users';

    public function getUserById($idUser)
    {
        if(!$idUser) $idUser = 0;
        $sql     = "SELECT id_user, profil, pseudo, email, join_at FROM {$this->table} WHERE id_user = {$idUser}";
        $request = $this->db->prepare($sql);
        $request->execute();
        $userData = $request->fetch(PDO::FETCH_ASSOC);

        if(!$userData) return;

        $userObjet = new User($userData['id_user'], $userData['profil'], $userData['pseudo'], $userData['email'], $userData['join_at']);

        return $userObjet;
    }

    public function getUserByEmail($email)
    {
        $request = $this->db->prepare($this->select($this->table)->where('email', '=')->getQuery());
        $request->execute([$email]);
        $userData = $request->fetch(PDO::FETCH_ASSOC);

        if(!$userData) return;

        $userObjet = new User($userData['id_user'], $userData['profil'], $userData['pseudo'], $userData['email'], $userData['join_at'], $userData['password']);

        return $userObjet;
    }

    public function getAllUsers()
    {
        $request    = $this->db->query($this->select($this->table)->getQuery());
        $usersData  = $request->fetchAll(PDO::FETCH_ASSOC);
        $usersObjet = [];

        foreach ($usersData as $user)
        {
            $usersObjet[] = new User($user['id_user'], $user['profil'], $user['pseudo'], $user['email'], $user['join_at']);
        }
        
        return $usersObjet;
    }

    public function insertUser($pseudo, $email, $password) 
    {
        $request = $this->db->prepare($this->insert($this->table, ['pseudo', 'email', 'password'], [$pseudo, $email, $password])->getQuery());
        $request->execute();
    }

    public function updateUser($idUser, $pseudo, $email, $profil = null)
    {
        if ($profil)
        {
            $request = $this->db->prepare($this->update($this->table, ['pseudo', 'email', 'profil', 'join_at'], [$pseudo, $email, $profil, 'NOW()'])->where('id_user', '=')->getQuery());
            $request->execute([$idUser]);
        }
        else
        {
            $request = $this->db->prepare($this->update($this->table, ['pseudo', 'email', 'join_at'], [$pseudo, $email, 'NOW()'])->where('id_user', '=')->getQuery());
            $request->execute([$idUser]);
        }
    }

    public function deleteUser($idUser) 
    {
        $request = $this->db->prepare($this->delete($this->table)->where('id_user', '=')->getQuery());
        $request->execute([$idUser]);
    }
}
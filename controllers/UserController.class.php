<?php
namespace controllers;
use models\UserService;
use models\base\FileService;

class UserController
{
    private $userService;
    private $fileService;

    public function __construct() 
    {
        $this->userService = new UserService();
        $this->fileService = new FileService();
    }

    public function signUp()
    {
        $this->saveUser();
    }

    public function signIn()
    {
        $this->authentication();
    }

    public function signOut()
    {
        session_destroy();
        header('Content-Type: application/json');
        echo json_encode(['message' => "Vous êtes bien déconnecté !"]);
    }

    public function authentication()
    {
        $data  = [];
        $email = $this->userService->verifyInput($_POST['email']);
        $user  = $this->userService->getUserByEmail($email);

        if ($user)
        {
            if (password_verify($_POST['password'], $user->getPassword()))
            {
                $_SESSION = [
                    'email'  => $user->getEmail(),
                    'login'  => $user->getPseudo(),
                    'userId' => $user->getIdUser(),
                    'online' => 1,
                ];

                $data = [
                    'message'   => "Bienvenue {$_SESSION['login']} !",
                    'email'     => $_SESSION['email'],
                    'login'     => $_SESSION['login'],
                    'userId'    => $_SESSION['userId'],
                    'online'    => $_SESSION['online'],
                ];
            }
            else
            {
                $data['errorPass'] = " * Le mot de passe est incorrect !";
            }
        }
        else
        {
            $data['errorMail'] = " * L'utilisateur n'existe pas !";
        }

        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function showAllUsers()
    {
        header('Content-Type: application/json');
        echo json_encode($this->userService->getAllUsers());
    }

    public function saveUser($idUser = null)
    {
        $msg    = "";
        $pseudo = $this->userService->verifyInput($_POST['pseudo']);
        $email  = $this->userService->verifyInput($_POST['email']);

        if ($idUser) 
        {
            $idUser = $this->userService->verifyInput($idUser);
            $file   = $this->fileService->uploadFile($_FILES['profil']);
            $profil = $file['name'];

            $this->userService->updateUser($idUser, $pseudo, $email, $profil);

            $msg = " Les infos sur l'utilisateur a bien été mise à jour !";
        }
        else
        {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            
            $this->userService->insertUser($pseudo, $email, $password);

            $msg = "Félicitation, inscription avec succès!";
        }

        header('Content-Type: application/json');
        echo json_encode(['message' => $msg]);
    }

    public function dropUser($idUser)
    {
        $idUser = $this->userService->verifyInput($idUser);

        $this->userService->deleteUser($idUser);

        header('Content-Type: application/json');
        echo json_encode(['message' => " L'utilisateur a bien été supprimer !"]);
    }

    public function isAuthenticated()
    {
        if (isset($_SESSION['online']))
        {
            $data = [
                'email'  => isset($_SESSION['email'])  ? $_SESSION['email']  : "",
                'login'  => isset($_SESSION['login'])  ? $_SESSION['login']  : "",
                'userId' => isset($_SESSION['userId']) ? $_SESSION['userId'] : "",
                'online' => isset($_SESSION['online']) ? $_SESSION['online'] : "",
            ];
        }
        else
        {
            $data = ['online' => 0];
        }

        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
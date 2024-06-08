<?php
namespace models\classes;
use JsonSerializable;

class User implements JsonSerializable
{
    private $idUser;
    private $profil;
    private $pseudo;
    private $email;
    private $password;
    private $joinAt;

    public function __construct($idUser, $profil, $pseudo, $email, $joinAt, $password = null) 
    {
        $this->idUser = $idUser;
        $this->profil = $profil;
        $this->pseudo = $pseudo;
        $this->email = $email;
        $this->joinAt = $joinAt;
        $this->password = $password;
    }

    public function jsonSerialize(): mixed
    {
        $vars = get_object_vars($this);
        return $vars;
    }
    
    /**
     * Get the value of idUser
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * Set the value of idUser
     */
    public function setIdUser($idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }

    /**
     * Get the value of profil
     */
    public function getProfil()
    {
        return $this->profil;
    }

    /**
     * Set the value of profil
     */
    public function setProfil($profil): self
    {
        $this->profil = $profil;

        return $this;
    }

    /**
     * Get the value of pseudo
     */
    public function getPseudo()
    {
        return $this->pseudo;
    }

    /**
     * Set the value of pseudo
     */
    public function setPseudo($pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * Get the value of email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     */
    public function setEmail($email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     */
    public function setPassword($password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of joinAt
     */
    public function getJoinAt()
    {
        return $this->joinAt;
    }

    /**
     * Set the value of joinAt
     */
    public function setJoinAt($joinAt): self
    {
        $this->joinAt = $joinAt;

        return $this;
    }
}
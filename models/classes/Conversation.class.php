<?php
namespace models\classes;
use JsonSerializable;

class Conversation implements JsonSerializable
{
    private $idConversation;
    private $title;
    private $contact;
    private $createdAt;


    public function __construct($idConversation, $title, $createdAt, $contact = null) 
    {
        $this->idConversation = $idConversation;
        $this->title          = $title;
        $this->createdAt      = $createdAt;
        $this->contact        = $contact;
    }
    
    public function jsonSerialize(): mixed
    {
        $vars = get_object_vars($this);
        return $vars;
    }

    /**
     * Get the value of idConversation
     */
    public function getIdConversation()
    {
        return $this->idConversation;
    }

    /**
     * Set the value of idConversation
     */
    public function setIdConversation($idConversation): self
    {
        $this->idConversation = $idConversation;

        return $this;
    }

    /**
     * Get the value of title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title
     */
    public function setTitle($title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of createdAt
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set the value of createdAt
     */
    public function setCreatedAt($createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get the value of contact
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Set the value of contact
     */
    public function setContact($contact): self
    {
        $this->contact = $contact;

        return $this;
    }
}
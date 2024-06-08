<?php
namespace models\classes;
use JsonSerializable;

class Message implements JsonSerializable
{
    private $idMessage;
    private $content;
    private $senderId;
    private $receiverId;
    private $conversationId;
    private $createdAt;

    public function jsonSerialize(): mixed
    {
        $vars = get_object_vars($this);
        return $vars;
    }

    public function __construct($idMessage, $content, $senderId, $receiverId, $conversationId, $createdAt) 
    {
        $this->idMessage      = $idMessage;
        $this->content        = $content;
        $this->senderId       = $senderId;
        $this->receiverId     = $receiverId;
        $this->conversationId = $conversationId;
        $this->createdAt      = $createdAt;
    }

    /**
     * Get the value of idMessage
     */
    public function getIdMessage()
    {
        return $this->idMessage;
    }

    /**
     * Set the value of idMessage
     */
    public function setIdMessage($idMessage): self
    {
        $this->idMessage = $idMessage;

        return $this;
    }

    /**
     * Get the value of content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set the value of content
     */
    public function setContent($content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get the value of senderId
     */
    public function getSenderId()
    {
        return $this->senderId;
    }

    /**
     * Set the value of senderId
     */
    public function setSenderId($senderId): self
    {
        $this->senderId = $senderId;

        return $this;
    }

    /**
     * Get the value of receiverId
     */
    public function getReceiverId()
    {
        return $this->receiverId;
    }

    /**
     * Set the value of receiverId
     */
    public function setReceiverId($receiverId): self
    {
        $this->receiverId = $receiverId;

        return $this;
    }

    /**
     * Get the value of conversationId
     */
    public function getConversationId()
    {
        return $this->conversationId;
    }

    /**
     * Set the value of conversationId
     */
    public function setConversationId($conversationId): self
    {
        $this->conversationId = $conversationId;

        return $this;
    }

    /**
     * Get the value of create_at
     */
    public function getCreateAt()
    {
        return $this->createdAt;
    }

    /**
     * Set the value of create_at
     */
    public function setCreateAt($createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
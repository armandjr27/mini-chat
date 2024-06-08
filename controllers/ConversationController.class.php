<?php
namespace controllers;
use models\ConversationService;
use models\MessageService;

class ConversationController
{
    private $conversationService;
    private $messageService;

    public function __construct() 
    {
        $this->conversationService = new ConversationService();
        $this->messageService      = new MessageService();
    }

    public function showConversationById()
    {
        $idConversation = $this->conversationService->verifyInput($_POST['idConversation']);
        header('Content-Type: application/json');
        echo json_encode($this->conversationService->getConversationById($idConversation));
    }

    public function showConversationByUserId()
    {
        $userId = $this->conversationService->verifyInput($_SESSION['userId']);
        header('Content-Type: application/json');
        echo json_encode($this->conversationService->getConversationByUserId($userId));
    }

    public function showAllConversations()
    {
        header('Content-Type: application/json');
        echo json_encode($this->conversationService->getAllConversations());
    }

    public function showMessagesOfConversation()
    {
        $idConversation = $this->conversationService->verifyInput($_POST['idConversation']);
        header('Content-Type: application/json');
        echo json_encode($this->messageService->getMessagesByConversationId($idConversation));
    }

    public function sendMessageToConversation() 
    {
        $content        = $this->conversationService->verifyInput($_POST['content']);
        $senderId       = $this->conversationService->verifyInput($_POST['senderId']);
        $receiverId     = $this->conversationService->verifyInput($_POST['receiverId']);
        $conversationId = $this->conversationService->verifyInput($_POST['conversationId']);

        $this->messageService->insertMessage($content, $senderId, $receiverId, $conversationId);
        header('Content-Type: application/json');
        echo json_encode(['content' => $content]);
    }

    public function newConversation()
    {
        $title          = $this->conversationService->verifyInput($_POST['title']);
        $content        = $this->conversationService->verifyInput($_POST['content']);
        $senderId       = $this->conversationService->verifyInput($_SESSION['userId']);
        $interlocuteur  = $this->conversationService->verifyInput($_POST['interlocuteur']);
        $conversationId = $this->conversationService->verifyInput($this->conversationService->insertConversation($title));

        $this->messageService->insertMessage($content, $senderId, $interlocuteur, $conversationId);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'created']);
    }

    public function findConversation()
    {
        $userId = $this->conversationService->verifyInput($_SESSION['userId']);
        $key    = $this->conversationService->verifyInput($_POST['search']);
        header('Content-Type: application/json');
        echo json_encode($this->conversationService->searchConversation($userId, $key));
    }
}
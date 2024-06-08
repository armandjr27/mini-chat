<?php
require_once 'Autoload.php';

use controllers\UserController;
use controllers\ConversationController;

session_start();

$userController         = new UserController();
$conversationController = new ConversationController();

define('URL',$_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME'].dirname($_SERVER['SCRIPT_NAME'])."/");

if (isset($_GET['action']))
{
    $params = explode('/',$_GET['action'],FILTER_SANITIZE_URL);
    $action = strtolower($params[0]);

    switch ($action) {
        case 'inscription':
            if (isset($_POST["inscription"])) 
            {
                $userController->signUp();
            }
            break;

        case 'connexion':
            if (isset($_POST["connexion"])) 
            {
                $userController->signIn();
            }
            break;

        case 'deconnexion':
            $userController->signOut();
            break;
        
        case 'users' :
            $userController->showAllUsers();
        break;

        case 'test-connexion' :
            $userController->isAuthenticated();
        break;

        case 'lists-conversation' :
            $conversationController->showConversationByUserId();
        break;

        case 'conversation-by-id' :
            $conversationController->showConversationById();
        break;

        case 'find-conversation' :
            $conversationController->findConversation();
        break;

        case 'new-conversation' :
            $conversationController->newConversation();
        break;

        case 'lists-message' :
            $conversationController->showMessagesOfConversation();
        break;

        case 'send-message' :
            $conversationController->sendMessageToConversation();
        break;
        
        default:
            header('location: http://localhost/mini-chat/views/app.html', 302);
            break;
    }
}
else
{
    header('location: http://localhost/mini-chat/views/app.html', 302);
}
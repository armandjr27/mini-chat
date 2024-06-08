<?php
namespace models;
use models\base\Services;
use models\classes\Message;
use PDO;

class MessageService extends Services
{
    private $table = 'messages';

    public function getMessageById($idMessage)
    {
        $request = $this->db->prepare($this->select($this->table)->where('id_message', '=')->getQuery());
        $request->execute([$idMessage]);
        $messageData = $request->fetch(PDO::FETCH_ASSOC);

        if(!$messageData) return false;
        
        $messageObjet = new Message($messageData['id_message'], $messageData['content'], $messageData['sender_id'], $messageData['receiver_id'], $messageData['conversation_id'], $messageData['created_at']);

        return $messageObjet;
    }

    public function getMessagesByConversationId($conversationId)
    {
        $request = $this->db->prepare($this->select($this->table)->where('conversation_id', '=')->getQuery());
        $request->execute([$conversationId]);
        $messagesData  = $request->fetchAll(PDO::FETCH_ASSOC);
        $messagesObjet = [];
        
        foreach($messagesData as $message)
        {
            $messagesObjet[] = new Message($message['id_message'], $message['content'], $message['sender_id'], $message['receiver_id'], $message['conversation_id'], $message['created_at']);
        }

        return $messagesObjet;
    }

    public function getAllMessages()
    {
        $request       = $this->db->query($this->select($this->table)->getQuery());
        $messagesData  = $request->fetchAll(PDO::FETCH_ASSOC);
        $messagesObjet = [];

        foreach ($messagesData as $message)
        {
            $messagesObjet[] = new Message($message['id_message'], $message['content'], $message['sender_id'], $message['receiver_id'], $message['conversation_id'], $message['created_at']);
        }

        return $messagesObjet;
    }

    public function insertMessage($content, $senderId, $receiverId, $conversationId) 
    {
        $request = $this->db->prepare($this->insert($this->table, ['content', 'sender_id', 'receiver_id', 'conversation_id'], [$content, $senderId, $receiverId, $conversationId])->getQuery());
        $request->execute();
    }

    public function updateMessage($content, $senderId, $receiverId, $conversationId, $idMessage)
    {
        $request = $this->db->prepare($this->update($this->table, ['content', 'sender_id', 'receiver_id', 'conversation_id', 'join_at'], [$content, $senderId, $receiverId, $conversationId, 'NOW()'])->where('id_message', '=')->getQuery());
        $request->execute([$idMessage]);
    }

    public function deleteMessage($idMessage) 
    {
        $request = $this->db->prepare($this->delete($this->table)->where('id_message', '=')->getQuery());
        $request->execute([$idMessage]);
    }
}
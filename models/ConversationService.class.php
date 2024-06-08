<?php
namespace models;
use models\base\Services;
use models\classes\Conversation;
use PDO;

class ConversationService extends Services
{
    private $table = 'conversations';

    public function getConversationById($idConversation)
    {
        $request = $this->db->prepare($this->select($this->table)->where('id_conversation', '=')->getQuery());
        $request->execute([$idConversation]);
        $conversationData = $request->fetch(PDO::FETCH_ASSOC);

        if(!$conversationData) return false;
        
        $conversationObjet = new Conversation($conversationData['id_conversation'], $conversationData['title'], $conversationData['created_at']);

        return $conversationObjet;
    }

    public function getConversationByUserId($userId)
    {
        $request = $this->db->prepare($this->select($this->table, ['DISTINCT(id_conversation)', 'title', 'conversations.created_at', 
        'CASE 
            WHEN sender_id   = ? THEN receiver_id
            WHEN receiver_id = ? THEN sender_id
        END AS idInterlocuteur'])->join('messages', ['id_conversation', 'conversation_id'])->getQuery());
        $request->execute([$userId, $userId]);
        $conversationsData = $request->fetchAll(PDO::FETCH_ASSOC);

        $results     = [];
        $userService = new UserService();
    
        foreach($conversationsData as $conversation)
        {
            $results[] = new Conversation($conversation['id_conversation'], $conversation['title'], $conversation['created_at'], $userService->getUserById($conversation['idInterlocuteur']));
        }

        return $results;
        
    }

    public function getAllConversations()
    {
        $request            = $this->db->query($this->select($this->table)->getQuery());
        $conversationsData  = $request->fetchAll(PDO::FETCH_ASSOC);
        $conversationsObjet = [];

        foreach ($conversationsData as $conversation)
        {
            $conversationsObjet[] = new Conversation($conversation['id_conversation'], $conversation['title'], $conversation['created_at']);
        }

        return $conversationsObjet;
    }

    public function insertConversation($title) 
    {
        $request = $this->db->prepare($this->insert($this->table, ['title'], [$title])->getQuery());
        $request->execute();
        return $this->db->lastInsertId();
    }

    public function updateConversation($title, $idConversation)
    {
        $request = $this->db->prepare($this->update($this->table, ['title', 'created_at'], [$title, 'NOW()'])->where('id_Conversation', '=')->getQuery());
        $request->execute([$idConversation]);
    }

    public function deleteConversation($idConversation) 
    {
        $request = $this->db->prepare($this->delete($this->table)->where('id_conversation', '=')->getQuery());
        $request->execute([$idConversation]);
    }

    public function searchConversation($userId, $key)
    {
        $request = $this->db->prepare($this->select($this->table, ['DISTINCT(id_conversation)', 'title', 'conversations.created_at', 
        'CASE 
            WHEN sender_id   = :sender_id THEN receiver_id
            WHEN receiver_id = :receiver_id THEN sender_id
        END AS idInterlocuteur'])->join('messages', ['id_conversation', 'conversation_id'])->where('title', 'LIKE')->getQuery());
        $request->execute([
            "sender_id"   => $userId,
            "receiver_id" => $userId,
            "key"         => "%{$key}%"
        ]);
        $conversationsData = $request->fetchAll(PDO::FETCH_ASSOC);

        $results     = [];
        $userService = new UserService();
    
        foreach($conversationsData as $conversation)
        {
            $results[] = new Conversation($conversation['id_conversation'], $conversation['title'], $conversation['created_at'], $userService->getUserById($conversation['idInterlocuteur']));
        }

        return $results;
    }
}
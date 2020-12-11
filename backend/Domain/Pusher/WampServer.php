<?php


namespace Domain\Pusher;


use Domain\Pusher\Events\NewMessageEvent;
use Domain\Pusher\Events\UserTypingEvent;
use Domain\Pusher\Listeners\UserTypingNotification;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\Topic;
use Ratchet\Wamp\WampServerInterface;
use Tymon\JWTAuth\Facades\JWTAuth;

class WampServer implements WampServerInterface
{
    protected $subscribedTopics = [];

    //todo: вынести метод из данного класса.
    public static function sentDataToServer(array $data)
    {
        $ip = gethostbyname(config('pusher.zmq_sub_host'));
        $context = new \ZMQContext();
        $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'pusher');
        $socket->connect("tcp://$ip:5555");
        $data = json_encode($data);
        $socket->send($data);
    }

    /**
     * Генерирует уникальный канал для пользователя.
     * @param $user_id
     * @return string
     */
    public static function channelForUser($user_id)
    {
        return sha1(json_encode(['user_id' => $user_id, 'topic_id' => 'onNewMessage']));
    }

    /**
     * @param $jsonData
     */
    public function broadcast($jsonData)
    {
        $aDataToSend = json_decode($jsonData, true);
        $topics = $this->getSubscribedTopics();
        if(isset($topics[$aDataToSend['topic_id']])){
            $topic = $topics[$aDataToSend['topic_id']];
            $topic->broadcast($aDataToSend);
        }
    }

    /**
     * @return array
     */
    public function getSubscribedTopics(): array
    {
        return $this->subscribedTopics;
    }

    public function addSubscribedTopic(Topic $topic)
    {
        $this->subscribedTopics[$topic->getId()] = $topic;
    }

    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible)
    {
        $this->broadcast($event);
    }

    public function onCall(ConnectionInterface $conn, $id, $topic, array $params)
    {
        try {
            $params = json_decode(json_encode($params), true);
            $user_id = $this->getUserIdFronToken($params['token']);
            if($user_id) {
                if($params['event'] === 'user.typing') {
                    event(new UserTypingEvent($params['userId'], $params['chatId']));
                } else if($params['event'] === 'new.message') {
                    event(new NewMessageEvent($params['body'], $user_id, $params['chatId'], $params['attachments'],
                        $params['replyOnMessageId'] ?? null,
                        $params['forwardFromChatId'] ?? null,
                        $params['toUserId'] ?? null
                    ));
                }
            }
        } catch (Exception $ex) {
            Log::error($ex);
        }
    }

    public function onSubscribe(ConnectionInterface $conn, $topic)
    {
        $this->addSubscribedTopic($topic);
    }

    public function onUnSubscribe(ConnectionInterface $conn, $topic) {}

    public function onOpen(ConnectionInterface $conn){}
    public function onClose(ConnectionInterface $conn) {}
    public function onError(ConnectionInterface $conn, \Exception $e) {}
    public function onMessage() {}

    private function getUserIdFronToken($token) {
        JWTAuth::setToken($token);
        $token = JWTAuth::getToken();
        $decode = JWTAuth::decode($token);
        $decode = json_decode(json_encode($decode), true);
        return $decode['sub'];
    }
}

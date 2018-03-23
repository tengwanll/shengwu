<?php
/**
 * Created by PhpStorm.
 * User: 31726
 * Date: 2018/2/26
 * Time: 11:14
 */

namespace Mirror\ApiBundle\Controller;


use Mirror\ApiBundle\Common\Constant;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/test")
 * Class TestController
 * @package Mirror\ApiBundle\Controller
 */
class TestController extends BaseController
{
    /**
     * @Route("/consumer")
     * @Method("GET")
     * @return Response
     */
    public function consumer(){
        $exchange = 'router';
        $queue = 'msgs';
        $consumerTag = 'consumer';

        $connection = new AMQPStreamConnection(Constant::$rabbit_host, Constant::$rabbit_port, Constant::$rabbit_user, Constant::$rabbit_pass, Constant::$rabbit_vhost);
        $channel = $connection->channel();

        /*
            The following code is the same both in the consumer and the producer.
            In this way we are sure we always have a queue to consume from and an
                exchange where to publish messages.
        */

        /*
            name: $queue
            passive: false
            durable: true // the queue will survive server restarts
            exclusive: false // the queue can be accessed in other channels
            auto_delete: false //the queue won't be deleted once the channel is closed.
        */
        $channel->queue_declare($queue, false, true, false, false);

        /*
            name: $exchange
            type: direct
            passive: false
            durable: true // the exchange will survive server restarts
            auto_delete: false //the exchange won't be deleted once the channel is closed.
        */

        $channel->exchange_declare($exchange, 'direct', false, true, false);

        $channel->queue_bind($queue, $exchange);

        /**
         * @param \PhpAmqpLib\Message\AMQPMessage $message
         */
        function process_message($message)
        {
            echo "\n--------\n";
            echo $message->body;
            echo "\n--------\n";

            //确认收到消息,必须要
            $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);

            // Send a message with the string "quit" to cancel the consumer.
            if ($message->body === 'quit') {
                $message->delivery_info['channel']->basic_cancel($message->delivery_info['consumer_tag']);
            }
        }

        /*
            queue: Queue from where to get the messages
            consumer_tag: Consumer identifier
            no_local: Don't receive messages published by this consumer.
            no_ack: Tells the server if the consumer will acknowledge the messages.
            exclusive: Request exclusive consumer access, meaning only this consumer can access the queue
            nowait:
            callback: A PHP Callback
        */

        $channel->basic_consume($queue, $consumerTag, false, false, false, false, 'process_message');

        /**
         * @param \PhpAmqpLib\Channel\AMQPChannel $channel
         * @param \PhpAmqpLib\Connection\AbstractConnection $connection
         */
        function shutdown($channel, $connection)
        {
            $channel->close();
            $connection->close();
        }

        register_shutdown_function('shutdown', $channel, $connection);

// Loop as long as the channel has callbacks registered
        while (count($channel->callbacks)) {
            $channel->wait();
        }
        return new Response(1);
    }

    /**
     * @Route("/publish/{message}")
     * @Method("GET")
     * @param $message
     * @return Response
     */
    public function publisher($message){
        $exchange = 'router';
        $queue = 'msgs';

        $connection = new AMQPStreamConnection(Constant::$rabbit_host, Constant::$rabbit_port, Constant::$rabbit_user, Constant::$rabbit_pass, Constant::$rabbit_vhost);
        $channel = $connection->channel();

        /*
            The following code is the same both in the consumer and the producer.
            In this way we are sure we always have a queue to consume from and an
                exchange where to publish messages.
        */

        /*
            name: $queue
            passive: false
            durable: true // the queue will survive server restarts
            exclusive: false // the queue can be accessed in other channels
            auto_delete: false //the queue won't be deleted once the channel is closed.
        */
        $channel->queue_declare($queue, false, true, false, false);

        /*
            name: $exchange
            type: direct
            passive: false
            durable: true // the exchange will survive server restarts
            auto_delete: false //the exchange won't be deleted once the channel is closed.
        */

        $channel->exchange_declare($exchange, 'direct', false, true, false);

        $channel->queue_bind($queue, $exchange);

        $messageBody = $message;
        $message = new AMQPMessage($messageBody, array('content_type' => 'text/plain', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
        $channel->basic_publish($message, $exchange);

        $channel->close();
        $connection->close();
        return new Response(1);
    }

    /**
     * @Route("/xun/search/name")
     * @return Response
     */
    public function xunSearch($name){
        try {
            $xs=new \XS('biology');
            $search=$xs->getSearch();
            $data=$search->search($name);
            var_dump($data);
        } catch (\XSException $e) {
            var_dump($e->getMessage());
        }
        return new Response(1);
    }

    /**
     * @Route("/xun/update")
     * @return Response
     */
    public function xunUpdate(){
        try {
            echo "开始处理\r\n";
            $xs=new \XS('biology');
            $index=$xs->getIndex();
            $lists=$this->get('biology_service')->getList();
            foreach($lists->result['list'] as $key=>$list){
                echo "开始处理第{$key}条\r\n";
                $data=array(
                    'id'=>$list['id'],
                    'name'=>$list['name'],
                    'english_name'=>$list['english_name'],
                    'sort'=>$list['sort'],
                    'kind'=>$list['kind'],
                    'check_gene'=>$list['check_gene'],
                    'disease'=>$list['disease']
                );
                $doc=new \XSDocument($data);
                $index->update($doc);
            }
            $index->flushIndex();
        } catch (\XSException $e) {
            var_dump($e->getMessage());
        }
        return new Response(0);
    }
}
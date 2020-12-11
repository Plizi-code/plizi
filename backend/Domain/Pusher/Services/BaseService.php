<?php


namespace Domain\Pusher\Services;


use Illuminate\Contracts\Events\Dispatcher;
use Psr\Log\LoggerInterface;

class BaseService
{

    /**
     * @var Dispatcher
     */
    protected $dispatcher;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * BaseService constructor.
     * @param Dispatcher $dispatcher
     * @param LoggerInterface $logger
     */
    public function __construct(Dispatcher $dispatcher, LoggerInterface $logger)
    {
        $this->dispatcher = $dispatcher;
        $this->logger = $logger;
    }
}

<?php

declare(strict_types=1);

namespace App\Controllers\Inventory;

use App\Controllers\Inventory\UploadJob;
use SlmQueue\Queue\QueueInterface;

class UploadQueue
{
    protected $queue;

    public function __construct(QueueInterface $queue)
    {
        echo "<pre> test";
        print_r($queue);
        exit;
        $this->queue = $queue;
    }

    public function fooAction()
    {
        // Do some work

        $job = new UploadJob;
        $job->setContent(array(
            'to'      => 'john@doe.com',
            'subject' => 'Just hi',
            'message' => 'Hi, I want to say hi!'
        ));

        $this->queue->push($job, ['delay' => 60]);
    }
}

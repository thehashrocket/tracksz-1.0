<?php

declare(strict_types=1);

namespace App\Controllers\Inventory;

use SlmQueue\Job\AbstractJob;

class UploadJob extends AbstractJob
{
    public function execute()
    {
        $payload = $this->getContent();
        echo "<pre> inside jobs";
        print_r($payload);
        exit;

        $to      = $payload['to'];
        $subject = $payload['subject'];
        $message = $payload['message'];

        mail($to, $subject, $message);
    }
}

<?php


namespace App\Services;

use App\Models\EmailLog;
use Swift_Message;

class EmailLoggingService
{
    public function log(Swift_Message $message)
    {
        EmailLog::create([
            'swift_identifier' => $message->getId(),
            'body' => $message->getBody(),
            'bcc' => $message->getBcc(),
            'cc' => $message->getCc(),
            'charset' => $message->getCharset(),
            'content_type' => $message->getContentType(),
            'date' => $message->getDate(),
            'description' => $message->getDescription(),
            'format' => $message->getFormat(),
            'from' => $message->getFrom(),
            'reply_to' => $message->getReplyTo(),
            'return_path' => $message->getReturnPath(),
            'sender' => $message->getSender(),
            'subject' => $message->getSubject(),
        ]);
    }
}
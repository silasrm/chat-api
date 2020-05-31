<?php

declare(strict_types=1);

namespace Silasrm\ChatApi;

use Exception;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Notifications\Notification;
use Silasrm\ChatApi\Exceptions\CouldNotSendNotification;

final class ChatApiChannel
{
    /** @var \Silasrm\ChatApi\ChatApi The SDK client instance. */
    private $chatApi;

    /**
     * Create a new Chat API channel instance.
     *
     * @param \Silasrm\ChatApi\ChatApi $chatApi
     * @return void
     */
    public function __construct(ChatApi $chatApi)
    {
        $this->chatApi = $chatApi;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     * @return void
     *
     * @throws \Silasrm\ChatApi\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification): void
    {
        /** @var \Silasrm\ChatApi\ChatApiMessage $message */
        $message = $notification->toChatApi($notifiable);

        $to = $message->getChannel() ?: $notifiable->routeNotificationFor('ChatApi');
        $to = $to ?: $this->chatApi->getDefaultChannel();
        if ($to === null) {
            throw CouldNotSendNotification::missingTo();
        }

        try {
            $this->sendMessage($to, $message);
        } catch (Exception $exception) {
            throw CouldNotSendNotification::couldNotCommunicateWithChatApi($exception);
        }
    }

    /**
     * @param string $to
     * @param \Silasrm\ChatApi\ChatApiMessage $message
     * @return void
     */
    private function sendMessage(string $to, ChatApiMessage $message): void
    {
        $this->chatApi->sendMessage($to, $message->toArray());
    }
}

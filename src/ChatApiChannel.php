<?php

declare(strict_types=1);

namespace Silasrm\ChatApi;

use Exception;
use Illuminate\Notifications\Notification;
use Silasrm\ChatApi\Exceptions\CouldNotSendNotification;

final class ChatApiChannel
{
    /** @var ChatApi The SDK client instance. */
    private $chatApi;

    /**
     * Create a new Chat API channel instance.
     *
     * @param ChatApi $chatApi
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
     * @param Notification $notification
     * @return void
     *
     * @throws CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification): void
    {
        /** @var ChatApiMessage $message */
        $message = $notification->toChatApi($notifiable);

        $to = $message->getChannel() ?: $notifiable->routeNotificationFor('ChatApi');
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
     * @param ChatApiMessage $message
     * @return void
     */
    private function sendMessage(string $to, ChatApiMessage $message): void
    {
        $this->chatApi->sendMessage($to, $message->toArray());
    }
}

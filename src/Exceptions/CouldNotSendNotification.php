<?php

declare(strict_types=1);

namespace Silasrm\ChatApi\Exceptions;

use Exception;
use RuntimeException;

final class CouldNotSendNotification extends RuntimeException
{
    /**
     * Thrown when channel identifier is missing.
     *
     * @return static
     */
    public static function missingTo(): self
    {
        return new static('Chat API notification was not sent. Channel identifier is missing.');
    }

    /**
     * Thrown when user or app access token is missing.
     *
     * @return static
     */
    public static function missingFrom(): self
    {
        return new static('Chat API notification was not sent. Access token is missing.');
    }

    /**
     * Thrown when we're unable to communicate with Chat API.
     *
     * @param Exception $exception
     * @return static
     */
    public static function couldNotCommunicateWithChatApi(Exception $exception): self
    {
        return new static("The communication with Chat API failed. Reason: {$exception->getMessage()}");
    }
}

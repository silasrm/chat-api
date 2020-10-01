<?php

declare(strict_types=1);

namespace Silasrm\ChatApi;

use Mike4ip\ChatApi as ChatApiSDK;

/**
 * Class ChatApi
 * @package Silasrm\ChatApi
 */
final class ChatApi extends ChatApiSDK
{
//    /** @var ChatApiSDK */
//    private $sdk;
//
//    /** @var string */
//    private $url;
//
//    /** @var string */
//    private $token;
//
//    /**
//     * @param ChatApiSDK $sdk
//     * @param string $url
//     * @param string $token
//     * @return void
//     */
//    public function __construct(ChatApiSDK $sdk, string $url, string $token)
//    {
//        $this->sdk = $sdk;
//        $this->url = rtrim($url, '/');
//        $this->token = $token;
//    }

    /**
     * Set Chat API Url.
     *
     * @param $url
     * @return ChatApi
     */
    public function setUrl($url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Returns Chat API Url.
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Set Chat API Token.
     *
     * @param $token
     * @return ChatApi
     */
    public function setToken($token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Returns Chat API token.
     *
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * Send a message.
     *
     * @param string $to
     * @param array $message
     * @return void
     */
    public function send(string $to, array $message): void
    {
        if (isset($message['instance'])) {
            $this->loadInstance($message['instance']);
        }

        if (isset($message['body']) && !empty($message['body'])) {
            $this->sendPhoneMessage($to, $message['body']);
        }

        if (isset($message['attachments']) && !empty($message['attachments'])) {
            foreach ($message['attachments'] as $attachment) {
                if (isset($attachment['url']) && !empty($attachment['url'])) {
                    $pathinfo = pathinfo($attachment['url']);
                    $body = $attachment['url'];
                    $filename = $pathinfo['basename'];
                } elseif (isset($attachment['path']) && !empty($attachment['path'])) {
                    $pathinfo = pathinfo($attachment['path']);
                    $body = 'data:' . mime_content_type($attachment['path']) . ';base64,' . base64_encode(file_get_contents($attachment['path']));
                    $filename = $pathinfo['basename'];
                }

                if (isset($attachment['filename']) && !empty($attachment['filename'])) {
                    $filename = $attachment['filename'];
                }

                $this->sendFile($to, $body, $filename, $attachment['caption'] ?? null);
            }
        }

        if (isset($message['links']) && !empty($message['links'])) {
            foreach ($message['links'] as $link) {
                if (strpos($link['previewBase64'], 'data:image/') === false) {
                    $pathinfo = pathinfo($link['previewBase64']);
                    $previewBase64 = 'data:image/' . $pathinfo['extension'] . ';base64,' . base64_encode(file_get_contents($link['previewBase64']));
                } else {
                    $previewBase64 = $link['previewBase64'];
                }

                $this->sendLink($to, $link['url'], $link['title'] ?? $link['url'], $previewBase64, $link['description'] ?? null);
            }
        }
    }

    /**
     * Send message to phone number
     * @param string $chat
     * @param string $text
     * @return boolean
     */
    public function sendPhoneMessage($chat, $text)
    {
        return json_decode($this->query('sendMessage', ['phone' => $chat, 'body' => $text], 'POST'), true)['sent'];
    }

    /**
     * Send file to chat or phone
     * @param string $channel
     * @param string $body
     * @param string $filename
     * @param string|null $caption
     * @param string $channelType phone or chatId
     * @return boolean
     */
    public function sendFile($channel, $body, $filename, $caption = null, $channelType = 'phone')
    {
        return json_decode($this->query('sendFile', [$channelType => $channel, 'body' => $body, 'filename' => $filename, 'caption' => $caption], 'POST'), true)['sent'];
    }

    /**
     * Send link to chat or phone
     * @param string $channel
     * @param string $body
     * @param string $title
     * @param string $previewBase64
     * @param string|null $description
     * @param string $channelType phone or chatId
     * @return mixed
     */
    public function sendLink($channel, $body, $title, $previewBase64, $description = null, $channelType = 'phone')
    {
        return json_decode($this->query('sendLink', [$channelType => $channel, 'body' => $body, 'title' => $title, 'previewBase64' => $previewBase64, 'description' => $description], 'POST'), true)['sent'];
    }

    /**
     * @param $instance
     * @return ChatApi
     */
    protected function loadInstance($instance): self
    {
        if (is_array($instance) && count($instance) === 2 && isset($instance['url'], $instance['token'])
            && !empty($instance['url']) && !empty($instance['token'])) {
            $this->url = $instance['url'];
            $this->token = $instance['token'];
        }

        return $this;
    }
}

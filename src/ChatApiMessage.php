<?php

declare(strict_types=1);

namespace Silasrm\ChatApi;

class ChatApiMessage
{
    /** @var string|null ChatApi channel is a phone number). */
    protected $channel = null;

    /** @var string The text content of the message. */
    protected $content;

    /** @var ChatApiAttachment[] Attachments of the message. */
    protected $attachments = [];

    /** @var ChatApiLink[] Links of the message. */
    protected $links = [];

    /**
     * Create a new instance of ChatApiMessage.
     *
     * @param string $content
     * @return static
     */
    public static function create(string $content = ''): self
    {
        return new static($content);
    }

    /**
     * Create a new instance of ChatApiMessage.
     *
     * @param string $content
     */
    public function __construct(string $content = '')
    {
        $this->content($content);
    }

    public function getChannel(): ?string
    {
        return $this->channel;
    }

    /**
     * Set the ChatApi channel the message should be sent to.
     *
     * @param string $channel
     * @return $this
     */
    public function to(string $channel): self
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * Set the content of the ChatApi message.
     * Supports GitHub flavoured markdown.
     *
     * @param string $content
     * @return $this
     */
    public function content(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Add an link to the message.
     *
     * @param array|ChatApiLink $link
     * @return $this
     */
    public function link($link): self
    {
        if (!($link instanceof ChatApiLink)) {
            $link = new ChatApiLink($link);
        }

        $this->links[] = $link;

        return $this;
    }

    /**
     * Add an attachment to the message.
     *
     * @param array|ChatApiAttachment $attachment
     * @return $this
     */
    public function attachment($attachment): self
    {
        if (!($attachment instanceof ChatApiAttachment)) {
            $attachment = new ChatApiAttachment($attachment);
        }

        $this->attachments[] = $attachment;

        return $this;
    }

    /**
     * Add multiple attachments to the message.
     *
     * @param array|ChatApiAttachment[] $attachments
     * @return $this
     */
    public function attachments(array $attachments): self
    {
        foreach ($attachments as $attachment) {
            $this->attachment($attachment);
        }

        return $this;
    }

    /**
     * Get an array representation of the ChatApiMessage.
     *
     * @return array
     */
    public function toArray(): array
    {
        $attachments = [];
        foreach ($this->attachments as $attachment) {
            $attachments[] = $attachment->toArray();
        }

        $links = [];
        foreach ($this->links as $link) {
            $links[] = $link->toArray();
        }

        return array_filter([
            'body' => $this->content,
            'channel' => $this->channel,
            'attachments' => $attachments,
            'links' => $links,
        ]);
    }
}

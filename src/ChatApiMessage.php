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
     * @var array
     */
    protected $instance = [];

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
     * @param array|null $instance
     */
    public function __construct(string $content = '', array $instance = null)
    {
        $this->content($content);

        if (!empty($instance) && is_array($instance)) {
            $this->onInstance($instance);
        }
    }

    public function getChannel(): ?string
    {
        return $this->channel;
    }

    /**
     * @param array|null $instance
     * @return ChatApiMessage
     */
    public function onInstance(array $instance = null): self
    {
        $this->instance = $instance;

        return $this;
    }

    /**
     * Set the ChatApi channel the message should be sent to.
     *
     * @param string $channel
     * @return ChatApiMessage
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
     * @return ChatApiMessage
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
     * @return ChatApiMessage
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
     * Add multiple links to the message.
     *
     * @param array|ChatApiLink[] $links
     * @return ChatApiMessage
     */
    public function links(array $links): self
    {
        foreach ($links as $link) {
            $this->link($link);
        }

        return $this;
    }

    /**
     * Add an attachment to the message.
     *
     * @param array|ChatApiAttachment $attachment
     * @return ChatApiMessage
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
     * @return ChatApiMessage
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
            'instance' => $this->instance,
        ]);
    }
}

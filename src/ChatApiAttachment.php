<?php

declare(strict_types=1);

namespace Silasrm\ChatApi;

use Illuminate\Support\Str;
use InvalidArgumentException;

class ChatApiAttachment
{
    /** @var string|null Name of this file. If empty, use the original name of file. */
    protected $filename;

    /** @var string|null The remote url of file. */
    protected $url;

    /** @var string|null The local path of file. */
    protected $path;

    /** @var string|null The text caption for this attachment. */
    protected $caption;

    /**
     * ChatApiAttachment constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->setPropertiesFromArray($data);
    }

    /**
     * Create a new instance of ChatApiAttachment.
     *
     * @param array $data
     * @return ChatApiAttachment
     */
    public static function create(array $data = [])
    {
        return new self($data);
    }

    /**
     * @param string $caption
     * @return ChatApiAttachment
     */
    public function caption(string $caption): self
    {
        $this->caption = $caption;

        return $this;
    }

    /**
     * @param string $filename
     * @return ChatApiAttachment
     */
    public function filename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * @param string $url
     * @return ChatApiAttachment
     */
    public function url(string $url): self
    {
        if (strpos($url, 'http://') === false && strpos($url, 'https://') === false) {
            throw new InvalidArgumentException(sprintf('URL invalid: %s', $url));
        }

        $this->url = $url;

        return $this;
    }

    /**
     * @param string $path
     * @return ChatApiAttachment
     */
    public function path(string $path): self
    {
        if (!file_exists($this->path)) {
            throw new InvalidArgumentException(sprintf('File path dont exists: %s', $this->path));
        }

        if (!is_file($this->path) || is_dir($this->path)) {
            throw new InvalidArgumentException(sprintf('File path is invalid: %s', $this->path));
        }

        $this->path = $path;

        return $this;
    }

    /**
     * Get an array representation of the ChatApiAttachment.
     *
     * @return array
     */
    public function toArray(): array
    {
        return array_filter([
            'filename' => $this->filename,
            'url' => $this->url,
            'path' => $this->path,
            'caption' => $this->caption,
        ]);
    }

    /**
     * Set attachment data from array.
     *
     * @param array $data
     * @return void
     */
    private function setPropertiesFromArray(array $data): void
    {
        foreach ($data as $key => $value) {
            $methodName = Str::camel($key);

            if (!method_exists($this, $methodName)) {
                continue;
            }

            $this->{$methodName}($value);
        }
    }
}

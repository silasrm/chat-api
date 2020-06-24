<?php

declare(strict_types=1);

namespace Silasrm\ChatApi;

use Illuminate\Support\Str;
use InvalidArgumentException;

class ChatApiLink
{
    /** @var string|null The title of link. Required. */
    protected $title;

    /** @var string|null The link url. Required. */
    protected $url;

    /** @var string|null The image url/path of link preview. Required. */
    protected $previewImage;

    /** @var string|null The description for this link. */
    protected $description;

    /**
     * ChatApiLink constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->setPropertiesFromArray($data);
    }

    /**
     * Create a new instance of ChatApiLink.
     *
     * @param array $data
     * @return ChatApiLink
     */
    public static function create(array $data = [])
    {
        return new self($data);
    }

    /**
     * @param string $title
     * @return ChatApiLink
     */
    public function title(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param string $url
     * @return ChatApiLink
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
     * @param string $previewImage
     * @return ChatApiLink
     */
    public function previewImage(string $previewImage): self
    {
        if (strpos($previewImage, 'http://') === false
            && strpos($previewImage, 'https://') === false
            && strpos($previewImage, 'base64,') === false
            && !file_exists($previewImage)) {
            throw new InvalidArgumentException(sprintf('Preview image invalid: %s', $previewImage));
        }

        $this->previewImage = $previewImage;

        return $this;
    }

    /**
     * @param string $description
     * @return ChatApiLink
     */
    public function description(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get an array representation of the ChatApiLink.
     *
     * @return array
     */
    public function toArray(): array
    {
        return array_filter([
            'title' => $this->title,
            'url' => $this->url,
            'previewBase64' => $this->previewImage,
            'description' => $this->description,
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

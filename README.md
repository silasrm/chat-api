# Chat API Laravel Notifications Channel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/rocket-chat.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/rocket-chat)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

## Introduction

This package makes it easy to send notifications using [Chat API](https://chat-api.com/) with Laravel 5.6+. 

## Contents

- [Installation](#installation)
	- [Setting up the Chat API service](#setting-up-the-chat-api-service)
- [Usage](#usage)
	- [Available Message methods](#available-message-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [Change log](#changelog)
- [License](#license)

## Installation

You can install the package via composer:

```shell script
$ composer require silasrm/chat-api
```

### Setting up the Chat API service

In order to send message to Whatsapp using Chat API, you need to authenticate your number using [QR Code](https://chat-api.com/en/sdk/php.html#/instance/getQRCode) on API.

Add your Chat API url and token to your `config/services.php`:

```php
// config/services.php
...
'chatapi' => [
     // Like: https://euXXXX.chat-api.com/instanceYYYYY/
    'url' => env('CHATAPI_URL'),
    'token' => env('CHATAPI_TOKEN'),
],
...
```

## Usage

You can use the channel in your `via()` method inside the notification:

```php
use Illuminate\Notifications\Notification;
use Silasrm\ChatApi\ChatApiMessage;
use Silasrm\ChatApi\ChatApiChannel;

class OrderCreated extends Notification
{
    public function via($notifiable): array
    {
        return [
            ChatApiChannel::class,
        ];
    }

    public function toRocketChat($notifiable): ChatApiMessage
    {
        return ChatApiMessage::create('John Doe create a new order with value US$ 50.0')
            ->to('NNNNNNNNNNNNNN'); // Phone number with country code
    }
}
```

In order to let your notification know which Chat API phone number you are targeting, add the `routeNotificationForChatApi` method to your Notifiable model:

```php
public function routeNotificationForChatApi(): string
{
    return $this->phone;
}
```

### Available methods

`to()`: Specifies the phone number to send the notification to (overridden by `routeNotificationForChatApi` if empty).

`content()`: Sets a content of the notification message. Supports pure text, UTF-8 or UTF-16 string with emoji.

`attachment()`: This will add an single attachment.

`attachments()`: This will add multiple attachments.

`link()`: This will add an single link.

`links()`: This will add multiple links.

### Adding Attachment

There are several ways to add one ore more attachments to a message

```php
public function toChatApi($notifiable)
{
    return ChatApiMessage::create('Test message')
        ->to('NNNNNNNNNNNNNN') // Phone number with country code
        ->attachments([
            // url (for remote) or path (for local), file
            ChatApiAttachment::create()->url('test'),
            ChatApiAttachment::create(['url' => 'test']),
            new ChatApiAttachment(['url' => 'test']),
            ['url' => 'test']
        ]);
}
```

#### Available methods

`caption()`: The text caption for this attachment.

`filename()`: Name of this file. If empty, use the original name of file.

```php
[
    [
        'caption' => 'Caption of file',
        'filename' => 'payment.xlsx',
    ]
];   
```

### Adding Link

There are several ways to add one ore more links to a message

```php
public function toChatApi($notifiable)
{
    return ChatApiMessage::create('Test message')
        ->to('NNNNNNNNNNNNNN') // Phone number with country code
        ->attachments([
            ChatApiLink::create()
                ->url('https://wikimedia.org/nature')
                ->title('All about Nature')
                ->previewImage('https://upload.wikimedia.org/wikipedia/ru/3/33/NatureCover2001.jpg'),
            ChatApiLink::create([
                'url' => 'https://wikimedia.org/nature',
                'title' => 'All about Nature',
                'previewImage' => 'https://upload.wikimedia.org/wikipedia/ru/3/33/NatureCover2001.jpg',
            ]),
            new ChatApiLink([
                'url' => 'https://wikimedia.org/nature',
                'title' => 'All about Nature',
                'previewImage' => 'https://upload.wikimedia.org/wikipedia/ru/3/33/NatureCover2001.jpg',
            ]),
            [
                'url' => 'https://wikimedia.org/nature',
                'title' => 'All about Nature',
                'previewImage' => 'https://upload.wikimedia.org/wikipedia/ru/3/33/NatureCover2001.jpg',
            ]
        ]);
}
```

#### Available methods

`title()`: The title of link. Required.

`previewImage()`: The image url/path of link preview. Required.

`description()`: The description for this link.

```php
[
    [
        'title' => 'All about Nature',
        'previewImage' => 'https://upload.wikimedia.org/wikipedia/ru/3/33/NatureCover2001.jpg',
        'description' => 'See that!',
    ]
];   
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

```shell script
$ vendor/bin/phpunit
```

## Security

If you discover any security related issues, please email silasrm@gmail.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Silas Ribas]

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[Silas Ribas]: http://silasribas.com.br

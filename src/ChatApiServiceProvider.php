<?php

declare(strict_types=1);

namespace Silasrm\ChatApi;

use Mike4ip\ChatApi as ChatApiSDK;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

final class ChatApiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->app
            ->when(ChatApiChannel::class)
            ->needs(ChatApi::class)
            ->give(function () {
                return new ChatApi(
                    new ChatApiSDK(Config::get('services.chatapi.token'), Config::get('services.chatapi.url')),
                    Config::get('services.chatapi.url'),
                    Config::get('services.chatapi.token')
                );
            });
    }
}

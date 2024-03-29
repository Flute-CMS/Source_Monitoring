<?php

namespace Flute\Modules\Monitoring\ServiceProviders;

use Flute\Core\Support\ModuleServiceProvider;
use Flute\Modules\Monitoring\ServiceProviders\Extensions\WidgetExtension;

class MonitoringServiceProvider extends ModuleServiceProvider
{
    public array $extensions = [
        WidgetExtension::class,
    ];

    public function boot(\DI\Container $container): void
    {
        $this->loadTranslations();
    }

    public function register(\DI\Container $containerBuilder): void
    {
    }
}
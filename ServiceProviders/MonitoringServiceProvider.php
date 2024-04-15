<?php

namespace Flute\Modules\Monitoring\ServiceProviders;

use Flute\Core\Support\ModuleServiceProvider;
use Flute\Modules\Monitoring\ServiceProviders\Extensions\WidgetExtension;
use Flute\Modules\Monitoring\ServiceProviders\Extensions\RoutesExtension;

class MonitoringServiceProvider extends ModuleServiceProvider
{
    public array $extensions = [
        WidgetExtension::class,
        RoutesExtension::class
    ];

    public function boot(\DI\Container $container): void
    {
        $this->loadTranslations();
    }

    public function register(\DI\Container $containerBuilder): void
    {
    }
}
<?php

namespace Flute\Modules\Source_Monitoring\src\ServiceProviders;

use Flute\Core\Support\ModuleServiceProvider;
use Flute\Modules\Source_Monitoring\src\ServiceProviders\Extensions\WidgetExtension;
use Flute\Modules\Source_Monitoring\src\ServiceProviders\Extensions\RoutesExtension;

class MonitoringServiceProvider extends ModuleServiceProvider
{
    public array $extensions = [
        WidgetExtension::class,
        RoutesExtension::class
    ];

    public function boot(\DI\Container $container): void
    {
        $this->loadTranslations();
        
        $this->setUpdateChannel('Flute-CMS', 'Source_Monitoring');
    }

    public function register(\DI\Container $containerBuilder): void
    {
    }
}
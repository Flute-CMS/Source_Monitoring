<?php

namespace Flute\Modules\Monitoring\ServiceProviders\Extensions;

use Flute\Modules\Monitoring\Widgets\ServersWidget;

class WidgetExtension implements \Flute\Core\Contracts\ModuleExtensionInterface
{
    public function register() : void
    {
        widgets()->register(new ServersWidget());
    }
}
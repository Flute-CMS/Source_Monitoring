<?php

namespace Flute\Modules\Monitoring\ServiceProviders\Extensions;

use Flute\Modules\Monitoring\Widgets\ServersWidget;
use Flute\Core\Contracts\ModuleExtensionInterface;

class WidgetExtension implements ModuleExtensionInterface
{
    public function register() : void
    {
        widgets()->register(new ServersWidget());
    }
}
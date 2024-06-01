<?php

namespace Flute\Modules\Source_Monitoring\src\ServiceProviders\Extensions;

use Flute\Modules\Source_Monitoring\src\Widgets\ServersWidget;
use Flute\Core\Contracts\ModuleExtensionInterface;

class WidgetExtension implements ModuleExtensionInterface
{
    public function register() : void
    {
        widgets()->register(new ServersWidget());
    }
}
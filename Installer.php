<?php

namespace Flute\Modules\Monitoring;

use Flute\Core\Support\AbstractModuleInstaller;
use Flute\Modules\Monitoring\Widgets\ServersWidget;

class Installer extends AbstractModuleInstaller
{
    public function install(\Flute\Core\Modules\ModuleInformation &$module) : bool
    {
        return true;
    }

    public function uninstall(\Flute\Core\Modules\ModuleInformation &$module) : bool
    {
        widgets()->unregister(ServersWidget::class);
        return true;
    }
}
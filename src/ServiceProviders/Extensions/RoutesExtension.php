<?php

namespace Flute\Modules\Source_Monitoring\src\ServiceProviders\Extensions;

use Flute\Modules\Source_Monitoring\src\Widgets\ServersWidget;
use Flute\Modules\Source_Monitoring\src\Http\Controllers\Api\ApiInfoController;
use Flute\Core\Contracts\ModuleExtensionInterface;
use Flute\Core\Router\RouteGroup;

class RoutesExtension implements ModuleExtensionInterface 
{
    public function register() : void
    {
        router()->group(function (RouteGroup $routeGroup) {
            $routeGroup->group(function (RouteGroup $apiRouteGroup) {
                $apiRouteGroup->get('/info', [ApiInfoController::class, 'getDetailInfo']);
            }, '/api');
        }, 'source_monitoring');
    }
}
<?php

namespace Flute\Modules\Monitoring\ServiceProviders\Extensions;

use Flute\Modules\Monitoring\Widgets\ServersWidget;
use Flute\Modules\Monitoring\Http\Controllers\Api\ApiInfoController;
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
        }, 'monitoring');
    }
}
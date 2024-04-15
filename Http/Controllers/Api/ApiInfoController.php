<?php

namespace Flute\Modules\Monitoring\Http\Controllers\Api;

use Flute\Core\Support\AbstractController;
use Flute\Core\Support\FluteRequest;

class ApiInfoController extends AbstractController
{
    public function getDetailInfo(FluteRequest $request) {
        return "{'test':'test'}";
    }
}
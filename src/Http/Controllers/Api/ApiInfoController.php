<?php

namespace Flute\Modules\Source_Monitoring\src\Http\Controllers\Api;

use Flute\Core\Support\AbstractController;
use Flute\Core\Support\FluteRequest;

class ApiInfoController extends AbstractController
{
    public function getDetailInfo(FluteRequest $request) {
        return "{'test':'test'}";
    }
}
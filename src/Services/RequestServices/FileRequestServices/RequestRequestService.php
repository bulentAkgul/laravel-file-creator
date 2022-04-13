<?php

namespace Bakgul\FileCreator\Services\RequestServices\FileRequestServices;

use Bakgul\FileCreator\Services\RequestServices\SrcRequestService;
use Bakgul\FileCreator\Tasks\ModifyFilePointer;

class RequestRequestService extends SrcRequestService
{
    public function __invoke(array $request): array
    {
        $request['attr']['path'] = ModifyFilePointer::path($request);
        $request['map']['namespace'] = ModifyFilePointer::namespace($request);
        
        return $request;
    }
}

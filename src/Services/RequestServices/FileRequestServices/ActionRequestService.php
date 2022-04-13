<?php

namespace Bakgul\FileCreator\Services\RequestServices\FileRequestServices;

use Bakgul\Kernel\Helpers\Text;
use Bakgul\FileCreator\Services\RequestServices\SrcRequestService;

class ActionRequestService extends SrcRequestService
{
    public function __invoke(array $request): array
    {
        $request['map']['static'] = $request['attr']['variation'] == 'static' ? ' static ' : ' ';
        $request['map']['method'] = $request['attr']['variation'] == 'static' ? '_' : '__invoke';

        return $request;
    }
}
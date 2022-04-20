<?php

namespace Bakgul\FileCreator\Services\RequestServices\FileRequestServices;

use Bakgul\Kernel\Helpers\Text;
use Bakgul\FileCreator\Services\RequestServices\SrcRequestService;
use Bakgul\Kernel\Helpers\Arry;
use Bakgul\Kernel\Helpers\Convention;
use Bakgul\Kernel\Helpers\Path;
use Bakgul\Kernel\Helpers\Settings;
use Bakgul\Kernel\Tasks\ConvertCase;
use Illuminate\Support\Str;

class LivewireRequestService extends SrcRequestService
{
    public function __invoke(array $request): array
    {
        $request['attr']['path'] = $this->modifyPath($request);
        $request['map']['view'] = $this->setView($request);

        return $request;
    }

    private function modifyPath(array $request): string
    {
        $variation = Arry::get($request['attr']['subs'], 0);
        $path = $request['attr']['path'];

        if ($this->isModifyable($variation)) {
            $folder = ConvertCase::pascal($variation);
            $path = str_replace(
                Text::append($folder),
                Text::append(Convention::namespace($folder)),
                $path
            );
        }

        return $path;
    }

    private function setView(array $request): string
    {
        $variation = Arry::get($request['attr']['subs'], 0);
        $convention = Settings::resources('blade.convention');

        if ($this->isModifyable($variation)) {
            $request['attr']['subs'][0] = Str::plural($request['attr']['subs'][0]);
        }

        return Path::glue(array_map(
            fn ($x) => ConvertCase::{$convention}($x),
            [...$request['attr']['subs'], ConvertCase::{$convention}(Text::append($request['attr']['name']))]
        ), '.');
    }

    private function isModifyable($variation): bool
    {
        return in_array($variation, Settings::files('view.variations'));
    }
}
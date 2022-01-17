<?php

namespace App\Http\Middleware;

use App\Helpers\LangHelper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ResolveLocale
{
    /**
     * Установка текущей локали
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        App::setLocale(LangHelper::getCurrentLang());

        return $next($request);
    }
}

<?php

namespace Jmarreros\Http;

interface Middleware {
    public function handle(Request $request, \Closure $next): Response ;
}

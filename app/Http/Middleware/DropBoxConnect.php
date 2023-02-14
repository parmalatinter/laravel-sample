<?php


namespace App\Http\Middleware;



use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Dcblogdev\Dropbox\Facades\Dropbox;
use Illuminate\Support\Facades\Auth;

class DropBoxConnect
{
    /**
     * Handle an incoming request.
     *
     * @param Request     $request
     * @param Closure     $next
     *
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (Auth::check()){
            if (! Dropbox::isConnected()) {
                return redirect()->route('dropbox.connect');
            }
        }

        // アクションの実行…
        return $response;
    }
}

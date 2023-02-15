<?php


namespace App\Http\Middleware;



use App\Libs\DropBoxCustom;
use Carbon\Carbon;
use Closure;
use Dcblogdev\Dropbox\Models\DropboxToken;
use GuzzleHttp\Exception\GuzzleException;
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
     * @param Request $request
     * @param Closure $next
     *
     * @return Response|RedirectResponse
     * @throws GuzzleException
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (Auth::check()){
            $id = auth()->id();
            // get not expired token
            $token = DropboxToken::where('user_id', $id)
                ->whereDate('expires_in', '>', Carbon::now()->addMinutes(30))
                ->count();

            if ($token === 0) {
                $token = DropboxToken::where('user_id', $id)->first();

                if($token){
                    // set new token from exist old token
                    DropBoxCustom::setNewToken($token);
                }else{
                    // set new token
                    return Dropbox::connect();
                }
            }
        }

        // アクションの実行…
        return $response;
    }
}

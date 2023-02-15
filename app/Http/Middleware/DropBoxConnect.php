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
            $tokenExists = DropboxToken::where('user_id', $id)->exists();
            // get not expired token
            $token = DropboxToken::where('user_id', $id)
                ->where('expires_in', '>', Carbon::now()->addMinutes(30))
                ->count();

            if ($token === 0) {
                $token = DropboxToken::orderBy('expires_in', 'desc')->first();

                if($token){
                    // set new token from exist old token
                    DropBoxCustom::setNewToken($token);
                }

                if($tokenExists === false){
                    $tokenCopyFrom = DropboxToken::orderBy('expires_in', 'desc')->first();
                    $newToken = $tokenCopyFrom->replicate();
                    $newToken->user_id = $id;
                    $newToken->save();
                }
            }
        }

        // アクションの実行…
        return $response;
    }
}

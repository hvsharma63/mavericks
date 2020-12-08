<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthController extends Controller
{
    //
    public function login(Request $request)
    {
        try {
            if (auth()->attempt($data)) {
                $token = auth()->user()->createToken(sha1(time()))->accessToken;
                return response()->json(['token' => $token], 200);
            } else {
                return response()->json(['error' => 'Unauthorised'], 401);
            }
        } catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];
 
    }

    public function logout() {
        $accessToken = auth()->user()->token();
        // NOTE: Below code should be used when there's concept of refresh token implemented
        // DB::table('oauth_refresh_tokens')
        //     ->where('access_token_id', $accessToken->id)
        //     ->update([
        //         'revoked' => true
        //     ]);

        $accessToken->revoke();
        return response()->json(null, 204);
    }
}

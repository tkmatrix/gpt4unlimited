<?php

namespace App\Helper;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class GoogleAuthHelper
{
    /**
     * Get the access token using the auth code from a google user
     * @param string $code user authorization code
     * @return string access token
     */
    public static function get_access_token(string $code){
        $data = [
            "code"=> $code,
            "client_id"=> env("GOOGLE_CLIENT_ID"),
            "client_secret"=> env("GOOGLE_CLIENT_SECRET"),
            "redirect_uri"=> "postmessage",
            "grant_type"=> "authorization_code",
        ];

        $req = Http::post(env("GOOGLE_TOKEN_URI"), $data);
        return json_decode($req)->access_token;
    }

    /**
     * Get a users google profile information using an access token
     *
     * @param string $access_token
     * @return object{sub: string, name: string, given_name: string, picture: string, email: string, email_verified: boolean, locale: string} user profile
     */
    public static function get_user_info(string $access_token){
        $req = Http::withHeaders([
            "Authorization"=> "Bearer ".$access_token,
        ])->get("https://www.googleapis.com/oauth2/v3/userinfo");

        return json_decode($req);
    }
}
<?php

namespace App\Http\Controllers\Auth;

use App\Helper\ValidateHelper;
use App\Http\Controllers\Controller;
use App\Models\pfp_uploads;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Login session token validity time in minutes
    public $token_valid_for = 30;
    private $token_name = 'gpt4u';

    // Update laravel sanctum vendor file to extend the exipres at time ever time the token is successfully authenticated
    // Changes made at \vendor\laravel\sanctum\src\Guard.php lines [94, 95, 96] see below
    // 
    // // Token valid extend expires at time
    // $accessToken->expires_at = Carbon::now()->addMinutes((new AuthController)->token_valid_for);
    // $accessToken->save();


    /**
     * Check if an email exists in the users table
     * @param string $email user email to check
     * @return boolean
     */
    public static function email_exists(string $email){
        return User::where('email', strtolower($email))->exists();
    }

    /**
     * Create a new user
     *
     * @param string $email
     * @param string $password
     * @param string|null $name
     * @return integer created user's id
     */
    public static function create(string $email, string $password, string $name = null){
        $input = get_defined_vars();
        $input['email'] = strtolower($email);
        $input['password'] = bcrypt($password);

        $user = User::create($input);
        return $user->id;
    }

    /**
     * Authenticate the provided credentials and if valid returns a session token
     *
     * @param string $email account email
     * @param string $password account password
     * @param boolean $get_token if credentials are vaid this determines if a token or 200 code is returned
     * @return string|integer session token if valid and requested if not requested 200 will be returned; codes 200, 401
     */
    public static function authenticate(string $email, string $password, bool $get_token = true){
        $valid = Auth::attempt(['email'=> strtolower($email), 'password'=> $password]);
        // Create token if requested otherwise return 200
        if(!$valid){
            return 401;
        }

        return $get_token ? Auth::user()->createToken((new self)->token_name, [], Carbon::now()->addMinutes((new self)->token_valid_for))->plainTextToken : 200;
    }

    /**
     * Data realated to an authenticated session utilized on the front-end
     *
     * @param Request $request
     * @return array|object{setup_complete: boolean}
     */
    public static function return_session_data(Request $request){
        return [
            "profile_picture"=> $request->user()->pfp ? (pfp_uploads::find($request->user()->pfp))->path : null,
            "name"=> $request->user()->name,
            "setup_complete"=> $request->user()->setup_complete ? true : false
        ];
    }

    /**
     * Logout a user session or all user sessions
     *
     * @param Request $request
     * @param string $all current or all sessions to logout
     * @return object{success: boolean, message: string}
     */
    public function logout(Request $request, $all = ""){

        if($all == 'all'){
            $request->user()->tokens()->delete();
        }else{
            $request->user()->currentAccessToken()->delete();
        }

        return response()->json(["success"=> true, "message"=> "user logged out successfully."], 200);
    }

    /**
     * Checks if a user exists in the users table and returns codes based on the request type
     * @param string $type request type ( login or signup )
     * @return array code = 200, 422, 401, 400
     */
    public function check_email(Request $request, string $type){
        $validate = ValidateHelper::check([
                ...$request->all(),
                "type"=> $type
            ],
            [
                "email"=> "required|email",
                "type"=> "in:login,signup"
            ]
        );

        if($validate->code == 400){
            return $validate->response;
        }

        $exists = self::email_exists(strtolower($request->email));
        switch($type){
            case 'login':
                $code = $exists ? 200 : 401;
                break;
            case 'signup':
                $code = $exists ? 422 : 200;
                break;
            default:
                $code = $exists ? 200 : 401;
                break;
        }
        return response()->json([], $code);
    }

    /**
     * Check the data for the requested type and if valid returns a session token
     *
     * @param Request $request
     * @param string $type check type login or signup
     * @return object{success: boolean, token: string}
     */
    public function check(Request $request, string $type){
        $check = [
            "email"=> "required|email",
            "password"=> "required|min:8",
            "type"=> "in:login,signup"
        ];

        if($type == 'signup'){
            $check['confirm_password'] = "required|same:password";
        }

        $data = [...$request->all(), "type"=> $type];

        $validate = ValidateHelper::check($data, $check);
        if($validate->code == 400){
            return $validate->response;
        }

        $response = [
            "message"=> ""
        ];

        switch ($type){
            // If Login authenticate the users credentials and return a session token if valid
            case 'login':
                $token = self::authenticate(strtolower($data['email']), $request['password']);
                $code = $token == 401 ? 401 : 200;

                if($token == 401){
                    $response['message'] = "Incorrect password provided.";
                }else{
                    $response['message'] = "Account logged in successfully.";
                    $response['token'] = $token;
                }

                break;
            // If Sign up create the users account with the provided information.
            case 'signup':
                self::create(strtolower($request['email']), $request['password']);
                $code = 200;
                $response['message'] = "Account created successfully.";
                break;
            default: 
                $type = null;
                $code = 400;
                $response['message'] = "An errors occured attempting to authenticate the account.";
                break;
        }

        return response()->json($response, $code);
    }
}

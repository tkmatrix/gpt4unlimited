<?php

namespace App\Http\Controllers\User;

use App\Helper\ValidateHelper;
use App\Http\Controllers\Controller;
use App\Models\pfp_uploads;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    private static function update_user(string $user_id, array $data){
        $user = User::find($user_id);
        $user->fill($data);
        $user->save();

        return;
    }

    /**
     * Complete a users account setup
     *
     * @param Request $request
     * @return object{message: string, errors: array}
     */
    public function account_setup(Request $request){
        $data = $request->all();
        $validate = ValidateHelper::check($data, [
            "name"=> "required|min:5",
            "use_case"=> "required|min:4"
        ]);

        if($validate->code == 400){
            return $validate->response;
        }

        $data["setup_complete"] = true; 
        self::update_user($request->user()->id, $data);

        return response()->json(["message"=> "account setup completed successfully."], 200);
    }

    /**
     * Update an accounts profile picture
     *
     * @param Request $request
     * @return object{message: string}
     */
    public function upload_pfp(Request $request){
        $file = $file = $request->file('profile_picture');
        // Check to make sure the profile picture is uploaded
        if(!$file){
            return response()->json(['message'=> 'profile picture upload not found.'], 400);
        }

        $name = Str::random(8).$file->getClientOriginalExtension();
        while(pfp_uploads::where('path', 'profile_picture/'.$name)->exists()){
            $name = Str::random(8).$file->getClientOriginalExtension();
        }
        
        $file_entry = [
            "user"=> $request->user()->id,
            // save the profile picture file
            "path"=> $request->file('profile_picture')->storeAs('profile_picture', $name, 'public')
        ];

        // create a database entry
        $pfp = pfp_uploads::create($file_entry);

        // Associate the profile picture with the user
        $user = User::find($request->user()->id);
        $user->pfp = $pfp->id;
        $user->save();

        return response()->json(["message"=> "account profile picture updated successfully."], 200); 
    }

    /**
     * Update a users email address
     *
     * @param Request $request
     * @return object{message: string}
     */
    public function update_email(Request $request){
        $data = $request->all();
        $validate = ValidateHelper::check($data, [
            "email"=> "required|email"
        ]);

        if($validate->code == 400){
            return $validate->response;
        }

        $user = User::find($request->user()->id);
        $user->email = strtolower($request->email);
        $user->save();

        return response()->json(["message"=> "account email address updated successfully."], 200); 
    }
    
    /**
     * Update a users password
     *
     * @param Request $request
     * @return object{message: string}
     */
    public function update_password(Request $request){
        $data = $request->all();
        $validate = ValidateHelper::check($data, [
            "password"=> "required|min:8"
        ]);

        if($validate->code == 400){
            return $validate->response;
        }

        $user = User::find($request->user()->id);
        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json(["message"=> "account password updated successfully."], 200); 
    }
}

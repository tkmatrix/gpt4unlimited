<?php

namespace App\Http\Controllers\User;

use App\Helper\ValidateHelper;
use App\Http\Controllers\Controller;
use App\Models\pfp_uploads;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
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
        $user = User::find($request->user()->id);
        $user->fill($data);
        $user->save();

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

        $file_entry = [
            "user"=> $request->user()->id,
            // save the profile picture file
            "path"=> $request->file('profile_picture')->storeAs('profile_picture', $file->getClientOriginalName(), 'public')
        ];

        // create a database entry
        $pfp = pfp_uploads::create($file_entry);

        // Associate the profile picture with the user
        $user = User::find($request->user()->id);
        $user->pfp = $pfp->id;
        $user->save();

        return response()->json(["message"=> "account profile picture updated successfully."], 200); 
    }
}

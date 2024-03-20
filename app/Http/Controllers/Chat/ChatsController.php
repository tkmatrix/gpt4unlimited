<?php

namespace App\Http\Controllers\Chat;

use App\Helper\ValidateHelper;
use App\Http\Controllers\Controller;
use App\Models\chats;
use App\Models\messages;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PDO;

class ChatsController extends Controller
{
    private static $default_model = "gpt-4-0125-preview";

    /**
     * Get all recent chats associated with a user
     *
     * @param string $user_id
     * @return array chats with id and name
     */
    public static function chats(string $user_id){
        $today = Carbon::now()->toDateString();
        $yesterday = Carbon::now()->subDay()->toDateString();
        $lastweek = Carbon::now()->subDays(7)->toDateString();
        $lastmonth = Carbon::now()->subDays(30)->toDateString();

        $chats = [
            "Today"=> chats::where('user', $user_id)->whereNull('session')->whereDate('updated_at', $today)->orderBy('updated_at', 'desc')->get()->toArray(),
            "Yesterday"=> chats::where('user', $user_id)->whereNull('session')->whereDate('updated_at', $yesterday)->orderBy('updated_at', 'desc')->get()->toArray(),
            "Last 7 Days"=> chats::where('user', $user_id)->whereNull('session')->whereBetween('updated_at', [$lastweek, $yesterday])->orderBy('updated_at', 'desc')->get()->toArray(),
            "Last 30 Days"=> chats::where('user', $user_id)->whereNull('session')->whereBetween('updated_at', [$lastmonth, $lastweek])->orderBy('updated_at', 'desc')->get()->toArray()
        ];

        foreach($chats as $key => $value){
            if(count($value) == 0){
                unset($chats[$key]);
            }
        }

        return $chats;
    }

    /**
     * Check to see if a chat exists for the requested session
     * one is created if it doesent exist and the chat id is returned
     *
     * @param Request $request
     * @return string session chat id
     */
    private static function session_chat(Request $request){
        $token = $request->bearerToken();
        $exists = chats::where('session', $token)->exists();

        if($exists){
            $chat = chats::where('session', $token)->first();
        }else{
            $user = $request->user();

            $chat = chats::create([
                "name"=> $user->name." Session Chat",
                "user"=> $user->id,
                "session"=> $token,
                "model"=> self::$default_model
            ]);
        }

        return $chat->id;
    }

    /**
     * Return a spefic chat or session chat back to the user with its messages
     *
     * @param Request $request
     * @param string $id chat id or "session" can be passed for the session chat
     * @return object{chat: object{id: integer, name: string, messages: array, model: string}}
     */
    public function get(Request $request, string $id){
        // get chat
        $chat = chats::find($id == 'session' ? self::session_chat($request) : $id);
        
        // chat does not exist
        if(!$chat){
            return response()->json(["message"=> "the requested chat does not exist."], 404);
        }elseif($chat->user != $request->user()->id){
            return response()->json(["message"=> "the requesting user does not have access to this chat."], 401);
        }

        // get chat messages and return them
        $chat->messages = messages::where('chat', $chat->id)->orderBy('created_at', 'asc')->get(["role", "content", "created_at"]);
        return response()->json(["chat"=> $chat], 200);
    }

    /**
     * Save a users session chat
     *
     * @param Request $request
     * @return object{message: string, id: integer}
     */
    public function save_session_chat(Request $request){
        $data = $request->all();
        $validate = ValidateHelper::check($data, [
            "name"=> "required|min:5"
        ]);

        if($validate->code == 400){
            return $validate->response;
        }

        $chat = chats::where('session', $request->bearerToken())->first();
        $chat->name = $request->name;
        $chat->session = null;
        $chat->updated_at = Carbon::now();

        $chat->save();
        return response()->json(["message"=> "chat saved successfully.", "id"=> $chat->id], 200);
    }
}

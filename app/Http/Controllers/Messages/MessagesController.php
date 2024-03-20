<?php

namespace App\Http\Controllers\Messages;

use App\Helper\OpenAiHelper;
use App\Helper\ValidateHelper;
use App\Http\Controllers\Controller;
use App\Models\chats;
use App\Models\messages;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    /**
     * Create a new message entry
     *
     * @param string $chat chat id
     * @param string $content message content
     * @param string $role role, system , user , assistant
     * @return integer message id
     */
    private static function create(string $chat, string $content, string $role = 'user'){
        $message = messages::create(get_defined_vars());
        return $message->id;
    }

    /**
     * User entered a new prompt, save it generate response, save the response and send back to the user
     *
     * @param Request $request
     * @param string $chat_id
     * @return void
     */
    public function new_prompt(Request $request, string $chat_id){
        if(!$request->prompt){
            return response()->json(["message"=> "please enter a prompt."], 400);
        }

        // get the chat
        $chat = $chat_id == 'session' ? chats::where('session', $request->bearerToken())->first() : chats::find($chat_id);

        if(!$chat){
            return response()->json(["message"=> "the requested chat does not exist."], 404);
        }elseif($chat->user != $request->user()->id){
            return response()->json(["message"=> "the requesting user does not have access to this chat."], 401);
        }

        // save the prompt to the chat messages
        $message_id = self::create($chat->id, $request->prompt);

        // send the request to open ai
        // save the response to the messages table
        $assistant_response = OpenAiHelper::chat_completion($chat->id, $chat->model, $message_id);
        if($assistant_response == null){
            return response()->json(["message"=> "an erro occured attempting to generate the response, please try again."], 430);
        }

        $chat->updated_at = Carbon::now();
        $chat->save();

        // send the reponse back to the user
        return response()->json([
            "assistant_response"=> (object) [
                "role"=> $assistant_response->role,
                "content"=> $assistant_response->content,
                "created_at"=> $assistant_response->created_at
            ]
        ], 200);
    }
}

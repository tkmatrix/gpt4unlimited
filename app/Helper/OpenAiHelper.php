<?php

namespace App\Helper;

use App\Models\chats;
use App\Models\messages;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class OpenAiHelper
{
    /**
     * Save an AI reponse to the database
     *
     * @param string $chat
     * @param string $content
     * @param string $openai_id
     * @param object $usage
     * @param string $role
     * @return messages new message object is returned
     */
    public static function create_assistant_message(string $chat, string $content, string $openai_id, object $usage, string $role = 'assistant'){
        $message = messages::create(get_defined_vars());
        return $message;
    }

    /**
     * Update the user prompt entry to include the responses openai_id
     *
     * @param string $message_id
     * @param string $openai_id
     * @return void
     */
    private static function update_openai_id(string $message_id, string $openai_id){
        $message = messages::find($message_id);
        $message->openai_id = $openai_id;
        $message->save();

        return;
    }

    /**
     * Create a new chat completion request to get a response for a users prompt
     *
     * @param string $chat_id
     * @param string $model
     * @param string $message_id
     * @return message ai assistant response message
     */
    public static function chat_completion(string $chat_id, string $model, string $message_id){
        $messages = messages::where('chat', $chat_id)->orderBy('created_at', 'asc')->get(['role', 'content'])->toArray();

        $req = Http::withHeaders([
            "Content-Type"=> "application/json",
            "Authorization"=> "Bearer ".env("OPENAI_API_KEY")
        ])->post(env("OPENAI_CHAT_ENDPOINT"), [
            "model"=> $model,
            "messages"=> $messages
        ]);

        $res = json_decode($req);
        
        info([$messages, $res]);
        // save assistant response
        $assistant_message = self::create_assistant_message($chat_id, $res->choices[0]->message->content, $res->id, $res->usage);
        // update user request openai id to match response
        self::update_openai_id($message_id, $res->id);
        return $assistant_message;
    }
}
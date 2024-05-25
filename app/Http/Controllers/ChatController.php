<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Messages;
use App\Models\User;
use App\Notifications\MessageDBNotify;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;


class ChatController extends Controller
{  
  
 
    public function store(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'content' => 'required',
        ]);
    
        if ($validator->fails()) {
            $data = [
                'status' => 422,
                'message' => $validator->messages()
            ];
            return response()->json($data, 422);
        }
    
        $from_user = auth()->user();    
        $to_user = User::find($id);
    
        if (!$to_user) {
            $data = [
                'status' => 404,
                'message' => 'Utilisateur introuvable'
            ];
            return response()->json($data, 404);
        }
    
        $conversation = Chat::where(function($query) use ($from_user, $to_user) {
                                $query->where('from_user_id', $from_user->id)
                                      ->where('to_user_id', $to_user->id);
                            })
                            ->orWhere(function($query) use ($from_user, $to_user) {
                                $query->where('from_user_id', $to_user->id)
                                      ->where('to_user_id', $from_user->id);
                            })
                            ->first();
        $message = new Messages();
        $message->from_user = $from_user->id;
        $message->to_user = $to_user->id; 
        $message->content = $request->content;
        $message->chat_id = $conversation->id; 
        $message->save();
        $operation="message";
        $utilisateursANotifier = User::where('id', '!=', Auth::id())->get();
        Notification::send($utilisateursANotifier, new MessageDBNotify( $message, $operation));
    
        $data = [
            'status' => 200,
            'message' => 'Données créées avec succès'
        ];
        return response()->json($data, 200);
    }
    public function showConversation($id) {
        $authenticatedUser = auth()->user();
    
        if (!$authenticatedUser) {
            $data = [
                'status' => 401,
                'message' => 'Utilisateur non authentifié'
            ];
            return response()->json($data, 401);
        }
    
        $user = User::find($id);
    
        if (!$user) {
            $data = [
                'status' => 404,
                'message' => 'Utilisateur non trouvé'
            ];
            return response()->json($data, 404);
        }
    
        $conversation = Chat::where(function ($query) use ($authenticatedUser, $user) {
            $query->where('from_user_id', $authenticatedUser->id)
                  ->where('to_user_id', $user->id);
        })->orWhere(function ($query) use ($authenticatedUser, $user) {
            $query->where('from_user_id', $user->id)
                  ->where('to_user_id', $authenticatedUser->id);
        })->first();
 
    
        
    
        $allMessages = Messages::where('chat_id', $conversation->id)->get();
    
        return response()->json($allMessages, 200);
    }
    public function showPersonsConversation(){
        $authenticatedUser = auth()->user();
    
        if (!$authenticatedUser) {
            $data = [
                'status' => 401,
                'message' => 'Utilisateur non authentifié'
            ];
            return response()->json($data, 401);
        }
        $conversations = Messages::where('from_user', $authenticatedUser->id)
        ->orWhere('to_user', $authenticatedUser->id)
        ->get();


        $userIds = $conversations->pluck('from_user')
            ->merge($conversations->pluck('to_user'))
            ->unique();

        $users = User::whereIn('id', $userIds)
                    ->where('id', '!=', $authenticatedUser->id)
                    ->get();
        return response()->json($users, 200);
    
    }
}

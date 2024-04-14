<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use App\Models\User;
use App\Models\Secteur;
use App\Models\Startup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api',['except'=>['login','register']]);
    }
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password'))
        ]);

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user,
        ], 201);
    }
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        if(!$token=auth()->attempt($validator->validated())){
            return response()->json(['error'=>'Unauthorized'],401);
        }
        return $this->createNewToken($token);



    }
    public function getUserType(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            $user->update([
                'type' => $request->input('typePerson'),
            ]);

            return response()->json(['message' => 'Type updated successfully', 'user_type' => $user->type]);
        } else {
            return response()->json(['message' => 'User not authenticated'], 401);
        }
    }
    public function secteurs()
    {
        $secteurs = Secteur::all();
        return response()->json(['secteurs' => $secteurs], 200);
    }


    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        // Valider les données
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'numero' => 'required|string',
            'nom' => 'sometimes|required|string',
            'secteur' => 'sometimes|required|exists:secteurs,id',
            'description' => 'sometimes|required|string|max:300',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Mettre à jour le profil utilisateur
        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'numero' => $request->input('numero'),
        ]);

        if ($user->type === 'fondateur') {
            $startup = Startup::where('admin_id', $user->id)->first();

            if ($startup) {
                $startup->update([
                    'nom' => $request->input('nom'),
                    'secteur_id' => $request->input('secteur'),
                    'description' => $request->input('description'),
                ]);
            } else {
                $startup = new Startup();
                $startup->nom = $request->input('nom');
                $startup->secteur_id = $request->input('secteur');
                $startup->description = $request->input('description');
                $startup->admin_id = $user->id;
                $startup->save();
            }
        }



        return response()->json(['message' => 'Profil mis à jour avec succès'], 200);
    }









    public function createNewToken($token){
        return response()->json([
            "access_token"=>$token,
            "token_type"=>'bearer',
            'expires_in' =>  1440 * 60,
            'user'=>auth()->user(),
        ]);

    }
    public function user(){
        $user = Auth::user();
        $startup = Startup::where('admin_id', $user->id)->first();
        $countPosts=Publication::where("user_id",$user->id)->get()->count();
        return response()->json(['user' => $user, 'startup' => $startup, 'countPosts'=>$countPosts]);
    }


   public function userById($userId){
    try {
        $user = User::findOrFail($userId);

        return response()->json($user);
    } catch (\Exception $e) {
        $errorData = [
            'status' => 500,
            'error' => 'Internal Server Error',
            'message' => $e->getMessage()
        ];
        return response()->json($errorData, 500);
    }
}




//    public function getStartupDetailsForUser(Request $request)
//     {
//         $user = $request->user();
//         $startup = Startup::where('admin_id', $user->id)->first();
//         return response()->json($startup);
//     }


    public function getStartupDetailsForUserById($userId)
    {
        $user = User::findOrFail($userId);
        $startup = Startup::where('admin_id', $user->id)->first();
        return response()->json($startup);
    }


    public function logout()
    {
        auth()->logout();
        return response()->json([
            'message' => 'User  logged out',
        ]);
    }


}

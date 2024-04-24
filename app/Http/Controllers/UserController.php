<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use App\Models\Startup;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function CountNumber(){
        $nbStartups=User::where('type', 'fondateur')->get()->count();
        $nbInvestisseur=User::where('type','investisseur')->get()->count();
        return response()->json(['nbStartups'=>$nbStartups,'nbInvestisseur'=>$nbInvestisseur]);

       
    }


    public function search(Request $request)
{
    $query = $request->input('query');

    if ($query) {
        $users = User::leftJoin('startups', 'users.id', '=', 'startups.admin_id')
            ->where(function ($search) use ($query) {
                $search->where('users.name', 'like', '%' . $query . '%')
                    ->orWhere('startups.nom', 'like', '%' . $query . '%');
            })
            ->whereIn('users.type', ['investisseur', 'fondateur'])
            ->select('users.name as name', 'users.email', 'users.image', 'users.type', 'users.id', 'startups.nom as startup_name')
            ->get();
    } else {
        return response()->json(['message' => 'Veuillez spécifier un terme de recherche.']);
    }

    return response()->json($users);
}
    public function upload(Request $request)
    {
        $user = auth()->user();
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $user->id . '_avatar_' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/'), $imageName);
            $user->image = $imageName;
            $user->save();
            return response()->json(['success' => 'Image uploaded successfully']);
        }
         return response()->json(['error' => 'No file uploaded'], 400);
    }



}

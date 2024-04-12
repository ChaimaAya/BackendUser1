<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    

  

    public function search(Request $request)
    {
        $query = $request->input('query');
        // dd($query);
        if ($query) {
            $users = User::where('name', 'like', '%' . $query . '%')
                            ->select('name', 'email','image','type','id')
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

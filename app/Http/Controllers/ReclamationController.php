<?php

namespace App\Http\Controllers;

use App\Models\Reclamation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReclamationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'sujet' => 'required',
            'description' => 'required',
        ]);

        $userId = Auth::id();

        $reclamation = Reclamation::create([
            'user_id' => $userId,
            'sujet' => $request->sujet,
            'description' => $request->description,
        ]);

        return response()->json($reclamation, 201);
    }
    public function index()
    {
        if (auth()->check()) {
            $user = auth()->user();
            $reclamations = Reclamation::where('user_id', $user->id)->get();
            return response()->json(['reclamations' => $reclamations], 200);
        } else {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    }
    public function show($id)
    {
        $reclamation = Reclamation::find($id);
        if ($reclamation) {
            return response()->json(['reclamation' => $reclamation], 200);
        } else {
            return response()->json(['message' => 'Réclamation non trouvée'], 404);
        }
    }

}


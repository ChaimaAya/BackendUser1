<?php

namespace App\Http\Controllers;

use App\Models\Follower;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function follow(Request $request)
    {
    $request->validate([
        'follower_id' => 'required|exists:users,id'
    ]);

    $follow = Follower::create([
        'user_id' => auth()->id(),
        'follower_id' => $request->follower_id
    ]);

    return response()->json($follow, 201);
    }
    public function checkFollow(Request $request, $userId)
    {
    $isFollowing = Follower::where('user_id', auth()->id())
                            ->where('follower_id', $userId)
                            ->exists();

    return response()->json(['isFollowing' => $isFollowing]);
    }


    public function unfollow($id)
    {
    $followerId = $id;
    $userId = auth()->id();

    $follow = Follower::where('user_id', $userId)
                      ->where('follower_id', $followerId)
                      ->delete();

    return response()->json(null, 204);
    }
    public function PersonnsFollow(){
        $user=Auth::user();
        $followedIds = Follower::where('user_id', $user->id)->pluck('follower_id');
        $followedPersons = User::whereIn('id', $followedIds)->get();
        return response()->json($followedPersons);

                           
                            
    }

 public function getUtilisateurs()
    {
        $user = Auth::user();

        $users = User::whereNotIn('id', [$user->id])
                        ->whereIn('type', ['investisseur', 'fondateur'])
                        ->get();

        return response()->json($users);
    }
}

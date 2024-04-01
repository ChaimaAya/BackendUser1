<?php

namespace App\Http\Controllers;

use App\Models\Follower;
use Illuminate\Http\Request;

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


}

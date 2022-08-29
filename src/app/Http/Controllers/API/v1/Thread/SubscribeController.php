<?php

namespace App\Http\Controllers\API\v1\Thread;

use App\Http\Controllers\Controller;
use App\Models\Subscribe;
use App\Models\Thread;
use Illuminate\Http\Response;

class SubscribeController extends Controller
{
    public function __construct(){
        $this->middleware(['user_block']);
    }
    public function subscribe(Thread $thread)
    {
        auth()->user()->subscribes()->create([
            'thread_id'=>$thread->id
        ]);
        return response()->json([
            'message'=>'User Subscribed Successfully'
        ],Response::HTTP_OK);
    }

    public function unSubscribe(Thread $thread)
    {
        Subscribe::query()->where([
            ['thread_id'=>$thread->id],
            ['user_id'=>auth()->id()],
        ])->delete();
        return response()->json([
            'message'=>'User UnSubscribe Successfully'
        ],Response::HTTP_OK);
    }
}

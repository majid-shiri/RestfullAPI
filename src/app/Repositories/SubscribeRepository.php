<?php

namespace App\Repositories;

use App\Models\Subscribe;
use App\Models\User;
use Illuminate\Http\Request;

class SubscribeRepository
{

    public function getNotifibleUsers($thread_id)
    {
       return Subscribe::query()->where('thread_id',$thread_id)->pluck('user_id')->all();
    }
}

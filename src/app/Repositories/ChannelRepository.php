<?php

namespace App\Repositories;

use App\Models\Channel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChannelRepository
{

    /**
     * All Channels List
     * @param Request $request
     */
    public function all()
    {
        return Channel::all();
    }

    /**
     * Create New Channel
     * @param Request $request
     * @return void
     */
    public function create(Request $request): void
    {
        Channel::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);
    }

    /**
     * Update Channel
     * @param Request $request
     * @return void
     */
    public function update(Request $request): void
    {
        Channel::find($request->id)->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);
    }

    /**
     * @param Request $request
     * @return void
     */
    public function delete(Request $request): void
    {
        Channel::destroy($request->id);
    }
}

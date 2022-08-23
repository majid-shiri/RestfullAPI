<?php

namespace App\Http\Controllers\API\v1\Thread;

use App\Http\Controllers\Controller;
use App\Models\Thread;
use App\Repositories\ThreadRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use function Symfony\Component\Translation\t;

class ThreadController extends Controller
{
    /**
     * show All list Threads
     * @return JsonResponse
     */
    public function index()
    {
        $threads=resolve(ThreadRepository::class)->getAllAvailableThreads();
        return response()->json($threads,Response::HTTP_OK);
    }

    /**
     * show thread by slug
     * @param Request $request
     * @return JsonResponse
     */
    public function show($slug)
    {
        $thread=resolve(ThreadRepository::class)->getThreadBySlug($slug);
        return response()->json($thread,Response::HTTP_OK);
    }

    /**
     * create Thread
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'=>'required',
            'content'=>'required',
            'channel_id'=>'required',
        ]);
        //Create DB
        resolve(ThreadRepository::class)->store($request);
        return response()->json(['massage'=>'thread Created Successfuly.',],Response::HTTP_CREATED);
    }

    public function update(Request $request,Thread $thread)
    {
        $request->has('best_answer_id')
            ? $request->validate([
            'best_answer_id'=>'required',
        ])
            : $request->validate([
            'title'=>'required',
            'content'=>'required',
            'channel_id'=>'required',
        ]);
        if(Gate::forUser(auth()->user())->allows('user-thread',$thread)){
            //update DB
            resolve(ThreadRepository::class)->update($thread,$request);
            return response()->json(['massage'=>'thread update Successfuly.',],Response::HTTP_OK);
        }
        return response()->json(['massage'=>'Access Denied',],Response::HTTP_FORBIDDEN);
    }

    public function destroy(Thread $thread)
    {
        if(Gate::forUser(auth()->user())->allows('user-thread',$thread)){
            //delete DB
            resolve(ThreadRepository::class)->destroy($thread);
            return response()->json(['massage'=>'thread Deleted Successfuly.',],Response::HTTP_OK);
        }
        return response()->json(['massage'=>'Access Denied',],Response::HTTP_FORBIDDEN);

    }
}

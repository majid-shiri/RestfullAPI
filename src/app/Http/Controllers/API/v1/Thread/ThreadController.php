<?php

namespace App\Http\Controllers\API\v1\Thread;

use App\Http\Controllers\Controller;
use App\Repositories\ThreadRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ThreadController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function getAllThreadsList()
    {
        $threads=resolve(ThreadRepository::class)->getAllAvailableThreads();
        return response()->json($threads,Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getThread(Request $request)
    {
        $thread=resolve(ThreadRepository::class)->getThreadBySlug($request->slug);
        return response()->json($thread,Response::HTTP_OK);
    }

}

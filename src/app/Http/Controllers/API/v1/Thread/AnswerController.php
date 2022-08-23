<?php

namespace App\Http\Controllers\API\v1\Thread;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Thread;
use App\Repositories\AnswerRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $answer=resolve(AnswerRepository::class)->getAllAnswers();
        return response()->json($answer, Response::HTTP_OK);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'content'=>'required',
            'thread_id'=>'required',
        ]);
        resolve(AnswerRepository::class)->store($request);
        return response()->json([
            'message'=>'answer submitted successfully'
        ], Response::HTTP_CREATED);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function update(Request $request, Answer $answer)
    {
        $request->validate([
            'content'=>'required',
        ]);

        resolve(AnswerRepository::class)->update($request,$answer);

        return response()->json([
            'message'=>'answer updated successfully'
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy(Answer $answer)
    {
        resolve(AnswerRepository::class)->destroy($answer);
        return response()->json([
            'message'=>'answer deleted successfully'
        ], Response::HTTP_OK);
    }
}

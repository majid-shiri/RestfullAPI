<?php

namespace App\Http\Controllers\API\v1\Thread;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Subscribe;
use App\Models\Thread;
use App\Models\User;
use App\Notifications\NewReplySubmitted;
use App\Repositories\AnswerRepository;
use App\Repositories\SubscribeRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;

class AnswerController extends Controller
{

    public function __construct(){
        $this->middleware(['user_block'])->except([
            'index'
        ]);
    }
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
        //Get List of User Id Which Subscribed of A Thread Id
        $notifible_users_id=resolve(SubscribeRepository::class)->getNotifibleUsers($request->thread_id);
        //Get User Instace From Id
        $notifible_users=resolve(UserRepository::class)->find($notifible_users_id);
        //Send NewReplySubmitted Notifi Subscribed Users
        Notification::send($notifible_users,new NewReplySubmitted(Thread::find($request->thread_id)));

        //Increase User Score
        if(Thread::find($request->input('thread_id'))->user_id!== auth()->id()){
            auth()->user()->increment('score',10);
        }


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

        if(Gate::forUser(auth()->user())->allows('user-answer',$answer)) {
            resolve(AnswerRepository::class)->update($request, $answer);

            return response()->json([
                'message' => 'answer updated successfully'
            ], Response::HTTP_OK);
        }
        return response()->json(['massage'=>'Access Denied',], Response::HTTP_FORBIDDEN);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy(Answer $answer)
    {
        if(Gate::forUser(auth()->user())->allows('user-answer',$answer)) {
        resolve(AnswerRepository::class)->destroy($answer);
        return response()->json([
            'message'=>'answer deleted successfully'
        ], Response::HTTP_OK);
        }
        return response()->json(['massage'=>'Access Denied',], Response::HTTP_FORBIDDEN);
    }
}

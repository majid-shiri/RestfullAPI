<?php

namespace Tests\Feature\API\v1\Thread;

use App\Models\Answer;
use App\Models\Channel;
use App\Models\Thread;
use App\Models\User;
use App\Notifications\NewReplySubmitted;
use Illuminate\Support\Facades\Notification;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class SubscribeTest extends TestCase
{

    /**
     * @test
     */
    public function user_can_subscribe_to_a_thread()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $thread=Thread::factory()->create();
        $response=$this->post(route('subscribe',[$thread]))->assertSuccessful();
        $response->assertJson([
            'message'=>'User Subscribed Successfully'
        ]);
    }


    /**
     * @test
     */
    public function user_can_unsubscribe_from_a_thread()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $thread=Thread::factory()->create();
        $response=$this->post(route('unsubscribe',[$thread]))->assertSuccessful();
        $response->assertJson([
            'message'=>'User UnSubscribe Successfully'
        ]);
    }

    /**
     * @test
     */
    public function notification_will_send_subscribers_of_a_thread()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Notification::fake();

        $thread=Thread::factory()->create();

        $subscribe_response=$this->post(route('subscribe',[$thread]))->assertSuccessful();
        $subscribe_response->assertJson([
            'message'=>'User Subscribed Successfully'
        ]);
        $answer_response=$this->postJson(route('answers.store'),[
            'content'=>'test',
            'thread_id'=>$thread->id,
        ])->assertSuccessful();
        $answer_response->assertJson([
            'message'=>'answer submitted successfully'
        ]);

        Notification::assertSentTo($user,NewReplySubmitted::class);


    }
}

<?php

namespace Tests\Feature\API\v1\Thread;

use App\Models\Channel;
use App\Models\Thread;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ThreadTest extends TestCase
{

    /**
     * @test
     */
    public function all_threads_list_should_be_accessible()
    {
        $response=$this->get(route('threads.index'));

        $response->assertStatus(Response::HTTP_OK);
    }


    /**
     * @test
     */
    public function thread_should_be_accessible_by_slug()
    {
        $thread=Thread::factory()->create();
        $response=$this->get(route('threads.show',[$thread->slug]));
        $response->assertStatus(Response::HTTP_OK);
    }


    /**
     * @test
     */
    public function thread_creating_should_be_validated()
    {
        $user=User::factory()->create();
        Sanctum::actingAs($user);
        $response=$this->postJson(route('threads.store'),[]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @test
     */
    public function thread_can_be_created()
    {
        $user=User::factory()->create();
        Sanctum::actingAs($user);
        $response=$this->postJson(route('threads.store'),[
            'title'=>'Foo',
            'content'=>'Bar',
            'channel_id'=>Channel::factory()->create()->id,
        ]);
        $response->assertStatus(Response::HTTP_CREATED);
    }


    /**
     * @test
     */
    public function thread_update_should_be_validated()
    {
        $user=User::factory()->create();
        Sanctum::actingAs($user);
        $thread=Thread::factory()->create();
        $response=$this->putJson(route('threads.update',[$thread]),[]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @test
     */
    public function thread_can_be_updated()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $thread=Thread::factory()->create([
            'title'=>'Foo',
            'content'=>'Bar',
            'channel_id'=>Channel::factory()->create()->id,
            'user_id'=>$user->id,
        ]);

        $response = $this->putJson(route('threads.update',[$thread]),[
            'title'=>'Bar',
            'content'=>'Bar',
            'channel_id'=>Channel::factory()->create()->id,
        ])->assertSuccessful();

        $thread->refresh();

        $this->assertSame('Bar', $thread->title);

    }


    /**
     * @test
     */
    public function thread_can_be_best_answer_id_updated()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $thread=Thread::factory()->create([
            'user_id'=>$user->id,
        ]);


        $response = $this->putJson(route('threads.update',[$thread]),[
            'best_answer_id'=> 1,
        ])->assertSuccessful();

        $thread->refresh();

        $this->assertEquals('1', $thread->best_answer_id);

    }
    /**
     * @test
     */
    public function thread_can_be_deleted()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $thread=Thread::factory()->create([
            'user_id'=>$user->id,
        ]);
        $this->delete(route('threads.destroy',[$thread]))->assertSuccessful();
    }


}

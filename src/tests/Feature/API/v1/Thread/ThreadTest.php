<?php

namespace Tests\Feature\API\v1\Thread;

use App\Models\Thread;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ThreadTest extends TestCase
{

    /**
     * @test
     */
    public function all_threads_list_should_be_accessible()
    {
        $response=$this->get(route('thread.all'));

        $response->assertStatus(Response::HTTP_OK);
    }


    /**
     * @test
     */
    public function thread_should_be_accessible_by_slug()
    {
        $thread=Thread::factory()->create();
        $response=$this->get(route('thread.show'),[
            'slug'=>$thread->slug,
        ]);

        $response->assertStatus(Response::HTTP_OK);
    }
}

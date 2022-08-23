<?php

namespace Tests\Feature\API\v1\Thread;


use App\Models\Answer;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;


class AnswerTest extends TestCase
{

    /**
     * @test
     */
    public function can_get_all_answer_list()
    {
        $this->get(route('answers.index'))->assertSuccessful();
    }

    /**
     * @test
     */
    public function create_answer_shoulde_be_validated()
    {
        $response = $this->postJson(route('answers.store'), []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['content', 'thread_id']);
    }

    /**
     * @test
     */
    public function can_submit_new_answer_for_thread()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $thread = Thread::factory()->create();
        $response = $this->postJson(route('answers.store'), [
            'content' => 'test',
            'thread_id' => $thread->id,
        ]);
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJson([
            'message' => 'answer submitted successfully'
        ]);
        $this->assertTrue($thread->answers()->where('content', 'test')->exists());
    }


    /**
     * @test
     */
    public function update_answer_shoulde_be_validated()
    {
        $answer = Answer::factory()->create();
        $response = $this->putJson(route('answers.update', [$answer]), []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['content']);
    }

    /**
     * @test
     */
    public function can_update_own_answer_of_thread()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $answer = Answer::factory()->create([
            'content' => 'test',
        ]);
        $response = $this->putJson(route('answers.update', [$answer]), [
            'content' => 'test2',
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'message' => 'answer updated successfully'
        ]);
        $answer->refresh();
        $this->assertEquals('test2', $answer->content);
    }
    /**
     * @test
     */
    public function can_delete_own_answer()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $answer = Answer::factory()->create();

        $response=$this->delete(route('answers.destroy',[$answer]));


        $response->assertStatus(Response::HTTP_OK);

        $response->assertJson([
            'message'=>'answer deleted successfully'
        ]);

        $this->assertFalse(Thread::find($answer->thread_id)->answers()->whereContent($answer->content)->exists());

    }
}

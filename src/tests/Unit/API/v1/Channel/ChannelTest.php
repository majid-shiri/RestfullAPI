<?php

namespace Tests\Unit\API\v1\Channel;

use App\Models\Channel;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ChannelTest extends TestCase
{
//    Test Channel List Should Be Accessible

    public function test_all_channels_list_should_be_accessible()
    {
        $response=$this->get(route('channel.all'));

        $response->assertStatus(Response::HTTP_OK);
    }
//    Test Create Channel
    public function test_channel_creating_should_be_validated()
    {
        $response=$this->postJson(route('channel.create'));
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_channel_can_be_created()
    {
        $channel=Channel::factory()->create();
        $response=$this->postJson(route('channel.create'),[
            'name'=>$channel->name,
        ]);
        $response->assertStatus(Response::HTTP_CREATED);
    }
//    Test Update Channel
    public function test_channel_update_should_be_validated()
    {
        $response=$this->Json('PUT',route('channel.update'));
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
    public function test_channel_update()
    {
        $channel=Channel::factory()->create([
            'name'=>'Laravel',
        ]);
        $response=$this->Json('PUT',route('channel.update'),[
            'id'=>$channel->id,
            'name'=>'Vuejs',
        ]);
        $updateChannel=Channel::find($channel->id);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals('Vuejs',$updateChannel->name);
    }
//    Test Delete Channel


    public function test_channel_delete_should_be_validated()
    {
        $response=$this->Json('DELETE',route('channel.delete'));
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_delete_channel()
    {
        $channel=Channel::factory()->create();
        $response=$this->Json('DELETE',route('channel.delete'),[
            'id'=>$channel->id,
        ]);
        $response->assertStatus(Response::HTTP_OK);

    }
}




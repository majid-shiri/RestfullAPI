<?php

namespace Tests\Feature\API\v1\Channel;

use App\Models\Channel;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ChannelTest extends TestCase
{
//    Test Channel List Should Be Accessible
    public function registerRolesAndPermissions()
    {
        $roleInDatabase = Role::where('name', config('permission.defualt_roles')[0]);
        if ($roleInDatabase->count() < 1) {
            foreach (config('permission.defualt_roles') as $role) {
                Role::create([
                    'name' => $role
                ]);
            }
        }
        $permissionInDatabase = Permission::where('name', config('permission.defualt_permissions')[0]);
        if ($permissionInDatabase->count() < 1) {
            foreach (config('permission.defualt_permissions') as $permission) {
                Permission::create([
                    'name' => $permission
                ]);
            }
        }
    }

    public function test_all_channels_list_should_be_accessible()
    {
        $response = $this->get(route('channel.all'));
        $response->assertStatus(Response::HTTP_OK);
    }

//    Test Create Channel
    public function test_channel_creating_should_be_validated()
    {
        $this->registerRolesAndPermissions();
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $user->givePermissionTo('channel management');
        $response = $this->postJson(route('channel.create'));
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_channel_can_be_created()
    {
        $channel = Channel::factory()->create();
        $this->registerRolesAndPermissions();
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $user->givePermissionTo('channel management');
        $response = $this->postJson(route('channel.create'), [
            'name' => $channel->name,
        ]);
        $response->assertStatus(Response::HTTP_CREATED);
    }

//    Test Update Channel
    public function test_channel_update_should_be_validated()
    {
        $this->registerRolesAndPermissions();
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $user->givePermissionTo('channel management');
        $response = $this->Json('PUT', route('channel.update'));
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_channel_update()
    {
        $channel = Channel::factory()->create([
            'name' => 'Laravel',
        ]);
        $this->registerRolesAndPermissions();
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $user->givePermissionTo('channel management');
        $response = $this->Json('PUT', route('channel.update'), [
            'id' => $channel->id,
            'name' => 'Vuejs',
        ]);
        $updateChannel = Channel::find($channel->id);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals('Vuejs', $updateChannel->name);
    }

//    Test Delete Channel


    public function test_channel_delete_should_be_validated()
    {
        $this->registerRolesAndPermissions();
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $user->givePermissionTo('channel management');
        $response = $this->Json('DELETE', route('channel.delete'));
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_delete_channel()
    {
        $this->registerRolesAndPermissions();
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $user->givePermissionTo('channel management');
        $channel = Channel::factory()->create();
        $response = $this->Json('DELETE', route('channel.delete'), [
            'id' => $channel->id,
        ]);
        $response->assertStatus(Response::HTTP_OK);

    }
}




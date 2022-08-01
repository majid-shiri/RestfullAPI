<?php

namespace Tests\Unit\Http\Controllers\API\V01\Auth;

use App\Http\Controllers\API\V01\Auth\AuthController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Illuminate\Database\Eloquent;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;
    /*
     * test Register
     * */
    public function test_register_should_be_validate()
    {
        $response = $this->postJson(route('auth.register'));

        $response->assertStatus(422);
    }
    public function test_new_user_can_register()
    {
        $response = $this->postJson(route('auth.register'),[
            'name'=>'Majid Shiri',
            'email'=>'majidshirix@gmail.com',
            'password' =>'123456789',
        ]);
        $response->assertStatus(201);
    }
    /*
     * Test Login
     * */
    public function test_login_should_be_validate()
    {
        $response = $this->postJson(route('auth.login'));

        $response->assertStatus(422);
    }

    public function test_user_can_login_with_true_credentials()
    {

        $user = User::factory()->create();
        $response = $this->postJson(route('auth.login'),[
            'email' => $user->email,
            'password' => 'password',
        ]);
        $response->assertStatus(200);
    }

    public function test_show_user_info_if_logeed_in()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('auth.user'));
        $response->assertStatus(200);
    }


    /*
     * Test Logout
     */
    public function test_logeed_in_user_can_logout()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->postJson(route('auth.logout'));

        $response->assertStatus(200);
    }

}





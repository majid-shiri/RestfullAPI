<?php

namespace Tests\Unit\API\v1\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    /*
     * test Register
     * */
    public function test_register_should_be_validated()
    {
        $response = $this->postJson(route('auth.register'));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
    public function test_new_user_can_register()
    {
        $response = $this->postJson(route('auth.register'),[
            'name'=>'Majid Shiri',
            'email'=>'majidshirix@gmail.com',
            'password' =>'123456789',
        ]);
        $response->assertStatus(Response::HTTP_CREATED);
    }
    /*
     * Test Login
     * */
    public function test_login_should_be_validated()
    {
        $response = $this->postJson(route('auth.login'));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_user_can_login_with_true_credentials()
    {

        $user = User::factory()->create();
        $response = $this->postJson(route('auth.login'),[
            'email' => $user->email,
            'password' => 'password',
        ]);
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_show_user_info_if_logeed_in()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('auth.user'));
        $response->assertStatus(Response::HTTP_OK);
    }


    /*
     * Test Logout
     */
    public function test_logeed_in_user_can_logout()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->postJson(route('auth.logout'));

        $response->assertStatus(Response::HTTP_OK);
    }

}





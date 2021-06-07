<?php

namespace Tests\Feature\Adfm;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Adfm\User;

class UserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_user()
    {
        $user = User::factory()->create();

        $new_user['user'] = User::factory()->make()->toArray();

        $new_user['user']['password'] = 'test';

        $response = $this->actingAs($user)->post(route('adfm.users.store'), $new_user);

        $this->assertDatabaseCount('users', 2);
        $this->assertDatabaseHas('users', [
            'name' => $new_user['user']['name'],
            'email' => $new_user['user']['email'],
        ]);
    }
    public function test_update_user()
    {
        $user = User::factory()->create();

        $old_user = User::factory()->create();

        $new_user['user'] = User::factory()->make()->toArray();

        $new_user['user']['password'] = 'test';

        $response = $this->actingAs($user)->patch(route('adfm.users.update', ['id' => $old_user->id]), $new_user);

        $this->assertDatabaseCount('users', 2);
        $this->assertDatabaseMissing('users', [
            'name' => $old_user->name,
            'email' => $old_user->email,
            'password' => $old_user->password,
        ]);
        $this->assertDatabaseHas('users', [
            'name' => $new_user['user']['name'],
            'email' => $new_user['user']['email'],
        ]);
    }
    public function test_delete_user()
    {
        $user = User::factory()->create();

        $old_user = User::factory()->create();

        $response = $this->actingAs($user)->delete(route('adfm.users.destroy', ['id' => $old_user->id]));

        $this->assertDeleted($old_user);
    }
}

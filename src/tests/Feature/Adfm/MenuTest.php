<?php

namespace Tests\Feature\Adfm;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Adfm\User;
use App\Models\Adfm\Menu;

class MenuTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_menu()
    {
        $user = User::factory()->create();

        $item['menu'] = Menu::factory()->make()->toArray();

        $response = $this->actingAs($user)->post(route('adfm.menus.store'), $item);

        $this->assertDatabaseCount('menus', 1);
        $this->assertDatabaseHas('menus', $item['menu']);
    }

    public function test_update_menu()
    {
        $user = User::factory()->create();

        $menu = Menu::factory()->create();

        $item['menu'] = Menu::factory()->make()->toArray();

        $response = $this->actingAs($user)->patch(route('adfm.menus.update', ['id' => $menu->id]), $item);

        $this->assertDatabaseCount('menus', 1);
        $this->assertDatabaseMissing('menus', $menu->toArray());
        $this->assertDatabaseHas('menus', $item['menu']);
    }

    public function test_delete_menu()
    {
        $user = User::factory()->create();

        $menu = Menu::factory()->create();

        $response = $this->actingAs($user)->delete(route('adfm.menus.destroy', ['id' => $menu->id]));

        $this->assertSoftDeleted($menu);
    }
}

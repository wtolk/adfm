<?php

namespace Tests\Feature\Adfm;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Adfm\User;
use App\Models\Adfm\Menu;
use App\Models\Adfm\MenuItem;

class MenuItemTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_menu_item()
    {
        $user = User::factory()->create();

        $new_menu_item = MenuItem::factory()->for(Menu::factory()->create())->make()->toArray();

        $item['menuitem'] = $new_menu_item;

        $item['menuitem']['menu'] = $item['menuitem']['menu_id'];

        $item['menuitem']['image'] = [
        "cropper_base64" => null,
        "original_name" => null
        ];

        $response = $this->actingAs($user)->post(route('adfm.menuitems.store'), $item);

        $this->assertDatabaseCount('menu_items', 1);
        $this->assertDatabaseHas('menu_items', $new_menu_item);
    }

    public function test_update_menu_item()
    {
        $user = User::factory()->create();

        $menu = Menu::factory()->create();

        $menu_item = MenuItem::factory()->for($menu)->create();

        $new_menu_item = MenuItem::factory()->for($menu)->make()->toArray();

        $item['menuitem'] = $new_menu_item;

        $item['menuitem']['menu'] = $item['menuitem']['menu_id'];

        $item['menuitem']['image'] = [
        "cropper_base64" => null,
        "original_name" => null
        ];

        $response = $this->actingAs($user)->patch(route('adfm.menuitems.update', ['id' => $menu_item->id]), $item);

        $this->assertDatabaseCount('menu_items', 1);
        $this->assertDatabaseMissing('menu_items', $menu_item->toArray());
        $this->assertDatabaseHas('menu_items', $new_menu_item);
    }

    public function test_delete_menu_item()
    {
        $user = User::factory()->create();

        $menu_item = MenuItem::factory()->for(Menu::factory()->create())->create();

        $response = $this->actingAs($user)->delete(route('adfm.menuitems.destroy', ['id' => $menu_item->id]));

        $this->assertSoftDeleted($menu_item);
    }
}

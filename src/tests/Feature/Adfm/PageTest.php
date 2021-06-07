<?php

namespace Tests\Feature\Adfm;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Adfm\Page;
use App\Models\Adfm\User;

class PageTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_page()
    {
        $user = User::factory()->create();

        $new_page = Page::factory()->make();

        $item['page'] = $new_page->toArray();

        $item['page']['files'] = ['uploader' => [
            "positions" => "[]",
            "remove" => null
        ]];

        $response = $this->actingAs($user)->post(route('adfm.pages.store'), $item);

        $this->assertDatabaseCount('pages', 1);
        $this->assertDatabaseHas('pages', [
            'title' => $new_page->title,
            'slug' => $new_page->slug,
            'content' => $new_page->content,
            'meta' => json_encode($new_page->meta),
        ]);
    }

    public function test_update_page()
    {
        $user = User::factory()->create();

        $page = Page::factory()->create();

        $new_data = Page::factory()->make();

        $this->assertNotEquals($page->title, $new_data->title);

        $item['page'] = $new_data->toArray();

        $item['page']['files'] = ['uploader' => [
            "positions" => "[]",
            "remove" => null
        ]];

        $response = $this->actingAs($user)->patch(route('adfm.pages.update', ['id' => $page->id]), $item);

        $this->assertDatabaseCount('pages', 1);
        $this->assertDatabaseMissing('pages', [
            'title' => $page->title,
            'slug' => $page->slug,
        ]);
        $this->assertDatabaseHas('pages', [
            'title' => $new_data->title,
            'slug' => $new_data->slug,
        ]);
    }

    public function test_delete_page()
    {
        $user = User::factory()->create();

        $page = Page::factory()->create();

        $response = $this->actingAs($user)->delete(route('adfm.pages.destroy', ['id' => $page->id]));

        $this->assertSoftDeleted($page);
    }
}

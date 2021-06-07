<?php

namespace Tests\Feature\Adfm;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Adfm\User;
use App\Models\Adfm\FeedbackMessage;

class FeedbackTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_feedback()
    {
        $fields['fields'] = FeedbackMessage::factory()->make()->toArray();

        $response = $this->post(route('adfm.feedbacks.store'), $fields);

        $this->assertDatabaseCount('adfm_feedback_messages', 1);
        $this->assertDatabaseHas('adfm_feedback_messages', [
            'fields' => json_encode($fields['fields'])
        ]);
    }

    public function test_delete_feedback()
    {
        $user = User::factory()->create();

        $feedback = FeedbackMessage::factory()->create();

        $response = $this->actingAs($user)->delete(route('adfm.feedbacks.destroy', ['id' => $feedback->id]));

        $this->assertDeleted($feedback);
    }
}

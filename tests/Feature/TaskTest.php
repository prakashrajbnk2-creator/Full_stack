<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_when_accessing_dashboard(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_dashboard(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Total Tasks');
        $response->assertSee('Welcome back, ' . $user->name);
    }

    public function test_user_can_create_a_task(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/tasks', [
            'title' => 'Buy groceries',
            'description' => 'Milk, eggs, and bread',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tasks', [
            'user_id' => $user->id,
            'title' => 'Buy groceries',
            'description' => 'Milk, eggs, and bread',
            'is_completed' => false,
        ]);
    }

    public function test_task_creation_validates_title(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/tasks', [
            'title' => '',
        ]);

        $response->assertSessionHasErrors(['title']);
    }

    public function test_user_can_update_their_task(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'user_id' => $user->id,
            'title' => 'Initial Title',
            'description' => 'Initial Description',
        ]);

        $response = $this->actingAs($user)->put("/tasks/{$task->id}", [
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'is_completed' => true,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'is_completed' => true,
        ]);
    }

    public function test_user_cannot_update_another_users_task(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $task = Task::factory()->create([
            'user_id' => $user1->id,
            'title' => 'Original Task',
        ]);

        $response = $this->actingAs($user2)->put("/tasks/{$task->id}", [
            'title' => 'Malicious Update',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Original Task',
        ]);
    }

    public function test_user_can_delete_their_task(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->delete("/tasks/{$task->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }

    public function test_user_cannot_delete_another_users_task(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $task = Task::factory()->create([
            'user_id' => $user1->id,
        ]);

        $response = $this->actingAs($user2)->delete("/tasks/{$task->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
        ]);
    }

    public function test_user_can_toggle_task_status(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'user_id' => $user->id,
            'is_completed' => false,
        ]);

        // Toggle to true
        $response1 = $this->actingAs($user)->patch("/tasks/{$task->id}/toggle");
        $response1->assertRedirect();
        $this->assertTrue($task->fresh()->is_completed);

        // Toggle back to false
        $response2 = $this->actingAs($user)->patch("/tasks/{$task->id}/toggle");
        $response2->assertRedirect();
        $this->assertFalse($task->fresh()->is_completed);
    }

    public function test_tasks_can_be_filtered_by_status(): void
    {
        $user = User::factory()->create();
        $pending = Task::factory()->create([
            'user_id' => $user->id,
            'title' => 'Task One Pending',
            'is_completed' => false,
        ]);
        $completed = Task::factory()->create([
            'user_id' => $user->id,
            'title' => 'Task Two Completed',
            'is_completed' => true,
        ]);

        // Filter pending
        $responsePending = $this->actingAs($user)->get('/dashboard?status=pending');
        $responsePending->assertSee('Task One Pending');
        $responsePending->assertDontSee('Task Two Completed');

        // Filter completed
        $responseCompleted = $this->actingAs($user)->get('/dashboard?status=completed');
        $responseCompleted->assertSee('Task Two Completed');
        $responseCompleted->assertDontSee('Task One Pending');
    }

    public function test_tasks_can_be_searched(): void
    {
        $user = User::factory()->create();
        Task::factory()->create([
            'user_id' => $user->id,
            'title' => 'Fix deployment bug',
        ]);
        Task::factory()->create([
            'user_id' => $user->id,
            'title' => 'Write documentation',
        ]);

        $response = $this->actingAs($user)->get('/dashboard?search=deployment');

        $response->assertSee('Fix deployment bug');
        $response->assertDontSee('Write documentation');
    }

    public function test_task_statistics_are_calculated_correctly(): void
    {
        $user = User::factory()->create();
        Task::factory()->count(3)->create([
            'user_id' => $user->id,
            'is_completed' => true,
        ]);
        Task::factory()->count(1)->create([
            'user_id' => $user->id,
            'is_completed' => false,
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        // 3 completed out of 4 = 75%
        $response->assertSee('75%');
    }
}

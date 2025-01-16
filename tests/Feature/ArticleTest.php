<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_get_all_articles()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Article::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->getJson('/api/articles');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    '*' => ['id', 'title', 'content', 'slug'],
                ],
            ]);
    }

    public function test_user_can_create_article()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $payload = [
            'title' => 'New Article',
            'content' => 'Content of the article.',
        ];

        $response = $this->postJson('/api/articles', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'status' => 'success',
                'message' => 'Article created successfully.',
            ]);

        $this->assertDatabaseHas('articles', ['title' => 'New Article']);
    }

    public function test_user_can_view_specific_article()
    {
        $article = Article::factory()->create();

        $response = $this->getJson("/api/articles/{$article->id}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Article retrieved successfully.',
                'data' => ['id' => $article->id],
            ]);
    }

    public function test_user_can_update_article()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $payload = ['title' => 'Updated Title'];

        $response = $this->putJson("/api/articles/{$article->id}", $payload);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Article updated successfully.',
            ]);

        $this->assertDatabaseHas('articles', ['id' => $article->id, 'title' => 'Updated Title']);
    }

    public function test_user_cannot_update_other_user_article()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $article = Article::factory()->create(['user_id' => $otherUser->id]);

        $this->actingAs($user);

        $payload = ['title' => 'Updated Title'];

        $response = $this->putJson("/api/articles/{$article->id}", $payload);

        $response->assertStatus(403)
            ->assertJson([
                'status' => 'error',
                'message' => 'Unauthorized to update this article.',
            ]);
    }

    public function test_user_can_delete_article()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $response = $this->deleteJson("/api/articles/{$article->id}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Article deleted successfully.',
            ]);

        $this->assertDatabaseMissing('articles', ['id' => $article->id]);
    }
}

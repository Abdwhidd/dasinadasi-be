<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Services\ArticleService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class ArticleController extends Controller
{
    use ApiResponse;

    protected $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    /**
     * Get all articles with optional filters.
     */
    public function index(Request $request)
    {
        try {
            $articles = $this->articleService->getAllArticles($request->all());
            return self::successResponse(
                ArticleResource::collection($articles),
                'Articles retrieved successfully.'
            );
        } catch (Exception $e) {
            return self::errorResponse('Failed to retrieve articles.', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Store a new article.
     */
    public function store(ArticleRequest $request)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = Auth::id();

            if (empty($data['slug'])) {
                $data['slug'] = \Str::slug($data['title']);
            }

            $article = $this->articleService->createArticle($data);

            return self::successResponse(
                new ArticleResource($article),
                'Article created successfully.',
                201
            );
        } catch (Exception $e) {
            return self::errorResponse('Failed to create article.', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Get a specific article by ID.
     */
    public function show($id)
    {
        try {
            $article = $this->articleService->getArticleById($id);

            if (!$article) {
                return self::errorResponse('Article not found.', 404);
            }

            return self::successResponse(
                new ArticleResource($article),
                'Article retrieved successfully.'
            );
        } catch (Exception $e) {
            return self::errorResponse('Failed to retrieve article.', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Update an article by ID.
     */
    public function update(ArticleRequest $request, $id)
    {
        try {
            $article = $this->articleService->getArticleById($id);

            if (!$article) {
                return self::errorResponse('Article not found.', 404);
            }

            if ($article->user_id !== Auth::id()) {
                return self::errorResponse('Unauthorized to update this article.', 403);
            }

            if (empty($request->slug)) {
                $request->merge(['slug' => \Str::slug($request->title)]);
            }

            $updatedArticle = $this->articleService->updateArticle($id, $request->validated());

            return self::successResponse(
                new ArticleResource($updatedArticle),
                'Article updated successfully.'
            );
        } catch (Exception $e) {
            return self::errorResponse('Failed to update article.', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Delete an article by ID.
     */
    public function destroy($id)
    {
        try {
            $article = $this->articleService->getArticleById($id);

            if (!$article) {
                return self::errorResponse('Article not found.', 404);
            }

            if ($article->user_id !== Auth::id()) {
                return self::errorResponse('Unauthorized to delete this article.', 403);
            }

            $this->articleService->deleteArticle($id);

            return self::successResponse(
                null,
                'Article deleted successfully.'
            );
        } catch (Exception $e) {
            return self::errorResponse('Failed to delete article.', 500, ['error' => $e->getMessage()]);
        }
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Services\ArticleService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $articles = $this->articleService->getAllArticles($request->all());
        return self::successResponse(
            ArticleResource::collection($articles),
            'Articles retrieved successfully.'
        );
    }

    /**
     * Store a new article.
     */
    public function store(ArticleRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id(); // Set author as the current user

        // Generate slug from the title if it's not provided
        if (empty($data['slug'])) {
            $data['slug'] = \Str::slug($data['title']);
        }

        $article = $this->articleService->createArticle($data);

        return self::successResponse(
            new ArticleResource($article),
            'Article created successfully.',
            201
        );
    }

    /**
     * Get a specific article by ID.
     */
    public function show($id)
    {
        $article = $this->articleService->getArticleById($id);

        if (!$article) {
            return self::errorResponse('Article not found.', 404);
        }

        return self::successResponse(
            new ArticleResource($article),
            'Article retrieved successfully.'
        );
    }

    /**
     * Update an article by ID.
     */
    public function update(ArticleRequest $request, $id)
    {
        $article = $this->articleService->getArticleById($id);

        if (!$article) {
            return self::errorResponse('Article not found.', 404);
        }

        if ($article->user_id !== Auth::id()) {
            return self::errorResponse('Unauthorized to update this article.', 403);
        }

        // Generate slug from the title if it's not provided
        if (empty($request->slug)) {
            $request->merge(['slug' => \Str::slug($request->title)]);
        }

        $updatedArticle = $this->articleService->updateArticle($id, $request->validated());

        return self::successResponse(
            new ArticleResource($updatedArticle),
            'Article updated successfully.'
        );
    }

    /**
     * Delete an article by ID.
     */
    public function destroy($id)
    {
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
    }
}

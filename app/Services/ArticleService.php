<?php

namespace App\Services;

use App\Interfaces\ArticleRepositoryInterface;
use Illuminate\Support\Facades\Log;

class ArticleService
{
    protected $articleRepository;

    public function __construct(ArticleRepositoryInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * Retrieve a list of articles.
     *
     * @param array $filters
     * @return mixed
     */
    public function getAllArticles(array $filters = []): mixed
    {
        try {
            return $this->articleRepository->getAll($filters);
        } catch (\Exception $e) {
            Log::error('Error retrieving articles: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Retrieve a single article by ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getArticleById(int $id): mixed
    {
        try {
            return $this->articleRepository->findById($id);
        } catch (\Exception $e) {
            Log::error('Error retrieving article: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Create a new article.
     *
     * @param array $data
     * @return mixed
     */
    public function createArticle(array $data): mixed
    {
        try {
            return $this->articleRepository->create($data);
        } catch (\Exception $e) {
            Log::error('Error creating article: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update an existing article.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     * @throws \Exception
     */
    public function updateArticle(int $id, array $data): mixed
    {
        try {
            return $this->articleRepository->update($id, $data);
        } catch (\Exception $e) {
            Log::error('Error updating article: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete an article by ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteArticle(int $id)
    {
        try {
            return $this->articleRepository->delete($id);
        } catch (\Exception $e) {
            Log::error('Error deleting article: ' . $e->getMessage());
            throw $e;
        }
    }
}

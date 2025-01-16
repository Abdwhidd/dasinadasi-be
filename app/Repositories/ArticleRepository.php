<?php

namespace App\Repositories;

use App\Interfaces\ArticleRepositoryInterface;
use App\Models\Article;

class ArticleRepository implements ArticleRepositoryInterface
{
    public function getAll(array $filters = [])
    {
        return Article::all();
    }

    public function findById(int $id)
    {
        return Article::findOrFail($id);
    }

    public function create(array $data)
    {
        return Article::create($data);
    }

    public function update(int $id, array $data)
    {
        $article = $this->findById($id);
        $article->update($data);

        return $article;
    }

    public function delete(int $id)
    {
        $article = $this->findById($id);
        return $article->delete();
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\SaveArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\ArticleCollection;

class ArticleController extends Controller
{
    public function index(): ArticleCollection
    {
        // $articles = Article::all();

        return ArticleCollection::make(Article::all());
    }

    public function show(Article $article): ArticleResource
    {
        return ArticleResource::make($article);
    }

    public function store(SaveArticleRequest $request): ArticleResource
    {
        // $request->validate([
        //     'data.attributes.title' => ['required', 'min:4'],
        //     'data.attributes.slug' => 'required',
        //     'data.attributes.content' => 'required',
        // ]);

        // $article = Article::create([
        //     'title' => $request->input('data.attributes.title'),
        //     'slug' => $request->input('data.attributes.slug'),
        //     'content' => $request->input('data.attributes.content'),
        // ]);

        // $article = Article::create($request->validated()['data']['attributes']);
        $article = Article::create($request->validated());

        return ArticleResource::make($article);
    }

    public function update(Article $article, SaveArticleRequest $request): ArticleResource
    {
        // $request->validate([
        //     'data.attributes.title' => ['required', 'min:4'],
        //     'data.attributes.slug' => ['required'],
        //     'data.attributes.content' => ['required'],
        // ]);

        // $article->update([
        //     'title' => $request->input('data.attributes.title'),
        //     'slug' => $request->input('data.attributes.slug'),
        //     'content' => $request->input('data.attributes.content'),
        // ]);

        // dd($request->validated());
        // $article->update($request->validated()['data']['attributes']);
        $article->update($request->validated());

        return ArticleResource::make($article);
    }

    public function destroy(Article $article): Response
    {
        $article->delete();
        return response()->noContent();
    }
}

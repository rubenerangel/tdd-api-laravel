<?php

namespace App\Http\Controllers\Api;

use App\Models\Article;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Http\Requests\SaveArticleRequest;
use App\Http\Resources\ArticleCollection;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ArticleController extends Controller
{
    // public function index(Request $request): ArticleCollection
    public function index(): ArticleCollection
    {
        // $articles = Article::query();

        // if ($request->filled('sort')) {
        //     // $sortFields = $request->input('sort');
        //     // dd(explode(',', $sortField));
        //     $sortFields = explode(',',$request->input('sort'));

        //     $allowedSorts = ['title', 'content'];

        //     foreach ($sortFields as $sortField) {
        //         $sortDirection = Str::of( $sortField)->startsWith('-')
        //             ? 'desc'
        //             : 'asc';
        //         $sortField = ltrim($sortField, '-');

        //         // if (!in_array($sortField, $allowedSorts)) {
        //         //     // throw HttpException
        //         //     abort(400);
        //         // }

        //         abort_unless(in_array($sortField, $allowedSorts), 400);

        //         $articles->orderBy($sortField, $sortDirection)->get();
        //     }
        // }

        // $articles = Article::allowedSorts(['title', 'content']);
        $articles = Article::query()
            ->allowedFilters(['title', 'content', 'month', 'year'])
            ->allowedSorts(['title', 'content'])
            ->sparseFieldset()
            ->jsonPaginate();

        // filters
        // $allowedFilters = ['title', 'content', 'month', 'year'];
        // $articles->where('content', 'LIKE', '%'.request('filter.content').'%');
        // foreach(request('filter', []) as $filter => $value) {
        //     // El campo seri4a el $filter y el valor el $value

        //     // dump($filter);
        //     // dump($value);

        //     abort_unless(in_array($filter, $allowedFilters), 400);

        //     $articles->hasNamedScope($filter)
        //         ? $articles->{$filter}($value)
        //         : $articles->where($filter, 'LIKE', '%'.$value.'%');

        //     // if ($filter === 'year') {
        //     //     $articles->{$filter}($value);
        //         // $articles->year($value);
        //         // $articles->whereYear('created_at', $value);
        //     // } else if ($filter === 'month') {
        //         // $articles->{$filter}($value);
        //         // $articles->month($value);
        //         // $articles->whereMonth('created_at', $value);
        //     // } else if ($filter === 'title') {
        //         // $articles->{$filter}($value);
        //         // $articles->title($value);
        //         // $articles->where('title', 'LIKE', '%'.$value.'%');
        //     // } else if ($filter === 'content') {
        //         // $articles->{$filter}($value);
        //         // $articles->content($value);
        //         // $articles->where('content', 'LIKE', '%'.$value.'%');
        //     // }

        // }

        // $articles->allowedSorts(['title', 'content']);

        // $sortDirection = Str::of( $sortField)->startsWith('-')
        //     ? 'desc'
        //     : 'asc';
        // $sortField = ltrim($sortField, '-');

        // $articles = Article::all();
        // $articles = Article::orderBy('title', 'asc')->get();
        // $articles = Article::orderBy($sortField, 'asc')->get();

        // $articles = Article::orderBy($sortField, $sortDirection)->get();

        // return ArticleCollection::make(Article::all());
        // return ArticleCollection::make($articles);

        // dd(request('page.size'));

        return ArticleCollection::make(
            // $articles->get()
            // $articles->paginate(
            //     $perPage = request('page.size', 15),
            //     $columns = ['*'],
            //     $pageName = 'page[number]',
            //     $page = request('page.number', 1)
            // )->appends(request()->only('sort','page.size'))

            // $articles->jsonPaginate()
            $articles
        );
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

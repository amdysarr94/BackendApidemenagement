<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleEditRequest;
use App\Http\Requests\ArticleStoreRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articlesActifs = Article::where('statut', 'Actif')->paginate(10);
        return ArticleResource::collection($articlesActifs);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ArticleStoreRequest $request)
    {
        $article = new Article();
        $article->titre = $request->titre;
        $article->contenu = $request->contenu;
        $article->user_id = auth()->user()->id;
        $article->save();
        return response()->json([
            'status' => 'success',
            'Message' => 'Article ajouté avec succès',
            'Article' => $article->titre
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Article $article)
    {
        
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ArticleEditRequest $request, Article $article)
    {
        $article->titre = $request->titre;
        $article->contenu = $request->contenu;
        $article->update();
        return response()->json([
            "message"=>"Article modifié avec succès",
            "article"=> $article->titre
        ], 200);
    }
    public function activate(Article $article){
        $article->statut = 'Actif';
        $article->update();
        return response()->json([
            "message"=>"Article activé avec succès",
            "article"=> $article->titre
        ], 200);
    }
    public function desactivate(Article $article){
        $article->statut = 'Inactif';
        $article->update();
        return response()->json([
            "message"=>"Article désactivé avec succès",
            "article"=> $article->titre
        ], 200);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        $article->delete();
        return response()->json([
            "message"=>"Article supprimé avec succès",
            "article"=> $article->titre
        ], 200);
    }
}

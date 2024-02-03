<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Resources\ArticleResource;
use App\Http\Requests\ArticleEditRequest;
use App\Http\Requests\ArticleStoreRequest;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $articlesActifs = Article::where('statut', 'Actif')->paginate(10);
            return ArticleResource::collection($articlesActifs);        
        }catch(Exception $e){
            return response()->json($e);
        }

       
    }
    public function inactifPosts(){
        try{
            $articlesInactifs = Article::where('statut', 'Inactif')->paginate(10);
            return ArticleResource::collection($articlesInactifs);         
        }catch(Exception $e){
            return response()->json($e);
        }

        
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
        try{
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
        }catch(Exception $e){
            return response()->json($e);
        }

        
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        try{
            return response()->json(compact('article'), 200);        
        }catch(Exception $e){
            return response()->json($e);
        }

        
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
        try{
            $article->titre = $request->titre;
            $article->contenu = $request->contenu;
            $article->update();
            return response()->json([
                "message"=>"Article modifié avec succès",
                "article"=> $article->titre
            ], 200);      
        }catch(Exception $e){
            return response()->json($e);
        }

       
    }
    public function activate(Article $article){
        try{
            $article->statut = 'Actif';
            $article->update();
            return response()->json([
                "message"=>"Article activé avec succès",
                "article"=> $article->titre
            ], 200);          
        }catch(Exception $e){
            return response()->json($e);
        }

        
    }
    public function desactivate(Article $article){
        try{
            $article->statut = 'Inactif';
            $article->update();
            return response()->json([
                "message"=>"Article désactivé avec succès",
                "article"=> $article->titre
            ], 200);          
        }catch(Exception $e){
            return response()->json($e);
        }

        
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        try{
            $article->delete();
            return response()->json([
                "message"=>"Article supprimé avec succès",
                "article"=> $article->titre
            ], 200);         
        }catch(Exception $e){
            return response()->json($e);
        }

        
    }
}

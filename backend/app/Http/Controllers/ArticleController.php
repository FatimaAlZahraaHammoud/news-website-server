<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    function create_article(Request $request, $news_id){
        try{

            $news = News::find($news_id);
            if (!$news) {
                return response()->json([
                    "status" => "error",
                    "message" => "News not found"
                ], 404);
            }
        
            $article = new Article();
            $article->title = $request->title;
            $article->content = $request->content;
            $article->news_id = $news_id;
            $article->user_id = auth()->id();

            if ($request->hasFile('attachment')) {
                $article->attachment = $request->file('attachment')->storeAs(
                    "attachments/articles", 
                    uniqid() . '.' . $request->file('attachment')->getClientOriginalExtension()
                );
            }

            $success = $article->save();

            if (!$success) {
                return response()->json([
                    "status" => "error",
                    "message" => "Failed to add article"
                ], 500);
            }

            return response()->json([
                "status" => "success",
                "message" => "Article added successfully",
                "new_article" => $article,
            ], 201);
        }catch (\Exception $e){
            return response()->json([
                "status" => "error",
                "message" => "An error occurred while creating the article",
                "error" => $e->getMessage(),
            ],500);
        }
    }

    function update_article(Request $request){
        try{
            $article = Article::find($request->id);

            if (!$article) {
                return response()->json([
                    "status" => "error",
                    "message" => "Article not found"
                ], 404);
            }

            $article->title = $request->title;
            $article->content = $request->content;
            
            if ($request->hasFile('attachment')) {
                if ($article->attachment && Storage::exists($article->attachment)) {
                    Storage::delete($article->attachment);
                }
                $article->attachment = $request->file('attachment')->storeAs(
                    "attachments/articles", 
                    uniqid() . '.' . $request->file('attachment')->getClientOriginalExtension()
                );
            }

            $success = $article->save();

            if (!$success) {
                return response()->json([
                    "status" => "error",
                    "message" => "Failed to update article"
                ], 500);
            }

            return response()->json([
                "status" => "success",
                "message" => "News updated successfully",
                "updated_article" => $article,
            ]);
        }catch (\Exception $e) {
            // Handle exceptions
            return response()->json([
                "status" => "error",
                "message" => "An error occurred while updating the article",
                "error" => $e->getMessage(),
            ], 500);
        }
    }
}

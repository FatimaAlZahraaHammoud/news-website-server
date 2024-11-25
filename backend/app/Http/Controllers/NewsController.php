<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    // function to get news
    function get_news(Request $request){

        $user = $request->user();
        $userAge = now()->diffInYears($user->date_of_birth);
        $news = News::where('min_age', '<=', $userAge)->get();
        return response()->json([
            "status" => "success",
            "message" => "getting all news successfully",
            "news" => $news,
        ]);
    }

    // function to create news
    function create_news(Request $request){
        
        try{
            $news = new News();
            $news->title = $request->title;
            $news->content = $request->content;
            $news->min_age = $request->min_age;

            if ($request->attachment) {
                $news->attachment = $request->file('attachment')->storeAs(
                    "attachments/news", 
                    uniqid() . '.' . $request->file('attachment')->getClientOriginalExtension()
                );
            }
            $success = $news->save();

            if (!$success) {
                return response()->json([
                    "status" => "error",
                    "message" => "Failed to add news"
                ], 500);
            }

            return response()->json([
                "status" => "success",
                "message" => "News added successfully",
                "new_news" => $news,
            ], 201);
        }catch (\Exception $e){
            return response()->json([
                "status" => "error",
                "message" => "An error occurred while creating the news",
                "error" => $e->getMessage(),
            ], 500);
        }
    }

    // function to update news
    function update_news(Request $request){
        try{
            $news = News::find($request->id);

            if (!$news) {
                return response()->json([
                    "status" => "error",
                    "message" => "News not found"
                ], 404);
            }

            $news->title = $request->title;
            $news->content = $request->content;
            $news->min_age = $request->min_age;
            
            if ($request->attachment) {
                if ($news->attachment && Storage::exists($news->attachment)) {
                    Storage::delete($news->attachment);
                }
                $news->attachment = $request->file('attachment')->storeAs(
                    "attachments/news", 
                    uniqid() . '.' . $request->file('attachment')->getClientOriginalExtension()
                );
            }

            $success = $news->save();

            if (!$success) {
                return response()->json([
                    "status" => "error",
                    "message" => "Failed to update news"
                ], 500);
            }

            return response()->json([
                "status" => "success",
                "message" => "News updated successfully",
                "updated_news" => $news,
            ]);
        }catch (\Exception $e) {
            // Handle exceptions
            return response()->json([
                "status" => "error",
                "message" => "An error occurred while updating the news",
                "error" => $e->getMessage(),
            ], 500);
        }
    }

    // function to delete news
    function delete_news(Request $request){
        $news = News::find($request->id);

        if($news->attachment && Storage::exists($news->attachment)){
            Storage::delete($news->attachment);
        }
        
        try {
            $news->delete();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete news'], 500);
        }
        return response()->json([
            "status"=> "success",
            "message"=> "news is deleted successfully",
            "deleted_news"=> $news,
        ]);
    }
}

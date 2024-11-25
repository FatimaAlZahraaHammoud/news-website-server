<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
       // function to get news
       function get_news(){
            $news = News::all();
            return response()->json([
                "status" => "success",
                "message" => "getting all news successfully",
                "news" => $news,
            ]);
       }

       // function to create news
       function create_news(Request $request){
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
            $news->save();

            return response()->json([
                "status"=> "success",
                "message"=> "news added successfully",
                "new_news"=> $news,
            ]);
       }

       
}

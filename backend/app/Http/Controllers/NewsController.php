<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

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
}

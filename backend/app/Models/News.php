<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $fillable = ["title", "content", "min_age", "attachment", "admin_id"];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class VideoSummaries extends Model
{
    use HasFactory,HasApiTokens, Notifiable;

    protected $table = 'video_summaries';



}

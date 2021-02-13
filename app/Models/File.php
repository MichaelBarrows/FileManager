<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class File extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'local_file_location',
        'extension',
        'user_id',
        'downloads',
    ];

    public function user () {
        return $this->belongsTo(User::class);
    }

    public function date () {
        return Carbon::parse($this->created_at)->format('d/m/Y H:i');
    }
}

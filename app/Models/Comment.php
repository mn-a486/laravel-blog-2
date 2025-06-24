<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    # Comment - User
    # get tue owner of a comment
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

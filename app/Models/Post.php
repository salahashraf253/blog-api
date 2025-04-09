<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    // The attributes that are mass assignable
    protected $fillable = ['title', 'content', 'user_id'];

    // Define the relationship: Each post belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);  // Define the inverse of the hasMany relation
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{

    protected $table = 'cars';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'image',
        'price',
        'status',
    ];

    public $timestamps = true;

    /*
     * Relationships
    */

    // One to Many (Inverse)
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}

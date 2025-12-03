<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topspender extends Model
{
    use HasFactory, HasUuids;

    /**

     * The attributes that are mass assignable.

     *

     * @var array

     */

     protected $fillable = ['member','application','enddate'];

     /**

     * The model's default values for attributes.

     *

     * @var array

     */

    protected $attributes = [

        'point' => 0,

    ];

    /**

     * The "booted" method of the model.

     */

     protected static function booted(): void

     {
 
         static::saving(function (Topspender $topspender) {
 
            $topspender->application = config('app.name');
 
         });
 
     }
}
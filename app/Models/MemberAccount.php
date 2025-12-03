<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberAccount extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'type',
        'application',
        'phone',
        'email',
        'authid',
        'member',
        'verify',
    ];

}

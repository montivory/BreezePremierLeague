<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberAddress extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'id',
        'member',
        'application',
    ];

    public function getFulladdressAttribute()
    {
        return $this->address . " " . $this->road . " " . $this->subdistrict . " " . $this->district . " " . $this->province . " " . $this->zipcode;
    }
}

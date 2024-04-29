<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TagMember extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'office_id',
        'member_id',
        'label_id',
        'tag_id',
    ];

    public function label()
    {
        return $this->belongsTo(Label::class);
    }

    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }

}

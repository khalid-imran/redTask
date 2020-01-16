<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $table = 'project_card';

    public function images()
    {
        return $this->hasMany(CardFile::class, 'card_id', 'id')->with('images');
    }

}

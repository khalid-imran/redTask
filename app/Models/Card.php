<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $table = 'project_card';

    public function desc()
    {
        return $this->hasOne(CardDesc::Class, 'card_id', 'id');
    }
    public function task()
    {
        return $this->hasMany(Task::Class, 'card_id', 'id');
    }
    public function image()
    {
        return $this->hasMany(CardFile::Class, 'card_id', 'id');
    }

}

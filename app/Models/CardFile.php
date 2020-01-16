<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CardFile extends Model
{
    protected $table = 'project_card_file';

    public function images()
    {
        return $this->hasOne(Media::class, 'id', 'media_id')->select(array('id', 'file_name'));
    }
}

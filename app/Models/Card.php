<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $fillable = [
        'board_id',
        'column_id',
        'title',
        'description',
        'priority',
        'deadline'
    ];

    public function board()
    {
        return $this->belongsTo(Board::class);
    }

    public function column()
    {
        return $this->belongsTo(Column::class);
    }
}

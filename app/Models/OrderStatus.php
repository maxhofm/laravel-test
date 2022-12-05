<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    use HasFactory;

    const STATUS_NEW = 1;
    const STATUS_HANDLED = 2;

    public function isNew(): bool
    {
        return $this->id === self::STATUS_NEW;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    use HasFactory;

    protected $fillable = ['path'];

    /**
     * Добавляем удаление файла при удалении объекта
     * @return bool|null
     */
    public function delete(): ?bool
    {
        Storage::delete($this->path);
        return parent::delete();
    }

}

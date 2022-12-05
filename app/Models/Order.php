<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'text', 'client_id', 'file_id'];

    /**
     * Получение клиента заявки
     */
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Получение менеджера заявки
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * Получение статуса заявки
     */
    public function status()
    {
        return $this->belongsTo(OrderStatus::class);
    }

    /**
     * Получение файла заявки
     */
    public function file()
    {
        return $this->belongsTo(File::class);
    }

    /**
     * Проверка на наличие прикрепленного файла к заявке
     * @return bool
     */
    public function hasFile(): bool
    {
        return !empty($this->file);
    }

}

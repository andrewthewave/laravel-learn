<?php

namespace Shops\Domain\Models;

use App\Helpers\DomainModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Shops\Contracts\DataTransferObjects\ShopDto;

class Shop extends DomainModel
{
    use HasFactory;

    /**
     * Получить DTO-представление объекта для передачи из сервисного слоя.
     *
     * @return mixed DTO-объект, представляющий модель
     */
    public function toDto(): mixed
    {
        return new ShopDto(
            id: $this->id,
            title: $this->title,
            url: $this->url,
            created_at: $this->created_at,
            updated_at: $this->updated_at,
        );
    }

    /**
     * Заполняемые поля и правила валидации при сохранении из Data Transfer Objects
     * @return array
     */
    public function fillableRules(): array
    {
        return [
            'title' => ['required'],
            'url' => ['required','url'],
        ];
    }

    protected $guarded = [
        'id',
    ];
}

<?php

namespace Shops\Domain\Services;

use App\Contracts\DataTransferObjects\PaginatedListDto;
use App\Exceptions\EntityNotCreatedException;
use App\Exceptions\EntityNotFoundException;
use App\Exceptions\EntityNotUpdatedException;
use App\Helpers\DomainModelService;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Database\QueryException;
use Shops\Contracts\DataTransferObjects\ShopDto;
use Shops\Contracts\ShopServiceContract;
use Shops\Domain\Models\Shop;

class ShopService extends DomainModelService implements ShopServiceContract
{

    /**
     * @inheritDoc
     */
    public function getById(int $id): ShopDto
    {
        $shop = Shop::find($id);
        if (!$shop) {
            throw new EntityNotFoundException();
        }

        return $shop->toDto();
    }

    /**
     * @inheritDoc
     */
    public function list(): PaginatedListDto
    {
        $shops = Shop::query();
        Debugbar::info($this->toPaginatedListDto($shops));

        return $this->toPaginatedListDto($shops);
    }

    /**
     * @inheritDoc
     */
    public function create(string $title, string $url): ShopDto
    {
        $shop = new Shop();
        $this->validateAndFill($shop, [
            'title' => $title,
            'url' => $url,
        ]);

        try {
            $shop->save();
        } catch (QueryException $exception) {
            throw new EntityNotCreatedException($exception->getMessage());
        }

        return $shop->toDto();
    }

    /**
     * @inheritDoc
     */
    public function update(int $id, ?string $title, ?string $url): ShopDto
    {
        $shop = Shop::find($id);
        if (!$shop) {
            throw new EntityNotFoundException();
        }

        $this->validateAndFill($shop, [
            'title' => $title,
            'url' => $url,
        ]);

        try {
            $shop->save();
        } catch (QueryException $exception) {
            throw new EntityNotUpdatedException($exception->getMessage());
        }

        return $shop->toDto();
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id): void
    {
        $shop = Shop::find($id);
        if (!$shop) {
            throw new EntityNotFoundException();
        }

        $shop->delete();
    }
}
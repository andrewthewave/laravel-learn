<?php

namespace Shops\Application\Http\Controllers;

use App\Exceptions\EntityNotCreatedException;
use App\Exceptions\EntityNotDeletedException;
use App\Exceptions\EntityNotFoundException;
use App\Exceptions\EntityNotUpdatedException;
use App\Exceptions\EntityValidationException;
use App\Helpers\DomainModelController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Shops\Contracts\DataTransferObjects\ShopDto;
use Shops\Domain\Services\ShopService;

class ShopController extends Controller
{
    use DomainModelController;

    public function __construct(
        private ShopService $shopService
    ) {
    }

    /**
     * Список магазинов
     * GET /shops
     */
    public function index()
    {
        $paginatedShops= $this->shopService->list();

        return Inertia::render('Shops/Index', [
            'shops' => $this->outputPaginatedList($paginatedShops, fn(ShopDto $shop) => [
                'id' => $shop->id,
                'title' => $shop->title,
                'url' => $shop->url,
                'created_at' => $shop->created_at,
            ]),
        ]);
    }

    /**
     * Форма создания магазина
     * GET /shops/create
     */
    public function create()
    {
        return Inertia::render('Shops/Create');
    }

    /**
     * Сохранение созданного магазина
     * POST /shops
     */
    public function store(Request $request)
    {
        try {
            $this->shopService->create(
                title: $request->string('title'),
                url: $request->string('url'),
            );
        } catch (EntityValidationException $exception) {
            return back()->withErrors($exception->messages);
        } catch (EntityNotCreatedException $exception) {
            return back()->withErrors(['title' => 'Не удается создать магазин: ' . $exception->getMessage()]);
        }

        return Redirect::route('shops.index');
    }

    /**
     * Страница магазина
     * GET /shop/{id}
     */
    public function show(Request $request, int $shop)
    {
        try {
            $shopDto = $this->shopService->getById($shop);
        } catch (EntityNotFoundException $exception) {
            abort(Response::HTTP_NOT_FOUND);
        }

        return Inertia::render('Shops/Show', [
            'shop' => (array)$shopDto,
        ]);
    }

    /**
     * Форма редактирования магазина
     * GET /shops/{id}/edit
     */
    public function edit(Request $request, int $shop)
    {
        try {
            $shopDto = $this->shopService->getById($shop);
        } catch (EntityNotFoundException $exception) {
            abort(Response::HTTP_NOT_FOUND);
        }

        return Inertia::render('Shops/Edit', [
            'id' => $shopDto->id,
            'values' => $shopDto,
        ]);
    }

    /**
     * Сохранение редактируемого магазина
     * PUT /shops/{id}
     */
    public function update(Request $request, int $shop)
    {
        try {
            $shopDto = $this->shopService->getById($shop);

            $this->shopService->update(
                id: $shopDto->id,
                title: $request->stringOrNull('title'),
                url: $request->stringOrNull('url'),
            );
        } catch (EntityNotFoundException $exception) {
            abort(Response::HTTP_NOT_FOUND);
        } catch (EntityValidationException $exception) {
            return back()->withErrors($exception->messages);
        } catch (EntityNotUpdatedException $exception) {
            return back()->withErrors(['title' => 'Не удается отредактировать магазин: ' . $exception->message]);
        }

        return Redirect::route('shops.index');
    }

    /**
     * Удаление пользователя
     * DELETE /users/{id}
     */
    public function destroy(Request $request, int $shop)
    {
        try {
            $userDto = $this->shopService->getById($shop);
            $this->shopService->delete($userDto->id);
        } catch (EntityNotFoundException $exception) {
            abort(Response::HTTP_NOT_FOUND);
        } catch (EntityNotDeletedException $exception) {
            return back()->withErrors(['id' => 'Не удается удалить пользователя: ' . $exception->message]);
        }

        return Redirect::back(303);
    }

}


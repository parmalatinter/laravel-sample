<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateItemAPIRequest;
use App\Http\Requests\API\UpdateItemAPIRequest;
use App\Models\Item;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;

/**
 * Class ItemController
 *
 * create by How to generate API in few minutes using InfyOm Laravel Generator
 * https://www.youtube.com/watch?v=W58PIXo0C40
 */

class ItemAPIController extends AppBaseController
{
    /**
     * @OA\Get(
     *      path="/items",
     *      summary="getItemList",
     *      tags={"Item"},
     *      description="Get all Items",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/Item")
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $query = Item::query();
        $totalRowCount = Item::query()->count();

        if ($request->get('skip')) {
            $query->skip($request->get('skip'));
        }
        if ($request->get('limit')) {
            $query->limit($request->get('limit'));
        }
        if ($request->get('sortBy')) {
            $query->orderBy($request->get('sortBy'));
        }
        if ($request->get('search')) {
            $query->where(function($query) use ($request) {
                $model = $query->getModel();
                foreach ($model->fillable as $column){
                    $query->orWhere($column, 'LIKE', '%'.$request->get('search').'%');
                }
            });
        }

        $items = $query->get();

        return $this->sendResponse([
                'rows' => $items->toArray(),
                'totalRowCount' => $totalRowCount
            ], 'Items retrieved successfully');
    }

    /**
     * @OA\Post(
     *      path="/items",
     *      summary="createItem",
     *      tags={"Item"},
     *      description="Create Item",
     *      @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(ref="#/components/schemas/Item")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  ref="#/components/schemas/Item"
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateItemAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        /** @var Item $item */
        $item = Item::create($input);

        return $this->sendResponse($item->toArray(), 'Item saved successfully');
    }

    /**
     * @OA\Get(
     *      path="/items/{id}",
     *      summary="getItemItem",
     *      tags={"Item"},
     *      description="Get Item",
     *      @OA\Parameter(
     *          name="id",
     *          description="id of Item",
     *           @OA\Schema(
     *             type="integer"
     *          ),
     *          required=true,
     *          in="path"
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  ref="#/components/schemas/Item"
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function show($id): JsonResponse
    {
        /** @var Item $item */
        $item = Item::find($id);

        if (empty($item)) {
            return $this->sendError('Item not found');
        }

        return $this->sendResponse($item->toArray(), 'Item retrieved successfully');
    }

    /**
     * @OA\Put(
     *      path="/items/{id}",
     *      summary="updateItem",
     *      tags={"Item"},
     *      description="Update Item",
     *      @OA\Parameter(
     *          name="id",
     *          description="id of Item",
     *           @OA\Schema(
     *             type="integer"
     *          ),
     *          required=true,
     *          in="path"
     *      ),
     *      @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(ref="#/components/schemas/Item")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  ref="#/components/schemas/Item"
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateItemAPIRequest $request): JsonResponse
    {
        /** @var Item $item */
        $item = Item::find($id);

        if (empty($item)) {
            return $this->sendError('Item not found');
        }

        $item->fill($request->all());
        $item->save();

        return $this->sendResponse($item->toArray(), 'Item updated successfully');
    }

    /**
     * @OA\Delete(
     *      path="/items/{id}",
     *      summary="deleteItem",
     *      tags={"Item"},
     *      description="Delete Item",
     *      @OA\Parameter(
     *          name="id",
     *          description="id of Item",
     *           @OA\Schema(
     *             type="integer"
     *          ),
     *          required=true,
     *          in="path"
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  type="string"
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function destroy($id): JsonResponse
    {
        /** @var Item $item */
        $item = Item::find($id);

        if (empty($item)) {
            return $this->sendError('Item not found');
        }

        $item->delete();

        return $this->sendSuccess('Item deleted successfully');
    }
}

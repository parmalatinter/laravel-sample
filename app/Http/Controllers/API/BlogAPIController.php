<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateBlogAPIRequest;
use App\Http\Requests\API\UpdateBlogAPIRequest;
use App\Models\Blog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;

/**
 * Class ItemController
 *
 * create by How to generate API in few minutes using InfyOm Laravel Generator
 * https://www.youtube.com/watch?v=W58PIXo0C40
 */

class BlogAPIController extends AppBaseController
{
    /**
     * @OA\Get(
     *      path="/blogs",
     *      summary="getBlogList",
     *      tags={"Blog"},
     *      description="Get all Blogs",
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
     *                  @OA\Items(ref="#/components/schemas/Blog")
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
        $query = Blog::query();

        if ($request->get('search')) {
            $query->where(function($query) use ($request) {
                $model = $query->getModel();
                foreach ($model->fillable as $column){
                    $query->orWhere($column, 'LIKE', '%'.$request->get('search').'%');
                }
            });
        }

        $totalRowCount = $query->count();

        if ($request->get('sortBy')) {
            $query->orderBy($request->get('sortBy'));
        }

        if ($request->get('skip')) {
            $query->skip($request->get('skip'));
        }
        if ($request->get('limit')) {
            $query->limit($request->get('limit'));
        }

        $items = $query->get();

        return $this->sendResponse([
            'rows' => $items->toArray(),
            'totalRowCount' => $totalRowCount
        ], 'Blogs retrieved successfully');
    }

    /**
     * @OA\Post(
     *      path="/blogs",
     *      summary="createBlog",
     *      tags={"Blog"},
     *      description="Create Blog",
     *      @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(ref="#/components/schemas/Blog")
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
     *                  ref="#/components/schemas/Blog"
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateBlogAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        /** @var Blog $blog */
        $blog = Blog::create($input);

        return $this->sendResponse($blog->toArray(), 'Blog saved successfully');
    }

    /**
     * @OA\Get(
     *      path="/blogs/{id}",
     *      summary="getBlogItem",
     *      tags={"Blog"},
     *      description="Get Blog",
     *      @OA\Parameter(
     *          name="id",
     *          description="id of Blog",
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
     *                  ref="#/components/schemas/Blog"
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
        /** @var Blog $blog */
        $blog = Blog::find($id);

        if (empty($blog)) {
            return $this->sendError('Blog not found');
        }

        return $this->sendResponse($blog->toArray(), 'Blog retrieved successfully');
    }

    /**
     * @OA\Put(
     *      path="/blogs/{id}",
     *      summary="updateBlog",
     *      tags={"Blog"},
     *      description="Update Blog",
     *      @OA\Parameter(
     *          name="id",
     *          description="id of Blog",
     *           @OA\Schema(
     *             type="integer"
     *          ),
     *          required=true,
     *          in="path"
     *      ),
     *      @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(ref="#/components/schemas/Blog")
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
     *                  ref="#/components/schemas/Blog"
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateBlogAPIRequest $request): JsonResponse
    {
        /** @var Blog $blog */
        $blog = Blog::find($id);

        if (empty($blog)) {
            return $this->sendError('Blog not found');
        }

        $blog->fill($request->all());
        $blog->save();

        return $this->sendResponse($blog->toArray(), 'Blog updated successfully');
    }

    /**
     * @OA\Delete(
     *      path="/blogs/{id}",
     *      summary="deleteBlog",
     *      tags={"Blog"},
     *      description="Delete Blog",
     *      @OA\Parameter(
     *          name="id",
     *          description="id of Blog",
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
        /** @var Blog $blog */
        $blog = Blog::find($id);

        if (empty($blog)) {
            return $this->sendError('Blog not found');
        }

        $blog->delete();

        return $this->sendSuccess('Blog deleted successfully');
    }
}

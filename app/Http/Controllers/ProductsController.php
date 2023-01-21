<?php

namespace App\Http\Controllers;

use Exception;
use App\Http\Requests\ProductCreateRequest;
use App\Repositories\ProductRepository;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    use ResponseTrait;

    public $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @OA\Get(
     *     path="/api/products",
     *     tags={"Products"},
     *     summary="Get all products for REST API",
     *     description="Multiple status values can be provided with comma separated string",
     *     operationId="index",
     *     @OA\Parameter(
     *         name="perPage",
     *         in="query",
     *         description="Per page count",
     *         required=false,
     *         explode=true,
     *         @OA\Schema(
     *             default="10",
     *             type="integer",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search by title",
     *         required=false,
     *         explode=true,
     *         @OA\Schema(
     *             default="",
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="orderBy",
     *         in="query",
     *         description="Order By column name",
     *         required=false,
     *         explode=true,
     *         @OA\Schema(
     *             default="id",
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="order",
     *         in="query",
     *         description="Order ordering - asc or desc",
     *         required=false,
     *         explode=true,
     *         @OA\Schema(
     *             default="desc",
     *             type="string",
     *         )
     *     ),
     *     security={{"bearer":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid status value"
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        try {
            return $this->responseSuccess($this->productRepository->getAll(request()->all()), 'Product fetched successfully.');
        } catch (Exception $e) {
            return $this->responseError([], $e->getMessage());
        }
    }

    /**
     * @OA\POST(
     *     path="/api/products",
     *     tags={"Products"},
     *     summary="Create product",
     *     description="Create product",
     *     operationId="store",
     *     security={{"bearer":{}}},
     *     @OA\RequestBody(
     *         description="Product objects",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *            @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="title",
     *                     description="Product title",
     *                     type="string",
     *                     example="Product title"
     *                 ),
     *                 @OA\Property(
     *                     property="slug",
     *                     description="Product slug",
     *                     type="string",
     *                     example="product-title"
     *                 ),
     *                 @OA\Property(
     *                     property="price",
     *                     description="Product price",
     *                     type="integer",
     *                     example="200"
     *                 ),
     *                 @OA\Property(
     *                     property="image",
     *                     description="Product image",
     *                     type="string",
     *                     format="binary"
     *                 ),
     *                 required={"title", "price"}
     *             )
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
    public function store(ProductCreateRequest $request): JsonResponse
    {
        try {
            return $this->responseSuccess($this->productRepository->create($request->all()), 'Product created successfully.');
        } catch (Exception $e) {
            return $this->responseError([], $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            return $this->responseSuccess([], 'Product fetched successfully.');
        } catch (Exception $e) {
            return $this->responseError([], $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

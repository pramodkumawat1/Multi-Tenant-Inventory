<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helper\ResponseBuilder;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Http\Requests\ProductRequest;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $paginate = $request->paginattion ?? 10;
        $data = Product::where('store_details_id',auth()?->user()?->store?->id)->paginate($paginate);
        $this->response = new ProductCollection($data);
        return ResponseBuilder::successWithPagination($data, $this->response, trans('global.all_products'), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        $this->authorize('create', Product::class);
        $product = Product::create($request->validated() + ['store_details_id' => auth()?->user()?->store?->id]);
        return ResponseBuilder::successMessage(trans('global.product_created'),201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // try {
        $product = Product::findOrFail($id);
        $this->authorize('view', $product);

        return ResponseBuilder::success(trans('global.product_detail'),200,new ProductResource($product));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request,Product $product)
    {
        $this->authorize('update', $product);

        $product->update($request->validated());

        return ResponseBuilder::success(trans('global.product_updated'),200,new ProductResource($product));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);
        $product->delete();
        return ResponseBuilder::successMessage(trans('global.product_deleted'),200);
    }
}

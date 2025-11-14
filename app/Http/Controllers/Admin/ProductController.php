<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('product_access');
        $query = Product::query();

        $keyword = $request->input('keyword', '');
        $query->where('store_details_id', Auth::user()?->store?->id)->where(function ($query1) use ($keyword) {
            $query1->where('name', 'like', '%'.$keyword.'%')
            ->orwhere('sku', 'like', '%'.$keyword.'%');
        });

        if(isset($request->items)){
            $data['items'] = $request->items;
        }
        else{
            $data['items'] = 10;
        }
        
        $data['data'] = $query->orderBy('created_at','DESC')->paginate($data['items']);

        return view('admin.product.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('product_create');
        return view('admin.product.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        $this->authorize('create', Product::class);
        Product::create($request->validated() + ['store_details_id' => auth()?->user()?->store?->id]);
        return redirect()->route('products.index')->with('success', 'Product created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $this->authorize('view', $product);
        $data['data'] = $product;
        return view('admin.product.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $this->authorize('view', $product);
        $data['data'] = $product;
        return view('admin.product.create', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product)
    {
        $this->authorize('update', $product);
        $product->update($request->validated());
        return redirect()->route('products.index')->with('success', 'Product updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully');
    }
}

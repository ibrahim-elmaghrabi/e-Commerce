<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Product;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Api\ProductResource;
use App\Http\Requests\Api\Admin\ProductRequest;

class ProductController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->apiResponse(true, "Success",
        ProductResource::collection(Product::with('store', 'category', 'sizes','images')
        ->paginate(5)));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request, Product $product)
    {
        $product->fill($request->validated()+['user_id' => auth()->user()->id])->save();
        foreach ($request->images as $image) {
           // $image->store('products', 'public');
            $product->images()->create($image);
        }
        foreach ($request->validated(['sizes']) as $size) {
		  $newSize = $product->sizes()->create(array_except($size, ['colors']));
          $newSize->colors()->attach($size['colors']);
		}
        return $this->apiResponse(true, "Product Created Successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->ApiResponse(true, "Success",
            new ProductResource(Product::with('category', 'store', 'store', 'images', 'sizes')
            ->findOrFail($id)));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
         return $this->ApiResponse(true, "Success",
            new ProductResource(Product::with('category', 'store', 'store', 'images', 'sizes')
            ->findOrFail($id)));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update($request->validated());
        if ($request->has(['images'])) {
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image);
                $image->delete();
            }
            foreach ($request->images as $image) {
                $image->store('products', 'public');
                $product->images()->updateOrCreate($image);
            }
        }
        foreach ($request->validated()['sizes'] as $size) {
            $newSize = $product->sizes()->updateOrCreate(array_except($size, ['colors']));
            $newSize->colors()->sync($size['colors']);
        }
        return $this->apiResponse(true, "Product Updated Successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        foreach ($product->images as $image) {
             Storage::disk('public')->delete($image);
        }
        $product->images()->delete();
        $product->sizes()->each(function ($size) {
            $size->colors()->detach();
        });
        $product->sizes()->delete();
        $product->delete();
        return $this->apiResponse(true, "Product Deleted successfully");
    }
}

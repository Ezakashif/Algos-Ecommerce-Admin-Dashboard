<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Storage;


class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
{
    $products = Product::with('primaryImage')->whereHas('categories', function ($q) {
        $q->whereNull('deleted_at');
    })
    ->with([
        'categories' => function ($q) {
            $q->whereNull('deleted_at');
        },
        'categories.parent',
        'variants'
    ])->orderby('created_at', 'desc')
    ->paginate(25);

    return view('admin.products.index', compact('products'));
}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $subcategories = Category::whereNotNull('parent_id')->get();
        return view('admin.products.create', compact('subcategories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     
public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string',
        'base_price' => 'required|numeric',
        'description' => 'nullable|string',
        'sku' => 'required|unique:products,sku',
        'attributes.size' => 'nullable|string',
        'attributes.color' => 'nullable|string',
        'stock_quantity' => 'required|numeric',
        'has_variant' => 'required|string',
        'category_id' => 'required|exists:categories,id',
        'product_images.*' => 'nullable|image',

        'stock_quantity' => 'nullable|integer|min:0', // For product-level stock

        'variants' => 'nullable|array', // Now variants are optional
        'variants.*.sku' => 'required_with:variants|string|distinct|unique:product_variants,sku',
        'variants.*.attributes.size' => 'required_with:variants|string',
        'variants.*.attributes.color' => 'required_with:variants|string',
        'variants.*.price_override' => 'nullable|numeric',
        'variants.*.images.*' => 'nullable|image',
        'variants.*.stock_quantity' => 'required_with:variants|integer|min:0',
    ]);

    // Create product
   $product = Product::create([
    'name' => $validated['name'],
    'description' => $validated['description'] ?? null,
    'sku' => $validated['sku'],
    'attributes' => $validated['attributes'] ?? [],
    'base_price' => $validated['base_price'],
    'stock_quantity' => $validated['stock_quantity'],
    'has_variant' => $validated['has_variant'],
]);


    $product->categories()->attach($validated['category_id']);

    // Handle product images as before
    if ($request->hasFile('product_images')) {
        foreach ($request->file('product_images') as $index => $image) {
            $path = $image->store("products/{$product->id}", 'public');
            $product->images()->create([
                'path' => $path,
                'is_primary' => $index === 0,
                'sort_order' => $index,
            ]);
        }
    }

    // Handle variants if any
    if (!empty($validated['variants'])) {
        foreach ($validated['variants'] as $index => $variant) {
            $variantModel = $product->variants()->create([
                'sku' => $variant['sku'],
                'attributes' => $variant['attributes'],
                'price_override' => $variant['price_override'] ?? null,
                'stock_quantity' => $variant['stock_quantity'],
            ]);

            // Create inventory for variant
            Inventory::create([
                'product_variant_id' => $variantModel->id,
                'stock_quantity' => $variant['stock_quantity'],
            ]);

            // Handle variant images as before
            $variantImages = data_get($request->variants, "{$index}.images");
            if ($variantImages && is_array($variantImages)) {
                foreach ($variantImages as $imgIndex => $imgFile) {
                    $path = $imgFile->store("products/variants/{$variant['sku']}", 'public');
                    $variantModel->images()->create([
                        'path' => $path,
                        'is_primary' => $imgIndex === 0,
                        'sort_order' => $imgIndex,
                    ]);
                }
            }
        }
    }

    return redirect()->route('admin.products.index')->with('success', 'Product and variants created successfully.');
}





    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $product = Products::find($id);
        // return $product;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::with('categories')->findOrFail($id);
        $subcategories = Category::whereNotNull('parent_id')->get();
        return view('admin.products.update', compact('product','subcategories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


public function update(Request $request, Product $product)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'base_price' => 'required|numeric',
        'description' => 'nullable|string',
        'sku' => 'required|unique:products,sku',
        'attributes.size' => 'nullable|string',
        'attributes.color' => 'nullable|string',
        'stock_quantity' => 'required|numeric',
        'category_id' => 'required|exists:categories,id',

        // Product images
        'product_images.*' => 'nullable|image|max:2048',

        // Variants
        'variants' => 'nullable|array',
        'variants.*.sku' => 'required|string',
        'variants.*.attributes.size' => 'nullable|string',
        'variants.*.attributes.color' => 'required|string',
        'variants.*.price_override' => 'nullable|numeric',
        'variants.*.images.*' => 'nullable|image|max:2048',
    ]);

    /* -------------------------------
     | Update product basic info
     |--------------------------------*/
    $product->update([
        'name' => $validated['name'],
        'base_price' => $validated['base_price'],
        'description' => $validated['description'] ?? null,
        'sku' => $validated['sku'],
        'attributes' => $validated['attributes'] ?? [],
        'stock_quantity' => $validated['stock_quantity'],
    ]);

    $product->categories()->sync([$validated['category_id']]);

    /* -------------------------------
     | Update PRODUCT images
     |--------------------------------*/
    if ($request->hasFile('product_images')) {

        // Delete old images
        foreach ($product->images as $oldImage) {
            Storage::disk('public')->delete($oldImage->path);
            $oldImage->delete();
        }

        // Save new images
        foreach ($request->file('product_images') as $index => $image) {
            $path = $image->store("products/{$product->id}", 'public');

            $product->images()->create([
                'path' => $path,
                'is_primary' => $index === 0,
                'sort_order' => $index,
            ]);
        }
    }

    /* -------------------------------
     | Update / Create VARIANTS
     |--------------------------------*/
    if (!empty($validated['variants'])) {

        foreach ($validated['variants'] as $index => $variantData) {

            $variant = ProductVariant::updateOrCreate(
                [
                    'product_id' => $product->id,
                    'sku' => $variantData['sku'],
                ],
                [
                    'attributes' => $variantData['attributes'] ?? [],
                    'price_override' => $variantData['price_override'] ?? null,
                    'stock_quantity' => $variantData['stock_quantity'] ?? 0,
                ]
            );

            /* ---------------------------
             | Update variant images
             |----------------------------*/
            if (isset($request->variants[$index]['images'])) {

                // Delete old images
                foreach ($variant->images as $oldImage) {
                    Storage::disk('public')->delete($oldImage->path);
                    $oldImage->delete();
                }

                // Save new images
                foreach ($request->variants[$index]['images'] as $imgIndex => $imageFile) {
                    $path = $imageFile->store(
                        "products/variants/{$variant->sku}",
                        'public'
                    );

                    $variant->images()->create([
                        'path' => $path,
                        'is_primary' => $imgIndex === 0,
                        'sort_order' => $imgIndex,
                    ]);
                }
            }
        }
    }

    return redirect()
        ->route('admin.products.index')
        ->with('success', 'Product updated successfully.');
}




    public function destroyVariant($variantId)
{
    $variant = ProductVariant::findOrFail($variantId);
    $variant->delete();

    return redirect()->back()->with('success', 'Product variant deleted successfully.');
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
{
    $product->delete();

    return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
}
}

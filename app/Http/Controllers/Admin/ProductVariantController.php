<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use App\Models\Product;
class ProductVariantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function indexByProduct(Product $product)
    {
        $variants = $product->variants()->with('images')->get();
        return view('admin.productVariants.index', compact('product', 'variants'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $productId = $request->query('product');
        $product = Product::findOrFail($productId); // required now
        return view('admin.productVariants.create', compact('product'));
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // ProductVariantController.php

        public function store(Request $request)
        {
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',
                'sku' => 'required|unique:product_variants,sku',
                'attributes.size' => 'nullable|string',
                'attributes.color' => 'nullable|string',
                'price_override' => 'nullable|numeric',
                'images.*' => 'nullable|image|max:2048',
            ]);

            $variant = ProductVariant::create([
                'product_id' => $validated['product_id'],
                'sku' => $validated['sku'],
                'attributes' => $validated['attributes'] ?? [],
                'price_override' => $validated['price_override'],
            ]);

               // Update product to indicate it has at least one variant
            $variant->product->update(['has_variant' => 1]);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $imageFile) {
                    $path = $imageFile->store('variant_images', 'public');
                    $variant->images()->create(['path' => $path]);
                }
            }

            return redirect()->route('admin.products.variants.index', $variant->product_id)
                ->with('success', 'Variant added successfully.');
        }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductVariant $productVariant)
    {
        $products = Product::all(); // In case you want to allow changing the product
        $variant = $productVariant->load('images'); // Ensure images are eager loaded
        return view('admin.productVariants.update', compact('variant', 'products'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
  
      public function update(Request $request, ProductVariant $productVariant)
{
    $validated = $request->validate([
        'sku' => 'required|string|unique:product_variants,sku,' . $productVariant->id,
        'attributes.size' => 'nullable|string',
        'attributes.color' => 'required|string',
        'price_override' => 'nullable|numeric',
        'images.*' => 'nullable|image|max:2048',
        'delete_images' => 'nullable|array',
        'delete_images.*' => 'integer|exists:product_images,id',
    ]);

    // Update the variant
    $productVariant->update([
        'sku' => $validated['sku'],
        'attributes' => $validated['attributes'],
        'price_override' => $validated['price_override'],
    ]);

    // Delete selected images
    if (!empty($validated['delete_images'])) {
        foreach ($validated['delete_images'] as $imgId) {
            $image = ProductImage::where('product_variant_id', $productVariant->id)->find($imgId);
            if ($image) {
                \Storage::delete($image->path); // deletes from storage
                $image->delete(); // deletes from DB
            }
        }
    }

    // Store new images
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $file) {
            $path = $file->store('product_images', 'public');
            $productVariant->images()->create([
                'path' => $path,
                'is_primary' => false,
            ]);
        }
    }

    return redirect()->route('admin.products.variants.index', $productVariant->product_id)
    ->with('success', 'Variant updated successfully.');
}


    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
 
         public function destroy(ProductVariant $productVariant)
    {
        $product = $productVariant->product; // Cache the product before deletion

        $productVariant->delete();

        // If no more variants exist for the product, update has_variant to null
        if ($product->variants()->count() === 0) {
            $product->update(['has_variant' => null]);
        }

        
        return back()->with('success', 'Variant deleted successfully.');
    }

    public function destroyImage($imageId)
    {
        $image = \App\Models\ProductVariantImage::findOrFail($imageId);
        Storage::delete($image->path);
        $image->delete();

        return back()->with('success', 'Image deleted successfully.');
    }

    
}

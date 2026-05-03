@include('layouts.header')
@include('layouts.sidebar')
@include('layouts.navbar')

<div class="bg-light rounded h-100 p-4">
    <h4 class="mb-4">Edit Product</h4>

    <!-- Main Product Update Form -->
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Product Fields -->
        <div class="form-floating mb-3">
            <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
            <label>Product Name</label>
        </div>

        <div class="form-floating mb-3">
            <input type="number" name="base_price" class="form-control" value="{{ old('base_price', $product->base_price) }}" required>
            <label>Base Price</label>
        </div>

         <div class="form-floating mb-3">
            <input type="text" name="sku" class="form-control" value="{{ old('sku', $product->sku) }}" required>
            <label>SKU</label>
        </div>

         <div class="form-floating mb-3">
            <input type="text" name="attributes[size]" class="form-control" value="{{ old('attributes.size', $product->attributes['size'] ?? '') }}" required>
            <label>Size</label>
        </div>

      <div class="col-md-12 d-flex align-items-center gap-4 mb-4 
            bg-white border border-light rounded p-3 shadow-sm">
            <label class="mb-0 text-secondary fw-semibold">Color</label>
            <input type="color"
                name="attributes[color]"
                class="form-control form-control-color"
                value="{{ old('attributes.color', $product->attributes['color'] ?? '#000000') }}">
        </div>

         <div class="form-floating mb-3">
            <input type="number" name="stock_quantity" class="form-control" value="{{ old('stock_quantity', $product->stock_quantity) }}" required>
            <label>Stock Quantity</label>
        </div>

        <div class="form-floating mb-3">
            <textarea name="description" class="form-control" style="height: 100px">{{ old('description', $product->description) }}</textarea>
            <label>Description</label>
        </div>

        <div class="form-floating mb-3">
            <select name="category_id" class="form-select" required>
                @foreach($subcategories as $cat)
                    <option value="{{ $cat->id }}" {{ $product->categories->first()->id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <label>Select Subcategory</label>
        </div>

        <div class="mb-3">
            <input type="file" name="product_images[]" class="form-control" multiple>
        </div>

        <hr>

      
        <!-- Submit -->
        <button type="submit" class="btn btn-success mt-2">Update Product</button>
    </form>

    <hr>


@include('layouts.footer')

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

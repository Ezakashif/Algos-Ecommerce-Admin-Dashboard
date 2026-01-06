@include('layouts.header')
@include('layouts.sidebar')
@include('layouts.navbar')

<div class="bg-light rounded h-100 p-4">
    <h6 class="mb-4">Add Product</h6>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> Please correct the errors below.
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Product Info -->
        <div class="form-floating mb-3">
            <input type="text" name="name" class="form-control" placeholder="Product Name" required>
            <label>Product Name</label>
        </div>

        <div class="form-floating mb-3">
            <input type="number" name="base_price" class="form-control" placeholder="Base Price" required>
            <label>Base Price</label>
        </div>

        <div class="form-floating mb-3">
            <textarea name="description" class="form-control" placeholder="Description" style="height: 100px"></textarea>
            <label>Description</label>
        </div>

        <input type="hidden" name="has_variant" value="NULL">

        <div class="form-floating mb-3">
            <select name="category_id" class="form-select" required>
                <option value="">-- Select Subcategory --</option>
                @foreach($subcategories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
            <label>Select Subcategory</label>
        </div>

        <!-- Product Images -->
        <div class="mb-3">
            <input type="file" name="product_images[]" class="form-control" multiple>
        </div>

        <hr>
        <button type="submit" class="btn btn-success">Create Product</button>
    </form>
</div>


@include('layouts.footer')

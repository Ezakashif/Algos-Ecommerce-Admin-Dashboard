@include('layouts.header')
@include('layouts.sidebar')
@include('layouts.navbar')

<div class="bg-light rounded h-100 p-4">
    <h6 class="mb-4">Add Variant for: <strong>{{ $product->name }}</strong></h6>

    <form action="{{ route('admin.productVariants.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">

        <div class="row g-2">
            <div class="col-md-4">
                <label>SKU</label>
                <input type="text" name="sku" class="form-control" required>
            </div>

            <div class="col-md-2">
                <label>Size</label>
                <input type="text" name="attributes[size]" class="form-control">
            </div>

            <div class="col-md-2">
                <label>Color</label>
                <input type="color" name="attributes[color]" class="form-control form-control-color" value="#000000">
            </div>

            <div class="col-md-2">
                <label>Price Override</label>
                <input type="number" step="0.01" name="price_override" class="form-control">
            </div>
        </div>

        <div class="mt-3">
            <label>Variant Images</label>
            <div id="variant-images-wrapper">
                <div class="d-flex align-items-center mb-2">
                    <input type="file" name="images[]" class="form-control me-2">
                    <button type="button" class="btn btn-danger btn-sm remove-image">&times;</button>
                </div>
            </div>
            <button type="button" class="btn btn-secondary btn-sm mt-2" id="add-image">Add More Images</button>
        </div>

        <button type="submit" class="btn btn-primary w-100 mt-4">Create Variant</button>
    </form>
</div>

<script>
    document.getElementById('add-image').addEventListener('click', () => {
        const wrapper = document.getElementById('variant-images-wrapper');
        const field = `
            <div class="d-flex align-items-center mb-2">
                <input type="file" name="images[]" class="form-control me-2">
                <button type="button" class="btn btn-danger btn-sm remove-image">&times;</button>
            </div>`;
        wrapper.insertAdjacentHTML('beforeend', field);
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-image')) {
            e.target.closest('div').remove();
        }
    });
</script>

@include('layouts.footer')

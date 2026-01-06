@include('layouts.header')
@include('layouts.sidebar')
@include('layouts.navbar')

<div class="bg-light rounded h-100 p-4">
    <h6 class="mb-4">Edit Variant</h6>

    <form action="{{ route('admin.productVariants.update', $variant->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-3">
            <div class="col-md-3">
                <label>SKU</label>
                <input type="text" name="sku" class="form-control" value="{{ $variant->sku }}" required>
            </div>

            <div class="col-md-2">
                <label>Size</label>
                <input type="text" name="attributes[size]" class="form-control" value="{{ $variant->attributes['size'] ?? '' }}">
            </div>

            <div class="col-md-2">
                <label>Color</label>
                <input type="color" name="attributes[color]" class="form-control form-control-color"
                    value="{{ $variant->attributes['color'] ?? '#000000' }}">
            </div>

            <div class="col-md-3">
                <label>Price Override</label>
                <input type="number" step="0.01" name="price_override" class="form-control"
                    value="{{ $variant->price_override }}">
            </div>
        </div>

        <div class="mt-4">
            <label>Upload Additional Images</label>
            <div id="variant-images-wrapper">
                <div class="d-flex align-items-center mb-2">
                    <input type="file" name="images[]" class="form-control me-2">
                    <button type="button" class="btn btn-sm btn-outline-danger remove-image">&times;</button>
                </div>
            </div>
            <button type="button" class="btn btn-secondary btn-sm mt-2" id="add-image">Add More Images</button>
        </div>

        <div class="mt-4">
            <label>Existing Images</label>
            <div class="row">
                @forelse ($variant->images as $image)
                 <div class="col-md-3 mb-3 position-relative existing-image-wrapper" style="max-width: 180px;">
                    <img src="{{ asset('storage/' . $image->path) }}" class="img-fluid rounded" style="max-height: 150px; width: 100%; object-fit: cover;">
                <button type="button"
                    class="btn btn-sm btn-danger position-absolute delete-existing-image"
                    style="top: 5px; right: 5px; padding: 2px 6px; line-height: 1; font-size: 14px; border-radius: 50%;"
                    data-id="{{ $image->id }}"
                    title="Delete Image"
                >&times;</button>

                </div>

                @empty
                    <p class="text-muted">No images</p>
                @endforelse
            </div>
        </div>

        <!-- Hidden container to track deleted image IDs -->
        <div id="deleted-images-container"></div>

        <button type="submit" class="btn btn-primary w-100 mt-4">Update Variant</button>
    </form>
</div>

<script>
    // Add new image upload field
    document.getElementById('add-image').addEventListener('click', () => {
        const wrapper = document.getElementById('variant-images-wrapper');
        const field = `
            <div class="d-flex align-items-center mb-2">
                <input type="file" name="images[]" class="form-control me-2">
                <button type="button" class="btn btn-sm btn-outline-danger remove-image">&times;</button>
            </div>`;
        wrapper.insertAdjacentHTML('beforeend', field);
    });

    // Remove dynamically added input or mark existing image for deletion
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-image')) {
            e.target.closest('.d-flex').remove();
        }

        if (e.target.classList.contains('delete-existing-image')) {
            const imageId = e.target.getAttribute('data-id');

            // Add hidden input to track deleted image ID
            const container = document.getElementById('deleted-images-container');
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'delete_images[]';
            input.value = imageId;
            container.appendChild(input);

            // Remove image preview
            e.target.closest('.existing-image-wrapper').remove();
        }
    });
</script>

@include('layouts.footer')

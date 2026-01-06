@include('layouts.header')
@include('layouts.sidebar')
@include('layouts.navbar')

<div class="bg-light rounded h-100 p-4">
    <h4 class="mb-4">All Product Variants</h4>
    <a href="{{ route('admin.productVariants.create', ['product' => $product->id]) }}" class="btn btn-primary">
    Add New Variant
</a>


    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>SKU</th>
                    <th>Size</th>
                    <th>Color</th>
                    <th>Price</th>
                    <th>Images</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($variants as $variant)
                <tr>
                    <td>{{ $variant->product->name }}</td>
                    <td>{{ $variant->sku }}</td>
                    <td>{{ $variant->attributes['size'] ?? '—' }}</td>
                    <td>
                        <span style="background: {{ $variant->attributes['color'] }}; display:inline-block; width:20px; height:20px; border:1px solid #000;"></span>
                        {{ $variant->attributes['color'] }}
                    </td>
                    <td>${{ $variant->price_override ?? 'N/A' }}</td>
                    <td>
                        @forelse($variant->images as $img)
                            <img src="{{ asset('storage/' . $img->path) }}" width="40" class="me-1 mb-1 rounded border">
                        @empty
                            <em>No image</em>
                        @endforelse
                    </td>
                    <td>
                        <a href="{{ route('admin.productVariants.edit', $variant->id) }}" class="btn btn-sm btn-info">Edit</a>
                        <form action="{{ route('admin.productVariants.destroy', $variant->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this variant?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">×</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@include('layouts.footer')

@include('layouts.header')
@include('layouts.sidebar')
@include('layouts.navbar')

<div class="bg-light rounded h-100 p-4">
    <h6 class="mb-4">Inventory List</h6>

    {{-- Products --}}
    <div class="table-responsive mb-5">
        <h5>Products</h5>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Stock Quantity</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $index => $product)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->stock_quantity ?? '—' }}</td>
                        <td>{{ $product->created_at->format('Y-m-d') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center">No products found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Variants --}}
    <div class="table-responsive">
        <h5>Product Variants</h5>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Variant ID</th>
                    <th>Product</th>
                    <th>SKU</th>
                    <th>Attributes</th>
                    <th>Price</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @forelse($variants as $index => $variant)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $variant->id }}</td>
                        <td>{{ $variant->product->name ?? '—' }}</td>
                        <td>{{ $variant->sku }}</td>
                        <td>
                            @foreach((array) $variant->attributes as $key => $value)
                                <span class="badge bg-secondary">{{ ucfirst($key) }}: {{ $value }}</span>
                            @endforeach
                        </td>
                        <td>{{ $variant->price_override ?? '—' }}</td>
                        <td>{{ $variant->created_at->format('Y-m-d') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center">No variants found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@include('layouts.footer')

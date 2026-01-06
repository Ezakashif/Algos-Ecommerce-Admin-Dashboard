@include('layouts.header')
@include('layouts.sidebar')
@include('layouts.navbar')

<div class="bg-light rounded h-100 p-4">
    <h6 class="mb-4">Products Table</h6>
    <div class="table-responsive">
        <a class="btn btn-primary btn-sm mb-2" href="{{ route('admin.products.create') }}">Add New Product</a>

        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Base Price</th>
                    <th>Category</th>
                    <th>Variants</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($products as $product)
                <tr>
                    <td style="width: 100px;">
                        @if ($product->images->count())
                            <img src="{{ asset('storage/' . $product->images->first()->path) }}" 
                                 alt="Product Image" 
                                 class="img-thumbnail" 
                                 style="width: 80px; height: 80px; object-fit: cover;">
                        @else
                            <span class="text-muted">No Image</span>
                        @endif
                    </td>

                    <td>{{ $product->name }}</td>

                    <td>${{ number_format($product->base_price, 2) }}</td>

                    <td>
                        @forelse ($product->categories as $category)
                            @if ($category->parent)
                                {{ $category->parent->name }} →
                            @endif
                            {{ $category->name }}
                        @empty
                            <em>No active category</em>
                        @endforelse
                    </td>

                  <td>
  <a href="{{ route('admin.products.variants.index', $product->id) }}" class="btn btn-sm btn-warning">View Variants</a>

                        
</td>

                    <td>
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Delete this product?')" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

@include('layouts.footer')

@include('layouts.header')
@include('layouts.sidebar')
@include('layouts.navbar')

<div class="bg-light rounded h-100 p-4">
    <h6 class="mb-4">Trashed Categories</h6>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
         <a class="btn btn-primary btn-sm mb-2" href="{{ route('admin.categories.create') }}">Add New Category</a>

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Id</th>
                    <th scope="col">Main Category</th>
                    <th scope="col">Sub Category</th>
                    <th scope="col">Description</th>
                    <th scope="col">Deleted At</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @php $i = 1; @endphp
                @foreach($categories as $item)
                    {{-- Main Trashed Category --}}
                    <tr>
                        <th scope="row">{{ $i++ }}</th>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->name }}</td>
                        <td>—</td>
                        <td>{{ $item->description ?? '—' }}</td>
                        <td>{{ $item->deleted_at->format('Y-m-d H:i') }}</td>
                        <td>
                            <form action="{{ route('admin.categories.restore', $item->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button class="btn btn-sm btn-success" onclick="return confirm('Restore this category?')">Restore</button>
                            </form>

                            <form action="{{ route('admin.categories.forceDelete', $item->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Permanently delete this category?')">Delete</button>
                            </form>
                        </td>
                    </tr>

                    {{-- Trashed Subcategories --}}
                    @foreach($item->children()->onlyTrashed()->get() as $child)
                        <tr>
                            <th scope="row">{{ $i++ }}</th>
                            <td>{{ $child->id }}</td>
                            <td></td>
                            <td>{{ $child->name }}</td>
                            <td>{{ $child->description ?? '—' }}</td>
                            <td>{{ $child->deleted_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <form action="{{ route('admin.categories.restore', $child->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button class="btn btn-sm btn-success" onclick="return confirm('Restore this subcategory?')">Restore</button>
                                </form>

                                <form action="{{ route('admin.categories.forceDelete', $child->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Permanently delete this subcategory?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @endforeach

                @if($categories->isEmpty())
                    <tr><td colspan="7" class="text-center">No trashed categories found.</td></tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

@include('layouts.footer')

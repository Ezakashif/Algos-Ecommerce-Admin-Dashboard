@include('layouts.header')
@include('layouts.sidebar')
@include('layouts.navbar')

  <div class="bg-light rounded h-100 p-4">

                            <h6 class="mb-4">Categoris List</h6>
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
                                        <th scope="col">Date Created</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 1; @endphp
                                    @foreach($categories as $item)
                                        {{-- Main Category --}}
                                        <tr>
                                            <th scope="row">{{ $i++ }}</th>
                                            <td>{{ $item->id }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>—</td>
                                            <td>{{ $item->description ?? '—' }}</td>
                                            <td>{{ $item->created_at->format('Y-m-d') }}</td>
                                            <td>
                                                <a href="{{ route('admin.categories.edit', $item->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                                <form action="{{ route('admin.categories.destroy', $item->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button onclick="return confirm('Delete this category?')" class="btn btn-sm btn-danger">Trash</button>
                                                </form>
                                            </td>
                                        </tr>

                                        {{-- Subcategories --}}
                                        @foreach($item->children as $child)
                                            <tr>
                                                <th scope="row">{{ $i++ }}</th>
                                                <td>{{ $child->id }}</td>
                                                <td></td> {{-- Main category column left empty --}}
                                                <td>{{ $child->name }}</td>
                                                <td>{{ $child->description ?? '—' }}</td>
                                                <td>{{ $child->created_at->format('Y-m-d') }}</td>
                                                <td>
                                                    <a href="{{ route('admin.categories.edit', $child->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                                    <form action="{{ route('admin.categories.destroy', $child->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button onclick="return confirm('Delete this category?')" class="btn btn-sm btn-danger">Trash</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>


                            </div>
                        </div>
                    </div>
                </div>

@include('layouts.footer')
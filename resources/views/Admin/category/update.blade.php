@include('/../layouts.header')
@include('layouts.sidebar')
@include('layouts.navbar')

<form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
    @csrf
    @method('PUT') {{-- For updating --}}
    
    <div class="bg-light rounded h-100 p-4">
        <h6 class="mb-4">Edit Category</h6>

        {{-- Category Name --}}
        <div class="form-floating mb-3">
            <input type="text" name="name" class="form-control" id="categoryName"
                   placeholder="Category Name" value="{{ old('name', $category->name) }}" required>
            <label for="categoryName">Category Name</label>
            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Parent Category --}}
        <div class="form-floating mb-3">
            <select name="parent_id" class="form-select" id="parentCategory">
                <option value="">-- None (Main Category) --</option>
                @foreach ($categories as $parent)
                    {{-- Prevent selecting self as parent --}}
                    @if ($parent->id !== $category->id)
                        <option value="{{ $parent->id }}"
                            {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                            {{ $parent->name }}
                        </option>
                    @endif
                @endforeach
            </select>
            <label for="parentCategory">Select Main Category (optional)</label>
            @error('parent_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Description --}}
        <div class="form-floating mb-3">
            <textarea name="description" class="form-control" placeholder="Category description"
                      id="categoryDescription" style="height: 150px;">{{ old('description', $category->description) }}</textarea>
            <label for="categoryDescription">Description</label>
            @error('description')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <button type="submit" class="btn btn-success w-100">Update Category</button>
    </div>
</form>


              

@include('layouts.footer')
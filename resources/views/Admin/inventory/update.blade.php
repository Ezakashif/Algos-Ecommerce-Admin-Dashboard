@extends('layouts.admin')

@section('content')
<h2>Edit Stock for {{ $inventory->variant->product->name }} - {{ $inventory->variant->name }}</h2>

<form method="POST" action="{{ route('inventories.update', $inventory) }}">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label for="stock_quantity">Stock Quantity</label>
        <input type="number" name="stock_quantity" id="stock_quantity" class="form-control" value="{{ old('stock_quantity', $inventory->stock_quantity) }}" min="0">
    </div>

    <button type="submit" class="btn btn-success">Update Stock</button>
</form>
@endsection

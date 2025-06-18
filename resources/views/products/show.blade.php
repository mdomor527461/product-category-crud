@extends('layouts.app')

@section('title', 'Product Details')

@section('content')
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-900">{{ $product->name }}</h2>
            <div class="space-x-2">
                <a href="{{ route('products.edit', $product) }}"
                    class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
                <a href="{{ route('products.index') }}"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Product Image -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow-md rounded-lg p-6">
                @if ($product->image)
                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                        class="w-full h-64 object-cover rounded-lg mb-4">
                @else
                    <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-image text-gray-400 text-4xl"></i>
                    </div>
                @endif

                <div class="text-center">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $product->name }}</h3>
                    <p class="text-3xl font-bold text-green-600">${{ number_format($product->price, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Product Details -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Product Information</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm font-medium text-gray-500">SKU</label>
                            <p class="text-gray-900 font-mono">{{ $product->sku }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-500">Price</label>
                            <p class="text-gray-900 text-lg font-semibold">${{ number_format($product->price, 2) }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-500">Stock Quantity</label>
                            <div class="flex items-center">
                                <span
                                    class="bg-{{ $product->stock_quantity > 10 ? 'green' : ($product->stock_quantity > 0 ? 'yellow' : 'red') }}-100 text-{{ $product->stock_quantity > 10 ? 'green' : ($product->stock_quantity > 0 ? 'yellow' : 'red') }}-800 text-sm font-semibold px-3 py-1 rounded">
                                    {{ $product->stock_quantity }} units
                                </span>
                            </div>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-500">Status</label>
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Categories</label>
                            <div class="flex flex-wrap gap-2 mt-1">
                                @forelse($product->categories as $category)
                                    <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded">
                                        {{ $category->name }}
                                    </span>
                                @empty
                                    <span class="text-gray-400">No categories assigned</span>
                                @endforelse
                            </div>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-500">Created</label>
                            <p class="text-gray-900">{{ $product->created_at->format('M d, Y') }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-500">Last Updated</label>
                            <p class="text-gray-900">{{ $product->updated_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Description -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Description</h3>
                <div class="prose max-w-none">
                    @if ($product->description)
                        <p class="text-gray-700 leading-relaxed">{{ $product->description }}</p>
                    @else
                        <p class="text-gray-400 italic">No description available.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('categories')->paginate(10);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|max:255|unique:products',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'sku' => 'required|unique:products',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max
            'is_active' => 'boolean',
            'categories' => 'array'
        ]);

        $productData = $request->except(['categories', 'image']);
        $productData['is_active'] = $request->boolean('is_active');
        if ($request->hasFile('image')) {
            $productData['image'] = $this->uploadImage($request->file('image'));
        }

        $product = Product::create($productData);

        if ($request->has('categories')) {
            $product->categories()->sync($request->categories);
        }

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        $product->load('categories');
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        $product->load('categories');
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|max:255|unique:products,name,' . $product->id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'sku' => 'required|unique:products,sku,' . $product->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max
            'is_active' => 'boolean',
            'categories' => 'array'
        ]);

        $productData = $request->except(['categories', 'image']);
        $productData['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {

            if ($product->image) {
                $this->deleteImage($product->image);
            }

            $productData['image'] = $this->uploadImage($request->file('image'));
        }

        $product->update($productData);

        if ($request->has('categories')) {
            $product->categories()->sync($request->categories);
        }

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {

        if ($product->image) {
            $this->deleteImage($product->image);
        }

        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }


    private function uploadImage($uploadedFile)
    {
        try {

            $filename = time() . '_' . Str::random(10) . '.webp';


            $directory = storage_path('app/public/products');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            $image = Image::read($uploadedFile);

            $image->resize(800, 800, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });


            $imagePath = $directory . '/' . $filename;
            $image->toWebp(85)->save($imagePath);


            $thumbnailFilename = 'thumb_' . $filename;
            $thumbnailPath = $directory . '/' . $thumbnailFilename;

            $thumbnail = Image::read($uploadedFile);
            $thumbnail->resize(200, 200, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $thumbnail->toWebp(80)->save($thumbnailPath);

            return 'products/' . $filename;
        } catch (\Exception $e) {

            Log::error('Image upload failed: ' . $e->getMessage());
            return null;
        }
    }

    private function deleteImage($imagePath)
    {
        try {

            if (Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }


            $thumbnailPath = str_replace('products/', 'products/thumb_', $imagePath);
            if (Storage::disk('public')->exists($thumbnailPath)) {
                Storage::disk('public')->delete($thumbnailPath);
            }
        } catch (\Exception $e) {
            Log::error('Image deletion failed: ' . $e->getMessage());
        }
    }


    public static function getThumbnailPath($imagePath)
    {
        if (!$imagePath) {
            return null;
        }

        return str_replace('products/', 'products/thumb_', $imagePath);
    }
}

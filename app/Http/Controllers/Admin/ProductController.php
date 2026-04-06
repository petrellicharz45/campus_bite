<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    public function index(): View
    {
        return view('admin.products.index', [
            'pageTitle' => 'Manage Products',
            'products' => Product::query()->with('category')->latest()->paginate(12),
            'summary' => [
                'total' => Product::query()->count(),
                'featured' => Product::query()->where('is_featured', true)->count(),
                'available' => Product::query()->where('is_available', true)->count(),
            ],
        ]);
    }

    public function create(): View
    {
        return view('admin.products.create', [
            'pageTitle' => 'Add Product',
            'categories' => Category::query()->orderBy('name')->get(),
            'product' => new Product([
                'prep_time' => 15,
                'is_featured' => false,
                'is_available' => true,
            ]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatedData($request);
        $validated['image_url'] = $this->resolveImageUrl($request);
        $validated['slug'] = $this->uniqueSlug($validated['name']);

        Product::create($validated);

        return redirect()->route('admin.products.index')->with('status', 'Product created successfully.');
    }

    public function edit(Product $product): View
    {
        return view('admin.products.edit', [
            'pageTitle' => 'Edit Product',
            'categories' => Category::query()->orderBy('name')->get(),
            'product' => $product,
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $this->validatedData($request);
        $validated['image_url'] = $this->resolveImageUrl($request, $product);
        $validated['slug'] = $product->name === $validated['name']
            ? $product->slug
            : $this->uniqueSlug($validated['name'], $product);

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('status', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('admin.products.index')->with('status', 'Product deleted successfully.');
    }

    private function validatedData(Request $request): array
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'short_description' => ['required', 'string', 'max:160'],
            'description' => ['required', 'string', 'max:600'],
            'image_url' => ['nullable', 'url', 'max:2048'],
            'image_upload' => ['nullable', 'image', 'max:3072'],
            'price' => ['required', 'numeric', 'min:1', 'max:999999.99'],
            'prep_time' => ['required', 'integer', 'min:5', 'max:90'],
            'calories' => ['nullable', 'integer', 'min:50', 'max:2000'],
            'is_featured' => ['nullable', 'boolean'],
            'is_available' => ['nullable', 'boolean'],
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_available'] = $request->boolean('is_available');

        return $validated;
    }

    private function resolveImageUrl(Request $request, ?Product $product = null): string
    {
        if ($request->hasFile('image_upload')) {
            $storedPath = $request->file('image_upload')->store('products', 'public');

            if ($product) {
                $this->deleteStoredImage($product->image_url);
            }

            return Storage::url($storedPath);
        }

        $externalImage = $request->string('image_url')->trim()->toString();

        if ($externalImage !== '') {
            return $externalImage;
        }

        if ($product?->image_url) {
            return $product->image_url;
        }

        throw ValidationException::withMessages([
            'image_upload' => 'Upload a product image or provide an image URL.',
        ]);
    }

    private function deleteStoredImage(?string $imageUrl): void
    {
        if (! $imageUrl || ! str_starts_with($imageUrl, '/storage/')) {
            return;
        }

        $storedPath = ltrim(Str::after($imageUrl, '/storage/'), '/');

        if ($storedPath !== '' && Storage::disk('public')->exists($storedPath)) {
            Storage::disk('public')->delete($storedPath);
        }
    }

    private function uniqueSlug(string $name, ?Product $ignore = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        while (Product::query()
            ->when($ignore, fn ($query) => $query->where('id', '!=', $ignore->id))
            ->where('slug', $slug)
            ->exists()) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}

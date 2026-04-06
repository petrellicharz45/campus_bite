<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Category</label>
        <select name="category_id" class="form-select" required>
            <option value="">Select a category</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">Product name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
    </div>

    <div class="col-md-7">
        <label class="form-label">Short description</label>
        <input type="text" name="short_description" class="form-control" value="{{ old('short_description', $product->short_description) }}" required>
    </div>

    <div class="col-md-5">
        <label class="form-label">Upload image</label>
        <input type="file" name="image_upload" class="form-control" accept="image/*">
        <div class="form-text">Use a local image file for the best admin workflow.</div>
    </div>

    <div class="col-md-7">
        <label class="form-label">External image URL</label>
        <input type="url" name="image_url" class="form-control" value="{{ old('image_url', $product->image_url) }}">
        <div class="form-text">Optional fallback if you are not uploading an image file.</div>
    </div>

    <div class="col-md-5">
        <label class="form-label">Current image preview</label>
        <div class="border rounded-4 p-2 bg-light">
            @if ($product->image_url)
                <img src="{{ $product->image_url }}" alt="{{ $product->name ?: 'Product image' }}" class="img-fluid rounded-4" style="max-height: 180px; object-fit: cover; width: 100%;">
            @else
                <div class="text-secondary small py-5 text-center">No image selected yet.</div>
            @endif
        </div>
    </div>

    <div class="col-12">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="4" required>{{ old('description', $product->description) }}</textarea>
    </div>

    <div class="col-md-4">
        <label class="form-label">Price</label>
        <input type="number" name="price" min="1" max="999999.99" step="0.01" class="form-control" value="{{ old('price', $product->price) }}" required>
        <div class="form-text">Enter the amount in {{ $currencyCode }}.</div>
    </div>

    <div class="col-md-4">
        <label class="form-label">Prep time (minutes)</label>
        <input type="number" name="prep_time" min="5" max="90" class="form-control" value="{{ old('prep_time', $product->prep_time) }}" required>
    </div>

    <div class="col-md-4">
        <label class="form-label">Calories</label>
        <input type="number" name="calories" min="50" max="2000" class="form-control" value="{{ old('calories', $product->calories) }}">
    </div>

    <div class="col-md-6 form-check ms-1">
        <input type="hidden" name="is_featured" value="0">
        <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="is_featured" @checked(old('is_featured', $product->is_featured))>
        <label class="form-check-label" for="is_featured">Featured product</label>
    </div>

    <div class="col-md-6 form-check ms-1">
        <input type="hidden" name="is_available" value="0">
        <input class="form-check-input" type="checkbox" name="is_available" value="1" id="is_available" @checked(old('is_available', $product->is_available ?? true))>
        <label class="form-check-label" for="is_available">Available for ordering</label>
    </div>
</div>

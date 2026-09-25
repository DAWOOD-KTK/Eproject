@extends('layouts.farmer')

@section('title', 'Weekly Stock & Pricing')

@section('content')
<div>
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold mb-1"><i class="bi bi-box-seam text-success me-2"></i> Weekly Stock & Pricing</h2>
            <p class="text-muted mb-0">Publish weekly harvests, adjust prices and units, and manage availability</p>
        </div>
        <div class="d-flex gap-2">
            <form action="{{ route('farmer.template.apply') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-success btn-sm rounded-pill px-3" onclick="return confirm('Restore stock quantities using your weekly recurring template?')">
                    <i class="bi bi-arrow-repeat me-1"></i> Apply Weekly Template
                </button>
            </form>
            <button class="btn btn-success btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#addProductModal">
                <i class="bi bi-plus-lg me-1"></i> Add New Product
            </button>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="card p-3 border-0 shadow-sm rounded-3 bg-white mb-4">
        <form action="{{ route('farmer.products') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-success"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" value="{{ request('search') }}" placeholder="Search products...">
                </div>
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available & In Stock</option>
                    <option value="sold_out" {{ request('status') == 'sold_out' ? 'selected' : '' }}>Sold Out / Unavailable</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-success flex-grow-1 rounded-pill fw-bold">Filter</button>
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('farmer.products') }}" class="btn btn-outline-secondary rounded-pill">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Products Table -->
    <div class="card border-0 shadow-sm rounded-3 bg-white overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-muted small">
                    <tr>
                        <th class="ps-4">Product Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock Available</th>
                        <th>Weekly Template</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td class="ps-4">
                                <strong class="text-dark d-block">{{ $product->name }}</strong>
                                <small class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $product->category }}</span>
                            </td>
                            <td class="fw-bold text-success">${{ number_format($product->price, 2) }} <small class="text-muted">/ {{ $product->unit }}</small></td>
                            <td>
                                <span class="fw-bold {{ $product->stock_quantity <= 0 ? 'text-danger' : 'text-dark' }}">
                                    {{ $product->stock_quantity }} {{ $product->unit }}
                                </span>
                            </td>
                            <td>
                                @if($product->is_weekly_template)
                                    <span class="badge bg-success bg-opacity-25 text-success" style="font-size: 0.7rem;">
                                        <i class="bi bi-check2"></i> Recurring
                                    </span>
                                @else
                                    <span class="text-muted small">No</span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('farmer.products.toggle', $product->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm p-0 border-0" title="Click to toggle availability">
                                        @if($product->is_available && $product->stock_quantity > 0)
                                            <span class="badge bg-success text-white rounded-pill px-2 py-1">In Stock</span>
                                        @else
                                            <span class="badge bg-danger text-white rounded-pill px-2 py-1">Sold Out</span>
                                        @endif
                                    </button>
                                </form>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <!-- Edit Trigger -->
                                    <button class="btn btn-outline-primary btn-sm rounded-circle px-2" data-bs-toggle="modal" data-bs-target="#editModal{{ $product->id }}" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <!-- Delete Trigger -->
                                    <form action="{{ route('farmer.products.delete', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove product from catalog?')">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-circle px-2" title="Delete">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                </div>

                                <!-- Edit Product Modal -->
                                <div class="modal fade text-start" id="editModal{{ $product->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('farmer.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title fw-bold">Edit {{ $product->name }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold">Product Name</label>
                                                        <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
                                                    </div>
                                                    <div class="row g-2 mb-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label small fw-bold">Category</label>
                                                            <select name="category_id" class="form-select" required>
                                                                @foreach($categories as $cat)
                                                                    <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label small fw-bold">Unit</label>
                                                            <input type="text" name="unit" class="form-control" value="{{ $product->unit }}" required placeholder="kg, bunch, dozen...">
                                                        </div>
                                                    </div>
                                                    <div class="row g-2 mb-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label small fw-bold">Price ($)</label>
                                                            <input type="number" step="0.01" name="price" class="form-control" value="{{ $product->price }}" required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label small fw-bold">Stock Quantity</label>
                                                            <input type="number" name="stock_quantity" class="form-control" value="{{ $product->stock_quantity }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold">Description</label>
                                                        <textarea name="description" class="form-control" rows="2">{{ $product->description }}</textarea>
                                                    </div>
                                                    <div class="form-check mb-2">
                                                        <input type="checkbox" name="is_available" value="1" class="form-check-input" id="avail{{ $product->id }}" {{ $product->is_available ? 'checked' : '' }}>
                                                        <label class="form-check-label small" for="avail{{ $product->id }}">Product is Available for Pre-Orders</label>
                                                    </div>
                                                    <div class="form-check mb-2">
                                                        <input type="checkbox" name="is_weekly_template" value="1" class="form-check-input" id="tpl{{ $product->id }}" {{ $product->is_weekly_template ? 'checked' : '' }}>
                                                        <label class="form-check-label small" for="tpl{{ $product->id }}">Include in Recurring Weekly Stock Template</label>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary btn-sm rounded-pill" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-success btn-sm rounded-pill px-3 fw-bold">Save Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-box-seam fs-1 d-block mb-2"></i>
                                <h6>No products found.</h6>
                                <p class="small mb-3">Add fresh fruits, vegetables, or specialty foods for customer pre-order.</p>
                                <button class="btn btn-success btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#addProductModal">
                                    Add First Product
                                </button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>
</div>

<!-- Add New Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('farmer.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Add Produce to Market Catalog</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Product Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required placeholder="e.g. Heirloom Beefsteak Tomatoes">
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Category <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select" required>
                                <option value="">-- Select Category --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Measurement Unit <span class="text-danger">*</span></label>
                            <input type="text" name="unit" class="form-control" required placeholder="kg, bunch, dozen, jar...">
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Price ($) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="price" class="form-control" required placeholder="e.g. 4.50">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Weekly Stock Quantity <span class="text-danger">*</span></label>
                            <input type="number" name="stock_quantity" class="form-control" required placeholder="e.g. 50">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Description</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Grown organic, sweet flavor, ideal for salads..."></textarea>
                    </div>
                    <div class="form-check mb-2">
                        <input type="checkbox" name="is_available" value="1" class="form-check-input" id="newAvail" checked>
                        <label class="form-check-label small" for="newAvail">Make Available for Pre-Orders Immediately</label>
                    </div>
                    <div class="form-check mb-2">
                        <input type="checkbox" name="is_weekly_template" value="1" class="form-check-input" id="newTpl" checked>
                        <label class="form-check-label small" for="newTpl">Save in Weekly Recurring Stock Template</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm rounded-pill" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success btn-sm rounded-pill px-4 fw-bold">Add Product</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

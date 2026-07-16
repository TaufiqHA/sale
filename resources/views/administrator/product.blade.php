@extends('layouts.administrator')

@section('title', 'Product Management')

@section('content')
<div class="relative min-h-[calc(100vh-8rem)]">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <!-- Search and Filter Bar -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 flex-1 max-w-2xl">
            <!-- Search Input -->
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" id="search-input" oninput="handleSearchFilterChange()" placeholder="Search product name, SKU or barcode..." class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#1e50d0] focus:ring-2 focus:ring-[#1e50d0]/10 transition duration-150 text-sm bg-white placeholder:text-slate-400">
            </div>

            <!-- Counter Filter -->
            <div class="relative shrink-0">
                <select id="filter-counter-id" onchange="handleSearchFilterChange()" class="w-full sm:w-48 pl-4 pr-10 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#1e50d0] focus:ring-2 focus:ring-[#1e50d0]/10 transition duration-150 text-sm bg-white appearance-none cursor-pointer">
                    <option value="">All Counters</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Add Button -->
        <button onclick="openAddModal()" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-[#1e50d0] hover:bg-[#1641b3] active:scale-[0.98] text-white text-sm font-semibold rounded-xl transition duration-200 shadow-sm shadow-[#1e50d0]/20 cursor-pointer shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
            </svg>
            Add Product
        </button>
    </div>

    <!-- Loading State (Table Skeleton) -->
    <div id="loading-skeleton" class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden mb-8 animate-pulse">
        <div class="h-12 bg-slate-50 border-b border-slate-100"></div>
        <div class="divide-y divide-slate-100">
            @for ($i = 0; $i < 3; $i++)
            <div class="px-6 py-5 flex items-center justify-between gap-6">
                <div class="h-4 bg-slate-100 rounded-md w-16 shrink-0"></div>
                <div class="h-4 bg-slate-100 rounded-md w-36 shrink-0 flex-1"></div>
                <div class="h-4 bg-slate-100 rounded-md w-24 shrink-0"></div>
                <div class="h-4 bg-slate-100 rounded-md w-20 shrink-0"></div>
                <div class="h-4 bg-slate-100 rounded-md w-24 shrink-0"></div>
                <div class="h-4 bg-slate-100 rounded-md w-28 shrink-0"></div>
                <div class="h-4 bg-slate-100 rounded-md w-28 shrink-0"></div>
                <div class="h-6 bg-slate-100 rounded-full w-16 shrink-0"></div>
                <div class="h-8 bg-slate-100 rounded-lg w-20 shrink-0"></div>
            </div>
            @endfor
        </div>
    </div>

    <!-- Empty State -->
    <div id="empty-state" class="hidden flex-col items-center justify-center py-16 px-4 bg-white border border-slate-100 rounded-2xl shadow-sm mb-8 text-center">
        <div class="flex items-center justify-center p-4 bg-slate-50 text-slate-400 rounded-2xl mb-4">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"></path>
            </svg>
        </div>
        <h3 class="text-base font-bold text-slate-800">No Products Registered</h3>
        <button onclick="openAddModal()" class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-[#1e50d0]/10 hover:bg-[#1e50d0]/20 text-[#1e50d0] text-sm font-semibold rounded-xl transition duration-150 cursor-pointer">
            Add Product
        </button>
    </div>

    <!-- Product Table -->
    <div id="product-table-wrapper" class="hidden bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden mb-8">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                        <th class="px-6 py-4">SKU / Barcode</th>
                        <th class="px-6 py-4">Product Name</th>
                        <th class="px-6 py-4">Category</th>
                        <th class="px-6 py-4">Unit</th>
                        <th class="px-6 py-4">Counter</th>
                        <th class="px-6 py-4">Stock</th>
                        <th class="px-6 py-4">Buy Price</th>
                        <th class="px-6 py-4">Sell Price</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="product-table-body" class="divide-y divide-slate-100 text-sm text-slate-600">
                    <!-- Rendered dynamically -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal (Create / Edit) -->
<div id="product-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden" role="dialog" aria-modal="true">
    <!-- Backdrop with blur -->
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300 opacity-0" id="modal-backdrop" onclick="closeModal()"></div>
    
    <!-- Modal content -->
    <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden transform scale-95 opacity-0 transition-all duration-300 flex flex-col max-h-[90vh]" id="modal-panel">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between shrink-0">
            <h3 id="modal-title" class="text-lg font-bold text-slate-800">Add Product</h3>
            <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600 transition p-1.5 rounded-lg hover:bg-slate-50 cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <form id="product-form" onsubmit="handleFormSubmit(event)" class="p-6 space-y-4 overflow-y-auto flex-1">
            <input type="hidden" id="product-id" name="id">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Name -->
                <div class="space-y-1.5 md:col-span-2">
                    <label for="input-name" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Product Name</label>
                    <input type="text" id="input-name" name="name" placeholder="e.g. Coca-Cola 330ml" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#1e50d0] focus:ring-2 focus:ring-[#1e50d0]/10 transition duration-150 text-sm placeholder:text-slate-400" required>
                    <p id="error-name" class="text-xs font-medium text-rose-500 hidden"></p>
                </div>

                <!-- SKU -->
                <div class="space-y-1.5">
                    <label for="input-sku" class="text-xs font-semibold uppercase tracking-wider text-slate-400">SKU</label>
                    <input type="text" id="input-sku" name="sku" placeholder="e.g. SKU-DRK-COCA" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#1e50d0] focus:ring-2 focus:ring-[#1e50d0]/10 transition duration-150 text-sm placeholder:text-slate-400" required>
                    <p id="error-sku" class="text-xs font-medium text-rose-500 hidden"></p>
                </div>

                <!-- Barcode -->
                <div class="space-y-1.5">
                    <label for="input-barcode" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Barcode</label>
                    <input type="text" id="input-barcode" name="barcode" placeholder="e.g. 8886007810123" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#1e50d0] focus:ring-2 focus:ring-[#1e50d0]/10 transition duration-150 text-sm placeholder:text-slate-400">
                    <p id="error-barcode" class="text-xs font-medium text-rose-500 hidden"></p>
                </div>

                <!-- Category -->
                <div>
                    <div class="flex items-end gap-2">
                        <div class="flex-1 space-y-1.5">
                            <label for="input-category_id" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Category</label>
                            <select id="input-category_id" name="category_id" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#1e50d0] focus:ring-2 focus:ring-[#1e50d0]/10 transition duration-150 text-sm bg-white" required>
                                <option value="" disabled selected>Select Category</option>
                            </select>
                        </div>
                        <button type="button" onclick="openCategoryManageModal()" class="px-3.5 py-2.5 bg-slate-50 hover:bg-slate-100 text-slate-600 hover:text-slate-800 text-sm font-semibold rounded-xl border border-slate-200 transition duration-150 inline-flex items-center gap-1.5 shrink-0 cursor-pointer" title="Manage Categories">
                            Kelola
                        </button>
                    </div>
                    <p id="error-category_id" class="text-xs font-medium text-rose-500 hidden mt-1.5"></p>
                </div>

                <!-- Unit -->
                <div>
                    <div class="flex items-end gap-2">
                        <div class="flex-1 space-y-1.5">
                            <label for="input-unit_id" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Unit</label>
                            <select id="input-unit_id" name="unit_id" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#1e50d0] focus:ring-2 focus:ring-[#1e50d0]/10 transition duration-150 text-sm bg-white" required>
                                <option value="" disabled selected>Select Unit</option>
                            </select>
                        </div>
                        <button type="button" onclick="openUnitManageModal()" class="px-3.5 py-2.5 bg-slate-50 hover:bg-slate-100 text-slate-600 hover:text-slate-800 text-sm font-semibold rounded-xl border border-slate-200 transition duration-150 inline-flex items-center gap-1.5 shrink-0 cursor-pointer" title="Manage Units">
                            Kelola
                        </button>
                    </div>
                    <p id="error-unit_id" class="text-xs font-medium text-rose-500 hidden mt-1.5"></p>
                </div>

                <!-- Counter -->
                <div>
                    <div class="space-y-1.5">
                        <label for="input-counter_id" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Counter</label>
                        <select id="input-counter_id" name="counter_id" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#1e50d0] focus:ring-2 focus:ring-[#1e50d0]/10 transition duration-150 text-sm bg-white" required>
                            <option value="" disabled selected>Select Counter</option>
                        </select>
                    </div>
                    <p id="error-counter_id" class="text-xs font-medium text-rose-500 hidden mt-1.5"></p>
                </div>

                <!-- Stock -->
                <div class="space-y-1.5">
                    <label for="input-stock" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Stock</label>
                    <input type="number" id="input-stock" name="stock" min="0" placeholder="e.g. 10" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#1e50d0] focus:ring-2 focus:ring-[#1e50d0]/10 transition duration-150 text-sm placeholder:text-slate-400" required>
                    <p id="error-stock" class="text-xs font-medium text-rose-500 hidden"></p>
                </div>

                <!-- Buy Price -->
                <div class="space-y-1.5">
                    <label for="input-buy_price" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Buy Price (Rp)</label>
                    <input type="text" id="input-buy_price" name="buy_price" placeholder="e.g. 5.000" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#1e50d0] focus:ring-2 focus:ring-[#1e50d0]/10 transition duration-150 text-sm placeholder:text-slate-400" required>
                    <p id="error-buy_price" class="text-xs font-medium text-rose-500 hidden"></p>
                </div>

                <!-- Sell Price -->
                <div class="space-y-1.5">
                    <label for="input-sell_price" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Sell Price (Rp)</label>
                    <input type="text" id="input-sell_price" name="sell_price" placeholder="e.g. 7.000" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#1e50d0] focus:ring-2 focus:ring-[#1e50d0]/10 transition duration-150 text-sm placeholder:text-slate-400" required>
                    <p id="error-sell_price" class="text-xs font-medium text-rose-500 hidden"></p>
                </div>
            </div>

            <!-- Description -->
            <div class="space-y-1.5">
                <label for="input-description" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Description</label>
                <textarea id="input-description" name="description" rows="2" placeholder="Product details or notes..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#1e50d0] focus:ring-2 focus:ring-[#1e50d0]/10 transition duration-150 text-sm placeholder:text-slate-400 resize-none"></textarea>
                <p id="error-description" class="text-xs font-medium text-rose-500 hidden"></p>
            </div>

            <!-- Status Checkbox -->
            <div class="flex items-center gap-3 py-2">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="input-status" name="status" class="sr-only peer" checked>
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-[#1e50d0]/10 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#1e50d0]"></div>
                    <span class="ml-3 text-sm font-semibold text-slate-700">Active</span>
                </label>
                <p id="error-status" class="text-xs font-medium text-rose-500 hidden"></p>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeModal()" class="px-4 py-2.5 text-sm font-semibold text-slate-500 hover:text-slate-800 hover:bg-slate-50 rounded-xl transition duration-150">
                    Cancel
                </button>
                <button type="submit" id="btn-save" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-[#1e50d0] hover:bg-[#1641b3] text-white text-sm font-semibold rounded-xl transition duration-150">
                    Save Product
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal (Delete Confirmation) -->
<div id="delete-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300 opacity-0" id="delete-backdrop" onclick="closeDeleteModal()"></div>
    
    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-xl border border-slate-100 p-6 transform scale-95 opacity-0 transition-all duration-300" id="delete-panel">
        <div class="flex items-start gap-4 mb-4">
            <div class="p-3 bg-rose-50 text-rose-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-base font-bold text-slate-800">Delete Product</h3>
                <p class="text-sm text-slate-500 mt-1">Are you sure you want to delete <span id="delete-product-name" class="font-semibold text-slate-700"></span>? This will remove the item permanently.</p>
            </div>
        </div>
        
        <div class="flex items-center justify-end gap-3 pt-2">
            <button onclick="closeDeleteModal()" class="px-4 py-2.5 text-sm font-semibold text-slate-500 hover:text-slate-800 hover:bg-slate-50 rounded-xl transition duration-150">
                Cancel
            </button>
            <button onclick="handleDeleteConfirm()" id="btn-delete" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold rounded-xl transition duration-150">
                Delete
            </button>
        </div>
    </div>
</div>

<!-- Modal (Manage Categories) -->
<div id="category-manage-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden" role="dialog" aria-modal="true">
    <!-- Backdrop with blur -->
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300 opacity-0" id="category-manage-backdrop" onclick="closeCategoryManageModal()"></div>
    
    <!-- Modal content -->
    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden transform scale-95 opacity-0 transition-all duration-300" id="category-manage-panel">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-lg font-bold text-slate-800">Manage Categories</h3>
            <button onclick="closeCategoryManageModal()" class="text-slate-400 hover:text-slate-600 transition p-1.5 rounded-lg hover:bg-slate-50">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <div class="p-6 space-y-6">
            <!-- Add Category Inline Form -->
            <form id="inline-category-form" onsubmit="handleInlineCategorySubmit(event)" class="space-y-3 bg-slate-50 p-4 rounded-xl border border-slate-100">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500">Quick Add Category</h4>
                <div class="space-y-1.5">
                    <input type="text" id="inline-cat-name" placeholder="Category Name (e.g. Beverages)" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-[#1e50d0] focus:ring-2 focus:ring-[#1e50d0]/10 transition duration-150 text-xs placeholder:text-slate-400 bg-white" required>
                </div>
                <div class="space-y-1.5">
                    <input type="text" id="inline-cat-desc" placeholder="Short Description (optional)" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-[#1e50d0] focus:ring-2 focus:ring-[#1e50d0]/10 transition duration-150 text-xs placeholder:text-slate-400 bg-white">
                </div>
                <button type="submit" id="btn-inline-cat-save" class="w-full py-2 bg-[#1e50d0] hover:bg-[#1641b3] text-white text-xs font-semibold rounded-xl transition duration-150">
                    Add Category
                </button>
            </form>

            <!-- Scrollable Category List -->
            <div class="space-y-2">
                <h4 class="text-xs font-semibold uppercase tracking-wider text-slate-400">Existing Categories</h4>
                <div class="max-h-60 overflow-y-auto divide-y divide-slate-100 border border-slate-100 rounded-xl pr-1">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-400 border-b border-slate-100">
                                <th class="p-3">Name</th>
                                <th class="p-3 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody id="inline-category-list-body" class="divide-y divide-slate-50 text-slate-600">
                            <!-- Rendered dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal (Manage Units) -->
<div id="unit-manage-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden" role="dialog" aria-modal="true">
    <!-- Backdrop with blur -->
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300 opacity-0" id="unit-manage-backdrop" onclick="closeUnitManageModal()"></div>
    
    <!-- Modal content -->
    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden transform scale-95 opacity-0 transition-all duration-300" id="unit-manage-panel">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-lg font-bold text-slate-800">Manage Units</h3>
            <button onclick="closeUnitManageModal()" class="text-slate-400 hover:text-slate-600 transition p-1.5 rounded-lg hover:bg-slate-50">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <div class="p-6 space-y-6">
            <!-- Add Unit Inline Form -->
            <form id="inline-unit-form" onsubmit="handleInlineUnitSubmit(event)" class="space-y-3 bg-slate-50 p-4 rounded-xl border border-slate-100">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500">Quick Add Unit</h4>
                <div class="grid grid-cols-2 gap-2">
                    <div class="space-y-1.5">
                        <input type="text" id="inline-unit-name" placeholder="Name (e.g. Pieces)" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-[#1e50d0] focus:ring-2 focus:ring-[#1e50d0]/10 transition duration-150 text-xs placeholder:text-slate-400 bg-white" required>
                    </div>
                    <div class="space-y-1.5">
                        <input type="text" id="inline-unit-code" placeholder="Code (e.g. PCS)" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-[#1e50d0] focus:ring-2 focus:ring-[#1e50d0]/10 transition duration-150 text-xs placeholder:text-slate-400 bg-white" required>
                    </div>
                </div>
                <button type="submit" id="btn-inline-unit-save" class="w-full py-2 bg-[#1e50d0] hover:bg-[#1641b3] text-white text-xs font-semibold rounded-xl transition duration-150">
                    Add Unit
                </button>
            </form>

            <!-- Scrollable Unit List -->
            <div class="space-y-2">
                <h4 class="text-xs font-semibold uppercase tracking-wider text-slate-400">Existing Units</h4>
                <div class="max-h-60 overflow-y-auto divide-y divide-slate-100 border border-slate-100 rounded-xl pr-1">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-400 border-b border-slate-100">
                                <th class="p-3">Name</th>
                                <th class="p-3">Code</th>
                                <th class="p-3 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody id="inline-unit-list-body" class="divide-y divide-slate-50 text-slate-600">
                            <!-- Rendered dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast System -->
<div id="toast-container" class="fixed top-6 right-6 z-50 flex flex-col gap-3 pointer-events-none"></div>

<!-- JavaScript Logic -->
<script>
    const csrfToken = "{{ csrf_token() }}";
    let activeProducts = [];
    let categoriesList = [];
    let unitsList = [];
    let countersList = [];
    let productToDelete = null;

    document.addEventListener("DOMContentLoaded", () => {
        initPage();

        // Auto-dot thousand separator event listeners
        document.getElementById("input-buy_price").addEventListener("input", function(e) {
            e.target.value = formatThousandSeparator(e.target.value);
        });
        document.getElementById("input-sell_price").addEventListener("input", function(e) {
            e.target.value = formatThousandSeparator(e.target.value);
        });

        // Realtime SKU check
        const skuInput = document.getElementById("input-sku");
        skuInput.addEventListener("input", function(e) {
            const skuVal = e.target.value.trim();
            const currentId = document.getElementById("product-id").value;
            const errorEl = document.getElementById("error-sku");
            const btnSave = document.getElementById("btn-save");

            if (!skuVal) {
                errorEl.innerText = "";
                errorEl.classList.add("hidden");
                skuInput.classList.remove("border-rose-500", "focus:border-rose-500", "focus:ring-rose-500/10");
                btnSave.disabled = false;
                return;
            }

            const exists = activeProducts.some(p => p.sku.toLowerCase() === skuVal.toLowerCase() && String(p.id) !== String(currentId));

            if (exists) {
                errorEl.innerText = "SKU ini sudah terdaftar.";
                errorEl.classList.remove("hidden");
                skuInput.classList.add("border-rose-500", "focus:border-rose-500", "focus:ring-rose-500/10");
                btnSave.disabled = true;
            } else {
                errorEl.innerText = "";
                errorEl.classList.add("hidden");
                skuInput.classList.remove("border-rose-500", "focus:border-rose-500", "focus:ring-rose-500/10");
                btnSave.disabled = false;
            }
        });
    });

    async function initPage() {
        await Promise.all([
            fetchCategories(),
            fetchUnits(),
            fetchCounters()
        ]);
        fetchProducts();
    }

    async function fetchCategories() {
        try {
            const response = await fetch("/categories", {
                headers: { "Accept": "application/json" }
            });
            if (!response.ok) throw new Error();
            categoriesList = await response.json();
            
            const select = document.getElementById("input-category_id");
            select.innerHTML = '<option value="" disabled selected>Select Category</option>';
            categoriesList.forEach(cat => {
                select.innerHTML += `<option value="${cat.id}">${escapeHtml(cat.name)}</option>`;
            });
        } catch (e) {
            showToast("Failed loading categories.", "error");
        }
    }

    async function fetchUnits() {
        try {
            const response = await fetch("/units", {
                headers: { "Accept": "application/json" }
            });
            if (!response.ok) throw new Error();
            unitsList = await response.json();
            
            const select = document.getElementById("input-unit_id");
            select.innerHTML = '<option value="" disabled selected>Select Unit</option>';
            unitsList.forEach(unit => {
                select.innerHTML += `<option value="${unit.id}">${escapeHtml(unit.name)} (${escapeHtml(unit.code)})</option>`;
            });
        } catch (e) {
            showToast("Failed loading units.", "error");
        }
    }

    async function fetchCounters() {
        try {
            const response = await fetch("/counters", {
                headers: { "Accept": "application/json" }
            });
            if (!response.ok) throw new Error();
            countersList = await response.json();
            
            const select = document.getElementById("input-counter_id");
            select.innerHTML = '<option value="" disabled selected>Select Counter</option>';
            countersList.forEach(counter => {
                select.innerHTML += `<option value="${counter.id}">${escapeHtml(counter.name)}</option>`;
            });

            const filterSelect = document.getElementById("filter-counter-id");
            filterSelect.innerHTML = '<option value="">All Counters</option>';
            countersList.forEach(counter => {
                filterSelect.innerHTML += `<option value="${counter.id}">${escapeHtml(counter.name)}</option>`;
            });
        } catch (e) {
            showToast("Failed loading counters.", "error");
        }
    }

    async function fetchProducts() {
        const skeleton = document.getElementById("loading-skeleton");
        const emptyState = document.getElementById("empty-state");
        const tableWrapper = document.getElementById("product-table-wrapper");

        skeleton.classList.remove("hidden");
        tableWrapper.classList.add("hidden");
        emptyState.classList.add("hidden");

        try {
            const response = await fetch("/products", {
                headers: { "Accept": "application/json" }
            });

            if (!response.ok) throw new Error("Failed to fetch products.");

            activeProducts = await response.json();
            renderProducts();
        } catch (error) {
            showToast("Failed loading products. Please try again.", "error");
        } finally {
            skeleton.classList.add("hidden");
        }
    }

    function renderProducts() {
        const emptyState = document.getElementById("empty-state");
        const tableWrapper = document.getElementById("product-table-wrapper");
        const tbody = document.getElementById("product-table-body");
        
        tbody.innerHTML = "";

        const searchQuery = document.getElementById("search-input").value.toLowerCase().trim();
        const counterFilter = document.getElementById("filter-counter-id").value;

        // Filter products
        const filteredProducts = activeProducts.filter(product => {
            if (counterFilter && String(product.counter_id) !== counterFilter) {
                return false;
            }
            if (searchQuery) {
                const nameMatch = product.name ? product.name.toLowerCase().includes(searchQuery) : false;
                const skuMatch = product.sku ? product.sku.toLowerCase().includes(searchQuery) : false;
                const barcodeMatch = product.barcode ? product.barcode.toLowerCase().includes(searchQuery) : false;
                if (!nameMatch && !skuMatch && !barcodeMatch) {
                    return false;
                }
            }
            return true;
        });

        if (activeProducts.length === 0) {
            emptyState.classList.remove("hidden");
            tableWrapper.classList.add("hidden");
            return;
        }

        emptyState.classList.add("hidden");
        tableWrapper.classList.remove("hidden");

        if (filteredProducts.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="9" class="px-6 py-12 text-center text-slate-400">
                        <div class="flex flex-col items-center justify-center gap-2">
                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <span class="text-sm font-semibold">No products match your criteria.</span>
                        </div>
                    </td>
                </tr>
            `;
            return;
        }

        filteredProducts.forEach(product => {
            const row = document.createElement("tr");
            row.className = "hover:bg-slate-50/50 transition-colors duration-150";
            
            const badgeClass = product.status 
                ? "bg-emerald-50 text-emerald-700 font-semibold" 
                : "bg-slate-100 text-slate-500 font-medium";
            const badgeText = product.status ? "Active" : "Inactive";

            const catName = product.category ? product.category.name : 'Uncategorized';
            const unitName = product.unit ? `${product.unit.name} (${product.unit.code})` : '-';
            const counterName = product.counter ? product.counter.name : '-';

            row.innerHTML = `
                <td class="px-6 py-4">
                    <div class="font-bold text-slate-800">${escapeHtml(product.sku)}</div>
                    <div class="text-xs text-slate-400 mt-0.5">${product.barcode ? escapeHtml(product.barcode) : '<span class="text-slate-300">No Barcode</span>'}</div>
                </td>
                <td class="px-6 py-4 font-bold text-slate-800">${escapeHtml(product.name)}</td>
                <td class="px-6 py-4 text-xs font-semibold text-slate-600">${escapeHtml(catName)}</td>
                <td class="px-6 py-4 text-xs font-medium text-slate-500">${escapeHtml(unitName)}</td>
                <td class="px-6 py-4 text-xs font-semibold text-slate-600">${escapeHtml(counterName)}</td>
                <td class="px-6 py-4 text-xs font-bold text-slate-700">${product.stock}</td>
                <td class="px-6 py-4 font-semibold text-slate-700">${formatRupiah(product.buy_price)}</td>
                <td class="px-6 py-4 font-semibold text-[#1e50d0]">${formatRupiah(product.sell_price)}</td>
                <td class="px-6 py-4">
                    <span class="px-2.5 py-1 text-[11px] rounded-full ${badgeClass}">
                        ${badgeText}
                    </span>
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end gap-1.5">
                        <button onclick="openEditModal(${product.id})" class="inline-flex items-center justify-center p-2 text-[#1e50d0] hover:bg-[#1e50d0]/5 rounded-xl transition duration-150 cursor-pointer" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"></path>
                            </svg>
                        </button>
                        <button onclick="openDeleteModal(${product.id}, '${escapeQuote(product.name)}')" class="inline-flex items-center justify-center p-2 text-rose-600 hover:bg-rose-50 rounded-xl transition duration-150 cursor-pointer" title="Delete">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"></path>
                            </svg>
                        </button>
                    </div>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    function handleSearchFilterChange() {
        renderProducts();
    }

    // Modal Helpers
    function openAddModal() {
        document.getElementById("modal-title").innerText = "Add Product";
        document.getElementById("product-id").value = "";
        document.getElementById("product-form").reset();
        document.getElementById("input-status").checked = true;
        document.getElementById("input-category_id").selectedIndex = 0;
        document.getElementById("input-unit_id").selectedIndex = 0;
        document.getElementById("input-counter_id").selectedIndex = 0;
        document.getElementById("input-stock").value = 0;
        
        clearValidationErrors();
        showModal();
    }

    function openEditModal(id) {
        const product = activeProducts.find(p => p.id === id);
        if (!product) return;

        document.getElementById("modal-title").innerText = "Edit Product";
        document.getElementById("product-id").value = product.id;
        document.getElementById("input-name").value = product.name;
        document.getElementById("input-sku").value = product.sku;
        document.getElementById("input-barcode").value = product.barcode || "";
        document.getElementById("input-category_id").value = product.category_id;
        document.getElementById("input-unit_id").value = product.unit_id;
        document.getElementById("input-counter_id").value = product.counter_id;
        document.getElementById("input-stock").value = product.stock;
        document.getElementById("input-buy_price").value = formatThousandSeparator(Math.round(parseFloat(product.buy_price)));
        document.getElementById("input-sell_price").value = formatThousandSeparator(Math.round(parseFloat(product.sell_price)));
        document.getElementById("input-description").value = product.description || "";
        document.getElementById("input-status").checked = !!product.status;

        clearValidationErrors();
        showModal();
    }

    function showModal() {
        const modal = document.getElementById("product-modal");
        const backdrop = document.getElementById("modal-backdrop");
        const panel = document.getElementById("modal-panel");

        modal.classList.remove("hidden");
        setTimeout(() => {
            backdrop.classList.replace("opacity-0", "opacity-100");
            panel.classList.replace("scale-95", "scale-100");
            panel.classList.replace("opacity-0", "opacity-100");
        }, 10);
    }

    function closeModal() {
        const modal = document.getElementById("product-modal");
        const backdrop = document.getElementById("modal-backdrop");
        const panel = document.getElementById("modal-panel");

        backdrop.classList.replace("opacity-100", "opacity-0");
        panel.classList.replace("scale-100", "scale-95");
        panel.classList.replace("opacity-100", "opacity-0");
        
        setTimeout(() => {
            modal.classList.add("hidden");
        }, 300);
    }

    // Category Manage Modal
    function openCategoryManageModal() {
        renderInlineCategories();
        const modal = document.getElementById("category-manage-modal");
        const backdrop = document.getElementById("category-manage-backdrop");
        const panel = document.getElementById("category-manage-panel");

        modal.classList.remove("hidden");
        setTimeout(() => {
            backdrop.classList.replace("opacity-0", "opacity-100");
            panel.classList.replace("scale-95", "scale-100");
            panel.classList.replace("opacity-0", "opacity-100");
        }, 10);
    }

    function closeCategoryManageModal() {
        const modal = document.getElementById("category-manage-modal");
        const backdrop = document.getElementById("category-manage-backdrop");
        const panel = document.getElementById("category-manage-panel");

        backdrop.classList.replace("opacity-100", "opacity-0");
        panel.classList.replace("scale-100", "scale-95");
        panel.classList.replace("opacity-100", "opacity-0");
        
        setTimeout(() => {
            modal.classList.add("hidden");
            document.getElementById("inline-category-form").reset();
        }, 300);
    }

    function renderInlineCategories() {
        const tbody = document.getElementById("inline-category-list-body");
        tbody.innerHTML = "";

        if (categoriesList.length === 0) {
            tbody.innerHTML = `<tr><td colspan="2" class="p-4 text-center text-slate-300">No categories found.</td></tr>`;
            return;
        }

        categoriesList.forEach(cat => {
            const tr = document.createElement("tr");
            tr.innerHTML = `
                <td class="p-3">
                    <div class="font-bold text-slate-800">${escapeHtml(cat.name)}</div>
                    <div class="text-[10px] text-slate-400 mt-0.5">${cat.description ? escapeHtml(cat.description) : '-'}</div>
                </td>
                <td class="p-3 text-right">
                    <button type="button" onclick="deleteInlineCategory(${cat.id})" class="text-rose-600 hover:text-rose-800 p-1 hover:bg-rose-50 rounded-lg transition shrink-0 cursor-pointer" title="Delete">
                        <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"></path>
                        </svg>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    async function handleInlineCategorySubmit(event) {
        event.preventDefault();
        const name = document.getElementById("inline-cat-name").value;
        const description = document.getElementById("inline-cat-desc").value;

        const btn = document.getElementById("btn-inline-cat-save");
        btn.disabled = true;

        try {
            const response = await fetch("/categories", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: JSON.stringify({ name, description })
            });

            if (response.status === 422) {
                const errors = await response.json();
                showToast(Object.values(errors.errors).flat().join(", "), "error");
                return;
            }

            if (!response.ok) throw new Error();

            showToast(`Category "${name}" added successfully!`);
            document.getElementById("inline-category-form").reset();
            await fetchCategories();
            renderInlineCategories();
        } catch (e) {
            showToast("Failed to create category.", "error");
        } finally {
            btn.disabled = false;
        }
    }

    async function deleteInlineCategory(id) {
        if (!confirm("Are you sure? Products belonging to this category will be deleted.")) return;

        try {
            const response = await fetch(`/categories/${id}`, {
                method: "DELETE",
                headers: {
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                }
            });

            if (!response.ok) throw new Error();

            showToast("Category deleted successfully.");
            await fetchCategories();
            renderInlineCategories();
            fetchProducts();
        } catch (e) {
            showToast("Failed to delete category.", "error");
        }
    }

    // Unit Manage Modal
    function openUnitManageModal() {
        renderInlineUnits();
        const modal = document.getElementById("unit-manage-modal");
        const backdrop = document.getElementById("unit-manage-backdrop");
        const panel = document.getElementById("unit-manage-panel");

        modal.classList.remove("hidden");
        setTimeout(() => {
            backdrop.classList.replace("opacity-0", "opacity-100");
            panel.classList.replace("scale-95", "scale-100");
            panel.classList.replace("opacity-0", "opacity-100");
        }, 10);
    }

    function closeUnitManageModal() {
        const modal = document.getElementById("unit-manage-modal");
        const backdrop = document.getElementById("unit-manage-backdrop");
        const panel = document.getElementById("unit-manage-panel");

        backdrop.classList.replace("opacity-100", "opacity-0");
        panel.classList.replace("scale-100", "scale-95");
        panel.classList.replace("opacity-100", "opacity-0");
        
        setTimeout(() => {
            modal.classList.add("hidden");
            document.getElementById("inline-unit-form").reset();
        }, 300);
    }

    function renderInlineUnits() {
        const tbody = document.getElementById("inline-unit-list-body");
        tbody.innerHTML = "";

        if (unitsList.length === 0) {
            tbody.innerHTML = `<tr><td colspan="3" class="p-4 text-center text-slate-300">No units found.</td></tr>`;
            return;
        }

        unitsList.forEach(unit => {
            const tr = document.createElement("tr");
            tr.innerHTML = `
                <td class="p-3 font-semibold text-slate-800">${escapeHtml(unit.name)}</td>
                <td class="p-3 font-semibold text-slate-500">${escapeHtml(unit.code)}</td>
                <td class="p-3 text-right">
                    <button type="button" onclick="deleteInlineUnit(${unit.id})" class="text-rose-600 hover:text-rose-800 p-1 hover:bg-rose-50 rounded-lg transition shrink-0 cursor-pointer" title="Delete">
                        <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"></path>
                        </svg>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    async function handleInlineUnitSubmit(event) {
        event.preventDefault();
        const name = document.getElementById("inline-unit-name").value;
        const code = document.getElementById("inline-unit-code").value;

        const btn = document.getElementById("btn-inline-unit-save");
        btn.disabled = true;

        try {
            const response = await fetch("/units", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: JSON.stringify({ name, code })
            });

            if (response.status === 422) {
                const errors = await response.json();
                showToast(Object.values(errors.errors).flat().join(", "), "error");
                return;
            }

            if (!response.ok) throw new Error();

            showToast(`Unit "${name}" added successfully!`);
            document.getElementById("inline-unit-form").reset();
            await fetchUnits();
            renderInlineUnits();
        } catch (e) {
            showToast("Failed to create unit.", "error");
        } finally {
            btn.disabled = false;
        }
    }

    async function deleteInlineUnit(id) {
        if (!confirm("Are you sure? Products belonging to this unit will be deleted.")) return;

        try {
            const response = await fetch(`/units/${id}`, {
                method: "DELETE",
                headers: {
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                }
            });

            if (!response.ok) throw new Error();

            showToast("Unit deleted successfully.");
            await fetchUnits();
            renderInlineUnits();
            fetchProducts();
        } catch (e) {
            showToast("Failed to delete unit.", "error");
        }
    }

    // Form Submit
    async function handleFormSubmit(event) {
        event.preventDefault();
        clearValidationErrors();

        const id = document.getElementById("product-id").value;
        const name = document.getElementById("input-name").value;
        const sku = document.getElementById("input-sku").value;
        const barcode = document.getElementById("input-barcode").value;
        const category_id = document.getElementById("input-category_id").value;
        const unit_id = document.getElementById("input-unit_id").value;
        const counter_id = document.getElementById("input-counter_id").value;
        const buy_price = document.getElementById("input-buy_price").value.replace(/\./g, "");
        const sell_price = document.getElementById("input-sell_price").value.replace(/\./g, "");
        const stock = document.getElementById("input-stock").value;
        const description = document.getElementById("input-description").value;
        const status = document.getElementById("input-status").checked;

        const data = { name, sku, barcode, category_id, unit_id, counter_id, buy_price, sell_price, stock, description, status };
        const isEdit = !!id;
        const url = isEdit ? `/products/${id}` : "/products";
        const method = isEdit ? "PUT" : "POST";

        const btnSave = document.getElementById("btn-save");
        const originalText = btnSave.innerHTML;
        btnSave.disabled = true;
        btnSave.innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Saving...
        `;

        try {
            const response = await fetch(url, {
                method: method,
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: JSON.stringify(data),
            });

            const result = await response.json();

            if (response.status === 422) {
                showValidationErrors(result.errors);
                return;
            }

            if (!response.ok) throw new Error("Could not save product details.");

            showToast(`Product "${name}" successfully ${isEdit ? 'updated' : 'created'}!`, "success");
            closeModal();
            fetchProducts();
        } catch (error) {
            showToast(error.message, "error");
        } finally {
            btnSave.disabled = false;
            btnSave.innerHTML = originalText;
        }
    }

    // Validation Display
    function showValidationErrors(errors) {
        Object.keys(errors).forEach(key => {
            const errorEl = document.getElementById(`error-${key}`);
            const inputEl = document.getElementById(`input-${key}`);
            if (errorEl) {
                errorEl.innerText = errors[key].join(", ");
                errorEl.classList.remove("hidden");
            }
            if (inputEl) {
                inputEl.classList.add("border-rose-500", "focus:border-rose-500", "focus:ring-rose-500/10");
            }
        });
    }

    function clearValidationErrors() {
        const errorElements = document.querySelectorAll("[id^='error-']");
        errorElements.forEach(el => {
            el.innerText = "";
            el.classList.add("hidden");
        });

        const inputs = document.querySelectorAll("#product-form input, #product-form select, #product-form textarea");
        inputs.forEach(input => {
            input.classList.remove("border-rose-500", "focus:border-rose-500", "focus:ring-rose-500/10");
        });
    }

    // Delete Flow
    function openDeleteModal(id, name) {
        productToDelete = id;
        document.getElementById("delete-product-name").innerText = name;

        const modal = document.getElementById("delete-modal");
        const backdrop = document.getElementById("delete-backdrop");
        const panel = document.getElementById("delete-panel");

        modal.classList.remove("hidden");
        setTimeout(() => {
            backdrop.classList.replace("opacity-0", "opacity-100");
            panel.classList.replace("scale-95", "scale-100");
            panel.classList.replace("opacity-0", "opacity-100");
        }, 10);
    }

    function closeDeleteModal() {
        const modal = document.getElementById("delete-modal");
        const backdrop = document.getElementById("delete-backdrop");
        const panel = document.getElementById("delete-panel");

        backdrop.classList.replace("opacity-100", "opacity-0");
        panel.classList.replace("scale-100", "scale-95");
        panel.classList.replace("opacity-100", "opacity-0");

        setTimeout(() => {
            modal.classList.add("hidden");
            productToDelete = null;
        }, 300);
    }

    async function handleDeleteConfirm() {
        if (!productToDelete) return;

        const btnDelete = document.getElementById("btn-delete");
        const originalText = btnDelete.innerText;
        btnDelete.disabled = true;
        btnDelete.innerText = "Deleting...";

        try {
            const response = await fetch(`/products/${productToDelete}`, {
                method: "DELETE",
                headers: {
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                }
            });

            if (!response.ok) throw new Error("Failed to delete product.");

            showToast("Product successfully deleted.", "success");
            closeDeleteModal();
            fetchProducts();
        } catch (error) {
            showToast(error.message, "error");
        } finally {
            btnDelete.disabled = false;
            btnDelete.innerText = originalText;
        }
    }

    // Toast System
    function showToast(message, type = "success") {
        const container = document.getElementById("toast-container");
        const toast = document.createElement("div");
        toast.className = "flex items-center gap-3 px-4 py-3 bg-white border border-slate-100 rounded-xl shadow-lg pointer-events-auto transform translate-y-2 opacity-0 transition-all duration-300 max-w-sm";
        
        const iconColor = type === "success" ? "text-emerald-500" : "text-rose-500";
        const iconSvg = type === "success" 
            ? `<svg class="w-5 h-5 ${iconColor}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`
            : `<svg class="w-5 h-5 ${iconColor}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>`;

        toast.innerHTML = `
            ${iconSvg}
            <p class="text-xs font-semibold text-slate-700 leading-normal pr-4">${escapeHtml(message)}</p>
            <button onclick="this.parentElement.remove()" class="text-slate-400 hover:text-slate-600 ml-auto p-1 rounded hover:bg-slate-50">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        `;

        container.appendChild(toast);
        
        setTimeout(() => {
            toast.classList.replace("translate-y-2", "translate-y-0");
            toast.classList.replace("opacity-0", "opacity-100");
        }, 10);

        setTimeout(() => {
            toast.classList.replace("translate-y-0", "translate-y-2");
            toast.classList.replace("opacity-100", "opacity-0");
            setTimeout(() => toast.remove(), 300);
        }, 4000);
    }

    // Utilities
    function formatThousandSeparator(value) {
        if (value == null) return "";
        let val = value.toString().replace(/\D/g, "");
        if (!val) return "";
        return new Intl.NumberFormat("id-ID").format(parseInt(val, 10));
    }

    function formatRupiah(amount) {
        if (amount == null) return "Rp 0";
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 2
        }).format(amount);
    }

    function escapeHtml(str) {
        if (!str) return '';
        return str.toString().replace(/&/g, "&amp;")
                  .replace(/</g, "&lt;")
                  .replace(/>/g, "&gt;")
                  .replace(/"/g, "&quot;")
                  .replace(/'/g, "&#039;");
    }

    function escapeQuote(str) {
        if (!str) return '';
        return str.toString().replace(/'/g, "\\'");
    }
</script>
@endsection

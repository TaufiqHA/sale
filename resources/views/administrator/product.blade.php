@extends('layouts.administrator')

@section('title', 'Manajemen Produk')

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
                <input type="text" id="search-input" oninput="handleSearchFilterChange()" placeholder="Cari nama produk, SKU atau barcode..." class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-brand focus:ring-2 focus:ring-brand/10 transition duration-150 text-sm bg-white placeholder:text-slate-400">
            </div>

            <!-- Counter Filter Dropdown -->
            <div class="relative shrink-0">
                <input type="hidden" id="filter-counter-id" value="">
                <button id="dropdownFilterButton" data-dropdown-toggle="dropdown-filter-counter" class="w-full sm:w-48 inline-flex items-center justify-between text-body bg-neutral-secondary-medium border border-default-medium hover:bg-neutral-tertiary focus:ring-4 focus:ring-neutral-tertiary-medium font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none cursor-pointer" type="button">
                    <span id="selected-counter-label">Semua Counter</span>
                    <svg class="w-4 h-4 ms-1.5 -me-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/></svg>
                </button>
                <div id="dropdown-filter-counter" class="z-10 hidden bg-neutral-primary-soft border border-default rounded-base shadow-lg w-48">
                    <ul class="p-2 text-sm text-body font-medium" aria-labelledby="dropdownFilterButton" id="filter-counter-options">
                        <li>
                            <button type="button" onclick="selectCounterFilter('', 'Semua Counter')" class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary hover:text-heading rounded text-left cursor-pointer">
                                Semua Counter
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Add Button -->
        <button onclick="openAddModal()" class="inline-flex items-center justify-center text-white bg-brand box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none cursor-pointer shrink-0 gap-1.5">
            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7 7V5"/>
            </svg>
            Tambah Produk
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
        <h3 class="text-base font-bold text-slate-800">Tidak Ada Produk Terdaftar</h3>
        <button onclick="openAddModal()" class="mt-4 inline-flex items-center justify-center text-white bg-brand box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none cursor-pointer gap-1.5">
            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7 7V5"/>
            </svg>
            Tambah Produk
        </button>
    </div>

    <!-- Product Table -->
    <div id="product-table-wrapper" class="hidden relative overflow-x-auto bg-neutral-primary-soft shadow-xs rounded-base border border-default mb-8">
        <table class="w-full text-sm text-left rtl:text-right text-body">
            <thead class="text-sm text-body bg-neutral-secondary-medium border-b border-default-medium">
                <tr>
                    <th scope="col" class="px-6 py-3 font-medium w-16 text-center">Gambar</th>
                    <th scope="col" class="px-6 py-3 font-medium">SKU / Barcode</th>
                    <th scope="col" class="px-6 py-3 font-medium">Nama Produk</th>
                    <th scope="col" class="px-6 py-3 font-medium">Kategori</th>
                    <th scope="col" class="px-6 py-3 font-medium">Satuan</th>
                    <th scope="col" class="px-6 py-3 font-medium">Counter</th>
                    <th scope="col" class="px-6 py-3 font-medium">Stok</th>
                    <th scope="col" class="px-6 py-3 font-medium">Harga Beli</th>
                    <th scope="col" class="px-6 py-3 font-medium">Harga Jual</th>
                    <th scope="col" class="px-6 py-3 font-medium">Status</th>
                    <th scope="col" class="px-6 py-3 font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody id="product-table-body">
                <!-- Rendered dynamically -->
            </tbody>
        </table>
    </div>
</div>

<!-- Modal (Create / Edit) -->
<div id="product-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden" role="dialog" aria-modal="true">
    <!-- Backdrop with blur -->
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300 opacity-0" id="modal-backdrop" onclick="closeModal()"></div>
    
    <!-- Modal content wrapper (Flowbite style max-w-2xl) -->
    <div class="relative w-full max-w-2xl max-h-[95vh] flex flex-col transform scale-95 opacity-0 transition-all duration-300" id="modal-panel">
        <div class="relative bg-neutral-primary-soft border border-default rounded-base shadow-sm p-4 md:p-6 flex flex-col overflow-hidden max-h-full">
            <!-- Modal header -->
            <div class="flex items-center justify-between border-b border-default pb-4 md:pb-5 shrink-0">
                <h3 id="modal-title" class="text-lg font-medium text-heading">
                    Tambah Produk
                </h3>
                <button type="button" onclick="closeModal()" class="text-body bg-transparent hover:bg-neutral-tertiary hover:text-heading rounded-base text-sm w-9 h-9 ms-auto inline-flex justify-center items-center cursor-pointer">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/></svg>
                    <span class="sr-only">Tutup modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <form id="product-form" onsubmit="handleFormSubmit(event)" class="overflow-y-auto flex-1 pr-1">
                <input type="hidden" id="product-id" name="id">
                
                <div class="grid gap-4 grid-cols-2 py-4 md:py-6">
                    <!-- Name -->
                    <div class="col-span-2">
                        <label for="input-name" class="block mb-2.5 text-sm font-medium text-heading">Nama Produk</label>
                        <input type="text" id="input-name" name="name" class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body" placeholder="contoh: Coca-Cola 330ml" required>
                        <p id="error-name" class="mt-2 text-xs font-medium text-rose-500 hidden"></p>
                    </div>

                    <!-- SKU -->
                    <div class="col-span-2 sm:col-span-1">
                        <label for="input-sku" class="block mb-2.5 text-sm font-medium text-heading">SKU</label>
                        <input type="text" id="input-sku" name="sku" class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body" placeholder="contoh: SKU-DRK-COCA" required>
                        <p id="error-sku" class="mt-2 text-xs font-medium text-rose-500 hidden"></p>
                    </div>

                    <!-- Barcode -->
                    <div class="col-span-2 sm:col-span-1">
                        <label for="input-barcode" class="block mb-2.5 text-sm font-medium text-heading">Barcode</label>
                        <input type="text" id="input-barcode" name="barcode" class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body" placeholder="contoh: 8886007810123">
                        <p id="error-barcode" class="mt-2 text-xs font-medium text-rose-500 hidden"></p>
                    </div>

                    <!-- Category -->
                    <div class="col-span-2 sm:col-span-1">
                        <div class="flex items-end gap-2">
                            <div class="flex-1">
                                <label for="input-category_id" class="block mb-2.5 text-sm font-medium text-heading">Kategori</label>
                                <select id="input-category_id" name="category_id" class="block w-full px-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand shadow-xs placeholder:text-body" required>
                                    <option value="" disabled selected>Pilih Kategori</option>
                                </select>
                            </div>
                            <button type="button" onclick="openCategoryManageModal()" class="px-3.5 py-2.5 bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading rounded-base text-sm font-medium focus:outline-none transition duration-150 inline-flex items-center gap-1.5 shrink-0 cursor-pointer" title="Kelola Kategori">
                                Kelola
                            </button>
                        </div>
                        <p id="error-category_id" class="mt-2 text-xs font-medium text-rose-500 hidden"></p>
                    </div>

                    <!-- Unit -->
                    <div class="col-span-2 sm:col-span-1">
                        <div class="flex items-end gap-2">
                            <div class="flex-1">
                                <label for="input-unit_id" class="block mb-2.5 text-sm font-medium text-heading">Satuan</label>
                                <select id="input-unit_id" name="unit_id" class="block w-full px-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand shadow-xs placeholder:text-body" required>
                                    <option value="" disabled selected>Pilih Satuan</option>
                                </select>
                            </div>
                            <button type="button" onclick="openUnitManageModal()" class="px-3.5 py-2.5 bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading rounded-base text-sm font-medium focus:outline-none transition duration-150 inline-flex items-center gap-1.5 shrink-0 cursor-pointer" title="Kelola Satuan">
                                Kelola
                            </button>
                        </div>
                        <p id="error-unit_id" class="mt-2 text-xs font-medium text-rose-500 hidden"></p>
                    </div>

                    <!-- Counter -->
                    <div class="col-span-2 sm:col-span-1">
                        <label for="input-counter_id" class="block mb-2.5 text-sm font-medium text-heading">Counter</label>
                        <select id="input-counter_id" name="counter_id" class="block w-full px-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand shadow-xs placeholder:text-body" required>
                            <option value="" disabled selected>Pilih Counter</option>
                        </select>
                        <p id="error-counter_id" class="mt-2 text-xs font-medium text-rose-500 hidden"></p>
                    </div>

                    <!-- Stock -->
                    <div class="col-span-2 sm:col-span-1">
                        <label for="input-stock" class="block mb-2.5 text-sm font-medium text-heading">Stok</label>
                        <input type="number" id="input-stock" name="stock" min="0" class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body" placeholder="contoh: 10" required>
                        <p id="error-stock" class="mt-2 text-xs font-medium text-rose-500 hidden"></p>
                    </div>

                    <!-- Buy Price -->
                    <div class="col-span-2 sm:col-span-1">
                        <label for="input-buy_price" class="block mb-2.5 text-sm font-medium text-heading">Harga Beli (Rp)</label>
                        <input type="text" id="input-buy_price" name="buy_price" class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body" placeholder="contoh: 5.000" required>
                        <p id="error-buy_price" class="mt-2 text-xs font-medium text-rose-500 hidden"></p>
                    </div>

                    <!-- Sell Price -->
                    <div class="col-span-2 sm:col-span-1">
                        <label for="input-sell_price" class="block mb-2.5 text-sm font-medium text-heading">Harga Jual (Rp)</label>
                        <input type="text" id="input-sell_price" name="sell_price" class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body" placeholder="contoh: 7.000" required>
                        <p id="error-sell_price" class="mt-2 text-xs font-medium text-rose-500 hidden"></p>
                    </div>

                    <!-- Description -->
                    {{-- <div class="col-span-2">
                        <label for="input-description" class="block mb-2.5 text-sm font-medium text-heading">Deskripsi Produk</label>
                        <textarea id="input-description" name="description" rows="3" class="block bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full p-3.5 shadow-xs placeholder:text-body" placeholder="Detail produk atau catatan..."></textarea>
                        <p id="error-description" class="mt-2 text-xs font-medium text-rose-500 hidden"></p>
                    </div> --}}

                    <!-- Gambar Produk -->
                    <div class="col-span-2 bg-neutral-secondary-soft p-4 rounded-base border border-default">
                        <label class="block mb-2 text-sm font-medium text-heading">Gambar Produk</label>
                        <div class="flex items-center gap-4">
                            <!-- Image Preview Frame -->
                            <div id="image-preview-container" class="relative w-20 h-20 rounded-lg border border-default-medium flex items-center justify-center overflow-hidden bg-white group shrink-0">
                                <span id="image-placeholder" class="text-slate-400">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                                </span>
                                <img id="image-preview" class="w-full h-full object-cover hidden" alt="Preview">
                                <button type="button" id="btn-remove-image" onclick="removeSelectedImage()" class="absolute inset-0 bg-black/40 items-center justify-center text-white opacity-0 group-hover:opacity-100 transition-opacity hidden">
                                    <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                            <!-- Upload Controls -->
                            <div class="flex flex-col gap-1.5">
                                <label for="input-image" class="inline-flex items-center justify-center bg-white border border-default-medium text-heading text-xs font-semibold px-3 py-2 rounded-base hover:bg-neutral-secondary-soft focus:outline-none focus:ring-2 focus:ring-brand cursor-pointer transition">
                                    Pilih File
                                </label>
                                <input type="file" id="input-image" name="image" accept="image/*" onchange="previewImageFile(event)" class="hidden">
                                <span class="text-[11px] text-body opacity-60">Format: JPG, PNG, WEBP, GIF (Max. 2MB)</span>
                            </div>
                        </div>
                        <input type="hidden" id="remove-image-flag" value="0">
                        <p id="error-image" class="mt-2 text-xs font-medium text-rose-500 hidden"></p>
                    </div>

                    <!-- Wholeprice Checkbox -->
                    <div class="col-span-2 flex items-center gap-3 py-2 border-t border-default pt-4">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="input-is_wholeprice" name="is_wholeprice" class="sr-only peer" onchange="toggleWholepriceSection(this.checked)">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-brand/10 rounded-full peer peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand"></div>
                            <span class="ml-3 text-sm font-semibold text-slate-700">Aktifkan Harga Grosir</span>
                        </label>
                        <p id="error-is_wholeprice" class="mt-2 text-xs font-medium text-rose-500 hidden"></p>
                    </div>

                    <!-- Wholeprice Section -->
                    <div id="wholeprice-section" class="col-span-2 hidden bg-neutral-secondary-soft p-4 rounded-base border border-default">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-sm font-semibold text-heading">Daftar Harga Grosir</h4>
                            <button type="button" onclick="addWholepriceRow()" class="px-3 py-1.5 bg-brand text-white hover:bg-brand-strong rounded-base text-xs font-semibold focus:outline-none transition duration-150 inline-flex items-center gap-1.5 cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                Tambah Level
                            </button>
                        </div>
                        <div id="wholeprice-rows-container" class="flex flex-col gap-3">
                            <!-- Rows loaded dynamically by JS -->
                        </div>
                        <p id="error-wholeprices" class="mt-2 text-xs font-medium text-rose-500 hidden"></p>
                    </div>

                    <!-- Status Checkbox -->
                    <div class="col-span-2 flex items-center gap-3 py-2">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="input-status" name="status" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-brand/10 rounded-full peer peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand"></div>
                            <span class="ml-3 text-sm font-semibold text-slate-700">Aktif</span>
                        </label>
                        <p id="error-status" class="mt-2 text-xs font-medium text-rose-500 hidden"></p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4 border-t border-default pt-4 md:pt-6">
                    <button type="submit" id="btn-save" class="inline-flex items-center text-white bg-brand hover:bg-brand-strong box-border border border-transparent focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none cursor-pointer">
                        <svg class="w-4 h-4 me-1.5 -ms-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7 7V5"/></svg>
                        Simpan Produk
                    </button>
                    <button type="button" onclick="closeModal()" class="text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none cursor-pointer">Batal</button>
                </div>
            </form>
        </div>
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
                <h3 class="text-base font-bold text-slate-800">Hapus Produk</h3>
                <p class="text-sm text-slate-500 mt-1">Apakah Anda yakin ingin menghapus <span id="delete-product-name" class="font-semibold text-slate-700"></span>? Tindakan ini akan menghapus produk secara permanen.</p>
            </div>
        </div>
        
        <div class="flex items-center justify-end gap-3 pt-2">
            <button onclick="closeDeleteModal()" class="px-4 py-2.5 text-sm font-semibold text-slate-500 hover:text-slate-800 hover:bg-slate-50 rounded-xl transition duration-150">
                Batal
            </button>
            <button onclick="handleDeleteConfirm()" id="btn-delete" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold rounded-xl transition duration-150">
                Hapus
            </button>
        </div>
    </div>
</div>

<!-- Modal (Manage Categories) -->
<div id="category-manage-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden" role="dialog" aria-modal="true">
    <!-- Backdrop with blur -->
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300 opacity-0" id="category-manage-backdrop" onclick="closeCategoryManageModal()"></div>
    
    <!-- Modal content -->
    <div class="relative w-full max-w-md flex flex-col transform scale-95 opacity-0 transition-all duration-300" id="category-manage-panel">
        <div class="relative bg-neutral-primary-soft border border-default rounded-base shadow-sm p-4 md:p-6 flex flex-col overflow-hidden max-h-full">
            <!-- Modal header -->
            <div class="flex items-center justify-between border-b border-default pb-4 md:pb-5">
                <h3 class="text-lg font-medium text-heading">Kelola Kategori</h3>
                <button onclick="closeCategoryManageModal()" class="text-body bg-transparent hover:bg-neutral-tertiary hover:text-heading rounded-base text-sm w-9 h-9 ms-auto inline-flex justify-center items-center cursor-pointer">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/></svg>
                    <span class="sr-only">Tutup modal</span>
                </button>
            </div>
            
            <div class="py-4 md:py-6 space-y-6">
                <!-- Add Category Inline Form -->
                <form id="inline-category-form" onsubmit="handleInlineCategorySubmit(event)" class="space-y-3 bg-neutral-secondary-soft p-4 rounded-base border border-default">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-heading">Tambah Kategori Cepat</h4>
                    <div class="space-y-1.5">
                        <input type="text" id="inline-cat-name" placeholder="Nama Kategori (contoh: Minuman)" class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2 shadow-xs placeholder:text-body" required>
                    </div>
                    {{-- <div class="space-y-1.5">
                        <input type="text" id="inline-cat-desc" placeholder="Deskripsi Singkat (opsional)" class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2 shadow-xs placeholder:text-body">
                    </div> --}}
                    <button type="submit" id="btn-inline-cat-save" class="w-full text-white bg-brand hover:bg-brand-strong box-border border border-transparent focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2 focus:outline-none cursor-pointer">
                        Tambah Kategori
                    </button>
                </form>

                <!-- Scrollable Category List -->
                <div class="space-y-2">
                    <h4 class="text-xs font-semibold uppercase tracking-wider text-heading opacity-80">Kategori yang Ada</h4>
                    <div class="max-h-60 overflow-y-auto divide-y divide-default border border-default rounded-base pr-1 bg-neutral-secondary-soft">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead>
                                <tr class="bg-neutral-secondary-medium text-heading border-b border-default font-semibold">
                                    <th class="p-3">Nama</th>
                                    <th class="p-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="inline-category-list-body" class="divide-y divide-default text-body">
                                <!-- Rendered dynamically -->
                            </tbody>
                        </table>
                    </div>
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
    <div class="relative w-full max-w-md flex flex-col transform scale-95 opacity-0 transition-all duration-300" id="unit-manage-panel">
        <div class="relative bg-neutral-primary-soft border border-default rounded-base shadow-sm p-4 md:p-6 flex flex-col overflow-hidden max-h-full">
            <!-- Modal header -->
            <div class="flex items-center justify-between border-b border-default pb-4 md:pb-5">
                <h3 class="text-lg font-medium text-heading">Kelola Satuan</h3>
                <button onclick="closeUnitManageModal()" class="text-body bg-transparent hover:bg-neutral-tertiary hover:text-heading rounded-base text-sm w-9 h-9 ms-auto inline-flex justify-center items-center cursor-pointer">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/></svg>
                    <span class="sr-only">Tutup modal</span>
                </button>
            </div>
            
            <div class="py-4 md:py-6 space-y-6">
                <!-- Add Unit Inline Form -->
                <form id="inline-unit-form" onsubmit="handleInlineUnitSubmit(event)" class="space-y-3 bg-neutral-secondary-soft p-4 rounded-base border border-default">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-heading">Tambah Satuan Cepat</h4>
                    <div class="space-y-1.5">
                        <input type="text" id="inline-unit-name" placeholder="Nama (contoh: Pieces)" class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2 shadow-xs placeholder:text-body" required>
                    </div>
                    <button type="submit" id="btn-inline-unit-save" class="w-full text-white bg-brand hover:bg-brand-strong box-border border border-transparent focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2 focus:outline-none cursor-pointer">
                        Tambah Satuan
                    </button>
                </form>

                <!-- Scrollable Unit List -->
                <div class="space-y-2">
                    <h4 class="text-xs font-semibold uppercase tracking-wider text-heading opacity-80">Satuan yang Ada</h4>
                    <div class="max-h-60 overflow-y-auto divide-y divide-default border border-default rounded-base pr-1 bg-neutral-secondary-soft">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead>
                                <tr class="bg-neutral-secondary-medium text-heading border-b border-default font-semibold">
                                    <th class="p-3">Nama</th>
                                    <th class="p-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="inline-unit-list-body" class="divide-y divide-default text-body">
                                <!-- Rendered dynamically -->
                            </tbody>
                        </table>
                    </div>
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
            select.innerHTML = '<option value="" disabled selected>Pilih Kategori</option>';
            categoriesList.forEach(cat => {
                select.innerHTML += `<option value="${cat.id}">${escapeHtml(cat.name)}</option>`;
            });
        } catch (e) {
            showToast("Gagal memuat kategori.", "error");
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
            select.innerHTML = '<option value="" disabled selected>Pilih Satuan</option>';
            unitsList.forEach(unit => {
                select.innerHTML += `<option value="${unit.id}">${escapeHtml(unit.name)}</option>`;
            });
        } catch (e) {
            showToast("Gagal memuat satuan.", "error");
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
            select.innerHTML = '<option value="" disabled selected>Pilih Counter</option>';
            countersList.forEach(counter => {
                select.innerHTML += `<option value="${counter.id}">${escapeHtml(counter.name)}</option>`;
            });

            populateCounterFilter();
        } catch (e) {
            showToast("Gagal memuat counter.", "error");
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

            if (!response.ok) throw new Error("Gagal mengambil produk.");

            activeProducts = await response.json();
            renderProducts();
        } catch (error) {
            showToast("Gagal memuat produk. Silakan coba lagi.", "error");
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
                            <span class="text-sm font-semibold">Tidak ada produk yang cocok dengan kriteria Anda.</span>
                        </div>
                    </td>
                </tr>
            `;
            return;
        }

        filteredProducts.forEach(product => {
            const row = document.createElement("tr");
            row.className = "bg-neutral-primary-soft border-b border-default hover:bg-neutral-secondary-medium transition-colors duration-150";
            
            const badgeClass = product.status 
                ? "bg-emerald-50 text-emerald-700 font-semibold" 
                : "bg-slate-100 text-slate-500 font-medium";
            const badgeText = product.status ? "Aktif" : "Tidak Aktif";

            const catName = product.category ? product.category.name : 'Tanpa Kategori';
            const unitName = product.unit ? product.unit.name : '-';
            const counterName = product.counter ? product.counter.name : '-';

            const imageHtml = product.image 
                ? `<img src="/storage/${product.image}" class="w-10 h-10 object-cover rounded-md mx-auto" alt="${escapeHtml(product.name)}">`
                : `<div class="w-10 h-10 bg-slate-100 rounded-md flex items-center justify-center text-slate-400 mx-auto">
                     <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                   </div>`;

            row.innerHTML = `
                <td class="px-6 py-4 text-center">
                    ${imageHtml}
                </td>
                <td class="px-6 py-4">
                    <div class="font-bold text-body">${escapeHtml(product.sku)}</div>
                    <div class="text-xs text-body opacity-60 mt-0.5">${product.barcode ? escapeHtml(product.barcode) : '<span class="text-body opacity-40">Tanpa Barcode</span>'}</div>
                </td>
                <th scope="row" class="px-6 py-4 font-medium text-heading whitespace-nowrap text-left">${escapeHtml(product.name)}</th>
                <td class="px-6 py-4 text-xs font-semibold text-body">${escapeHtml(catName)}</td>
                <td class="px-6 py-4 text-xs font-medium text-body opacity-80">${escapeHtml(unitName)}</td>
                <td class="px-6 py-4 text-xs font-semibold text-body">${escapeHtml(counterName)}</td>
                <td class="px-6 py-4 text-xs font-bold text-body">${product.stock}</td>
                <td class="px-6 py-4 font-semibold text-body">${formatRupiah(product.buy_price)}</td>
                <td class="px-6 py-4 font-semibold text-fg-brand">
                    ${formatRupiah(product.sell_price)}
                    ${product.is_wholeprice ? `<div class="text-[10px] text-emerald-600 font-semibold mt-1">Grosir Aktif</div>` : ''}
                </td>
                <td class="px-6 py-4">
                    <span class="px-2.5 py-1 text-[11px] rounded-full ${badgeClass}">
                        ${badgeText}
                    </span>
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end gap-3">
                        <button onclick="openEditModal(${product.id})" class="font-medium text-fg-brand hover:underline cursor-pointer" title="Ubah">Ubah</button>
                        <button onclick="openDeleteModal(${product.id}, '${escapeQuote(product.name)}')" class="font-medium text-fg-danger hover:underline cursor-pointer" title="Hapus">Hapus</button>
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
        document.getElementById("modal-title").innerText = "Tambah Produk";
        document.getElementById("product-id").value = "";
        document.getElementById("product-form").reset();
        document.getElementById("input-status").checked = true;
        document.getElementById("input-category_id").selectedIndex = 0;
        document.getElementById("input-unit_id").selectedIndex = 0;
        document.getElementById("input-counter_id").selectedIndex = 0;
        document.getElementById("input-stock").value = 0;
        
        document.getElementById("input-is_wholeprice").checked = false;
        toggleWholepriceSection(false);
        document.getElementById("wholeprice-rows-container").innerHTML = "";

        resetImagePreview();
        clearValidationErrors();
        showModal();
    }

    function openEditModal(id) {
        const product = activeProducts.find(p => p.id === id);
        if (!product) return;

        document.getElementById("modal-title").innerText = "Ubah Produk";
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
        const descInput = document.getElementById("input-description");
        if (descInput) {
            descInput.value = product.description || "";
        }
        document.getElementById("input-status").checked = !!product.status;

        // Reset and populate wholeprice
        document.getElementById("wholeprice-rows-container").innerHTML = "";
        const isWholeprice = !!product.is_wholeprice;
        document.getElementById("input-is_wholeprice").checked = isWholeprice;
        toggleWholepriceSection(isWholeprice);
        if (isWholeprice && product.wholeprices) {
            product.wholeprices.forEach(tier => {
                addWholepriceRow(tier.minimum_qty, formatThousandSeparator(Math.round(parseFloat(tier.wholeprice_price))));
            });
        }

        resetImagePreview();
        if (product.image) {
            const img = document.getElementById("image-preview");
            img.src = `/storage/${product.image}`;
            img.classList.remove("hidden");
            document.getElementById("image-placeholder").classList.add("hidden");
            document.getElementById("btn-remove-image").classList.replace("hidden", "flex");
        }

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
            resetImagePreview();
        }, 300);
    }

    // Wholeprice Helpers
    function toggleWholepriceSection(show) {
        const section = document.getElementById("wholeprice-section");
        if (show) {
            section.classList.remove("hidden");
        } else {
            section.classList.add("hidden");
        }
    }

    function addWholepriceRow(minQty = '', price = '') {
        const container = document.getElementById("wholeprice-rows-container");
        const row = document.createElement("div");
        row.className = "wholeprice-row flex items-end gap-3 bg-white p-3 rounded-lg border border-default-medium";
        row.innerHTML = `
            <div class="flex-1">
                <label class="block mb-1.5 text-[11px] font-semibold text-slate-500">Min. Qty</label>
                <input type="number" min="1" class="input-min-qty bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-2.5 py-1.5 shadow-xs" placeholder="Misal: 10" value="${minQty}" required>
            </div>
            <div class="flex-1">
                <label class="block mb-1.5 text-[11px] font-semibold text-slate-500">Harga Grosir (Rp)</label>
                <input type="text" class="input-wholeprice-price bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-2.5 py-1.5 shadow-xs" placeholder="Misal: 6.500" value="${price}" oninput="this.value = formatThousandSeparator(this.value)" required>
            </div>
            <div class="shrink-0">
                <button type="button" onclick="removeWholepriceRow(this)" class="text-rose-600 hover:text-rose-800 p-1.5 hover:bg-rose-50 rounded-lg transition cursor-pointer" title="Hapus Level">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </div>
        `;
        container.appendChild(row);
    }

    function removeWholepriceRow(button) {
        const row = button.closest(".wholeprice-row");
        if (row) {
            row.remove();
        }
    }

    // Image Upload Preview & Removal Helpers
    function previewImageFile(event) {
        const input = event.target;
        const file = input.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById("image-preview");
                img.src = e.target.result;
                img.classList.remove("hidden");
                document.getElementById("image-placeholder").classList.add("hidden");
                document.getElementById("btn-remove-image").classList.replace("hidden", "flex");
                document.getElementById("remove-image-flag").value = "0";
            };
            reader.readAsDataURL(file);
        }
    }

    function removeSelectedImage() {
        document.getElementById("input-image").value = "";
        const img = document.getElementById("image-preview");
        img.src = "";
        img.classList.add("hidden");
        document.getElementById("image-placeholder").classList.remove("hidden");
        document.getElementById("btn-remove-image").classList.replace("flex", "hidden");
        document.getElementById("remove-image-flag").value = "1";
    }

    function resetImagePreview() {
        document.getElementById("input-image").value = "";
        const img = document.getElementById("image-preview");
        img.src = "";
        img.classList.add("hidden");
        document.getElementById("image-placeholder").classList.remove("hidden");
        document.getElementById("btn-remove-image").classList.replace("flex", "hidden");
        document.getElementById("remove-image-flag").value = "0";
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
            tbody.innerHTML = `<tr><td colspan="2" class="p-4 text-center text-slate-300">Kategori tidak ditemukan.</td></tr>`;
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
        const descEl = document.getElementById("inline-cat-desc");
        const description = descEl ? descEl.value : "";

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

            showToast(`Kategori "${name}" berhasil ditambahkan!`);
            document.getElementById("inline-category-form").reset();
            await fetchCategories();
            renderInlineCategories();
        } catch (e) {
            showToast("Gagal membuat kategori.", "error");
        } finally {
            btn.disabled = false;
        }
    }

    async function deleteInlineCategory(id) {
        if (!confirm("Apakah Anda yakin? Produk yang termasuk dalam kategori ini juga akan dihapus.")) return;

        try {
            const response = await fetch(`/categories/${id}`, {
                method: "DELETE",
                headers: {
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                }
            });

            if (!response.ok) throw new Error();

            showToast("Kategori berhasil dihapus.");
            await fetchCategories();
            renderInlineCategories();
            fetchProducts();
        } catch (e) {
            showToast("Gagal menghapus kategori.", "error");
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
            tbody.innerHTML = `<tr><td colspan="2" class="p-4 text-center text-slate-300">Satuan tidak ditemukan.</td></tr>`;
            return;
        }

        unitsList.forEach(unit => {
            const tr = document.createElement("tr");
            tr.innerHTML = `
                <td class="p-3 font-semibold text-slate-800">${escapeHtml(unit.name)}</td>
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
                body: JSON.stringify({ name })
            });

            if (response.status === 422) {
                const errors = await response.json();
                showToast(Object.values(errors.errors).flat().join(", "), "error");
                return;
            }

            if (!response.ok) throw new Error();

            showToast(`Satuan "${name}" berhasil ditambahkan!`);
            document.getElementById("inline-unit-form").reset();
            await fetchUnits();
            renderInlineUnits();
        } catch (e) {
            showToast("Gagal membuat satuan.", "error");
        } finally {
            btn.disabled = false;
        }
    }

    async function deleteInlineUnit(id) {
        if (!confirm("Apakah Anda yakin? Produk yang menggunakan satuan ini juga akan dihapus.")) return;

        try {
            const response = await fetch(`/units/${id}`, {
                method: "DELETE",
                headers: {
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                }
            });

            if (!response.ok) throw new Error();

            showToast("Satuan berhasil dihapus.");
            await fetchUnits();
            renderInlineUnits();
            fetchProducts();
        } catch (e) {
            showToast("Gagal menghapus satuan.", "error");
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
        const descInput = document.getElementById("input-description");
        const description = descInput ? descInput.value : "";
        const status = document.getElementById("input-status").checked;
        const isWholeprice = document.getElementById("input-is_wholeprice").checked;

        const formData = new FormData();
        formData.append("name", name);
        formData.append("sku", sku);
        if (barcode) {
            formData.append("barcode", barcode);
        }
        formData.append("category_id", category_id);
        formData.append("unit_id", unit_id);
        formData.append("counter_id", counter_id);
        formData.append("buy_price", buy_price);
        formData.append("sell_price", sell_price);
        formData.append("stock", stock);
        formData.append("description", description);
        formData.append("status", status ? "1" : "0");
        formData.append("is_wholeprice", isWholeprice ? "1" : "0");

        if (isWholeprice) {
            const rows = document.querySelectorAll(".wholeprice-row");
            rows.forEach((row, index) => {
                const minQty = row.querySelector(".input-min-qty").value;
                const price = row.querySelector(".input-wholeprice-price").value.replace(/\./g, "");
                formData.append(`wholeprices[${index}][minimum_qty]`, minQty);
                formData.append(`wholeprices[${index}][wholeprice_price]`, price);
            });
        }

        const imageFileInput = document.getElementById("input-image");
        if (imageFileInput.files.length > 0) {
            formData.append("image", imageFileInput.files[0]);
        }

        const removeImageFlag = document.getElementById("remove-image-flag").value;
        if (removeImageFlag === "1") {
            formData.append("remove_image", "1");
        }

        const isEdit = !!id;
        const url = isEdit ? `/products/${id}` : "/products";
        const method = "POST"; // Use POST for multipart form uploads

        if (isEdit) {
            formData.append("_method", "PUT");
        }

        const btnSave = document.getElementById("btn-save");
        const originalText = btnSave.innerHTML;
        btnSave.disabled = true;
        btnSave.innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Menyimpan...
        `;

        try {
            const response = await fetch(url, {
                method: method,
                headers: {
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: formData,
            });

            const result = await response.json();

            if (response.status === 422) {
                showValidationErrors(result.errors);
                return;
            }

            if (!response.ok) throw new Error("Gagal menyimpan detail produk.");

            showToast(`Produk "${name}" berhasil ${isEdit ? 'diperbarui' : 'dibuat'}!`, "success");
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
            if (key.includes('.')) {
                const generalErrorEl = document.getElementById("error-wholeprices");
                if (generalErrorEl) {
                    generalErrorEl.innerText = errors[key].join(", ");
                    generalErrorEl.classList.remove("hidden");
                }
                return;
            }
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
        btnDelete.innerText = "Menghapus...";

        try {
            const response = await fetch(`/products/${productToDelete}`, {
                method: "DELETE",
                headers: {
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                }
            });

            if (!response.ok) throw new Error("Gagal menghapus produk.");

            showToast("Produk berhasil dihapus.", "success");
            closeDeleteModal();
            fetchProducts();
        } catch (error) {
            showToast(error.message, "error");
        } finally {
            btnDelete.disabled = false;
            btnDelete.innerText = originalText;
        }
    }

    function populateCounterFilter() {
        const optionsList = document.getElementById("filter-counter-options");
        if (!optionsList) {
            return;
        }

        let html = `
            <li>
                <button type="button" onclick="selectCounterFilter('', 'Semua Counter')" class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary hover:text-heading rounded text-left cursor-pointer">
                    Semua Counter
                </button>
            </li>
        `;

        countersList.forEach(counter => {
            html += `
                <li>
                    <button type="button" onclick="selectCounterFilter('${counter.id}', '${escapeHtml(counter.name)}')" class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary hover:text-heading rounded text-left cursor-pointer">
                        ${escapeHtml(counter.name)}
                    </button>
                </li>
            `;
        });

        optionsList.innerHTML = html;
    }

    function selectCounterFilter(id, name) {
        document.getElementById("filter-counter-id").value = id;
        document.getElementById("selected-counter-label").innerText = name;
        
        const button = document.getElementById("dropdownFilterButton");
        if (button) {
            button.click();
        }

        handleSearchFilterChange();
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

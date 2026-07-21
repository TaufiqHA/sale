@extends('layouts.administrator')

@section('title', 'Monitoring Stok')

@section('content')
<div class="relative min-h-[calc(100vh-8rem)]">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-xl font-bold text-heading">Monitoring Stok</h1>
            <p class="text-xs text-body mt-0.5">Pantau tingkat persediaan stok produk dan identifikasi kebutuhan restock</p>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Products Card -->
        <div class="bg-white p-5 rounded-xl border border-slate-200/70 shadow-xs flex items-center gap-4">
            <div class="p-3.5 bg-[#00B4D8] text-white shadow-xs rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Produk</h3>
                <p id="summary-total-products" class="text-2xl font-bold text-slate-900 mt-1">0</p>
            </div>
        </div>

        <!-- Low Stock Card -->
        <div class="bg-white p-5 rounded-xl border border-slate-200/70 shadow-xs flex items-center gap-4">
            <div class="p-3.5 bg-[#FF8A00] text-white shadow-xs rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Stok Menipis (≤ 5)</h3>
                <p id="summary-low-stock" class="text-2xl font-bold text-[#FF8A00] mt-1">0</p>
            </div>
        </div>

        <!-- Out of Stock Card -->
        <div class="bg-white p-5 rounded-xl border border-slate-200/70 shadow-xs flex items-center gap-4">
            <div class="p-3.5 bg-[#FF5252] text-white shadow-xs rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Habis</h3>
                <p id="summary-out-stock" class="text-2xl font-bold text-[#FF5252] mt-1">0</p>
            </div>
        </div>
    </div>

    <!-- Filters & Search Bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <!-- Search and Counter Filter -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 flex-1 max-w-3xl">
            <!-- Search -->
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" id="search-input" oninput="handleSearchFilterChange()" placeholder="Cari nama produk..." class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-brand focus:ring-2 focus:ring-brand/10 transition duration-150 text-sm bg-white placeholder:text-slate-400">
            </div>

            <!-- Counter Filter -->
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

            <!-- Stock Status Filter -->
            <div class="relative shrink-0">
                <input type="hidden" id="filter-stock-status" value="">
                <button id="dropdownStockButton" data-dropdown-toggle="dropdown-filter-stock" class="w-full sm:w-48 inline-flex items-center justify-between text-body bg-neutral-secondary-medium border border-default-medium hover:bg-neutral-tertiary focus:ring-4 focus:ring-neutral-tertiary-medium font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none cursor-pointer" type="button">
                    <span id="selected-stock-label">Semua Status Stok</span>
                    <svg class="w-4 h-4 ms-1.5 -me-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/></svg>
                </button>
                <div id="dropdown-filter-stock" class="z-10 hidden bg-neutral-primary-soft border border-default rounded-base shadow-lg w-48">
                    <ul class="p-2 text-sm text-body font-medium" aria-labelledby="dropdownStockButton">
                        <li>
                            <button type="button" onclick="selectStockFilter('', 'Semua Status Stok')" class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary hover:text-heading rounded text-left cursor-pointer">
                                Semua Status Stok
                            </button>
                        </li>
                        <li>
                            <button type="button" onclick="selectStockFilter('out', 'Habis (0)')" class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary hover:text-heading rounded text-left cursor-pointer text-rose-600">
                                Habis (0)
                            </button>
                        </li>
                        <li>
                            <button type="button" onclick="selectStockFilter('low', 'Menipis (≤ 5)')" class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary hover:text-heading rounded text-left cursor-pointer text-amber-600">
                                Menipis (≤ 5)
                            </button>
                        </li>
                        <li>
                            <button type="button" onclick="selectStockFilter('good', 'Cukup (> 5)')" class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary hover:text-heading rounded text-left cursor-pointer text-emerald-600">
                                Cukup (> 5)
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading State -->
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
                <div class="h-4 bg-slate-100 rounded-md w-16 shrink-0"></div>
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
        <h3 class="text-base font-bold text-slate-800">Tidak Ada Produk Ditemukan</h3>
        <p class="text-xs text-slate-500 mt-1">Pastikan Anda telah mendaftarkan produk di sistem</p>
    </div>

    <!-- Stock Monitor Table -->
    <div id="stock-table-wrapper" class="hidden relative overflow-x-auto bg-neutral-primary-soft shadow-xs rounded-base border border-default mb-8">
        <table class="w-full text-sm text-left rtl:text-right text-body">
            <thead class="text-sm text-body bg-neutral-secondary-medium border-b border-default-medium">
                <tr>
                    <th scope="col" class="px-6 py-3 font-medium">Nama Produk</th>
                    <th scope="col" class="px-6 py-3 font-medium">Kategori</th>
                    <th scope="col" class="px-6 py-3 font-medium">Counter</th>
                    <th scope="col" class="px-6 py-3 font-medium text-center">Stok</th>
                    <th scope="col" class="px-6 py-3 font-medium text-center">Indikator</th>
                    <th scope="col" class="px-6 py-3 font-medium">Status</th>
                </tr>
            </thead>
            <tbody id="stock-table-body">
                <!-- Rendered dynamically -->
            </tbody>
        </table>
    </div>
</div>

<!-- Toast Container -->
<div id="toast-container" class="fixed bottom-5 right-5 z-[60] flex flex-col gap-3 pointer-events-none"></div>

<script>
    const csrfToken = "{{ csrf_token() }}";
    let activeProducts = [];
    let countersList = [];

    document.addEventListener("DOMContentLoaded", async () => {
        try {
            await fetchCounters();
            await fetchStockData();
        } catch (e) {
            showToast("Gagal menginisialisasi Monitoring Stok.", "error");
        }
    });

    async function fetchCounters() {
        try {
            const response = await fetch("/counters", {
                headers: { "Accept": "application/json" }
            });
            if (!response.ok) throw new Error();
            countersList = await response.json();
            renderCounterFilterOptions();
        } catch (error) {
            showToast("Gagal memuat pilihan filter counter.", "error");
        }
    }

    function renderCounterFilterOptions() {
        const optionsList = document.getElementById("filter-counter-options");
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
                    <button type="button" onclick="selectCounterFilter(${counter.id}, '${escapeQuote(counter.name)}')" class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary hover:text-heading rounded text-left cursor-pointer">
                        ${escapeHtml(counter.name)}
                    </button>
                </li>
            `;
        });
        optionsList.innerHTML = html;
    }

    async function fetchStockData() {
        const skeleton = document.getElementById("loading-skeleton");
        const emptyState = document.getElementById("empty-state");
        const tableWrapper = document.getElementById("stock-table-wrapper");

        skeleton.classList.remove("hidden");
        tableWrapper.classList.add("hidden");
        emptyState.classList.add("hidden");

        try {
            const response = await fetch("/administrator/stock-monitor", {
                headers: { "Accept": "application/json" }
            });

            if (!response.ok) throw new Error();

            activeProducts = await response.json();
            
            // Calculate KPI summary
            updateStockSummary();
            
            // Render Products
            renderProducts();
        } catch (error) {
            showToast("Gagal memuat data monitoring stok.", "error");
        } finally {
            skeleton.classList.add("hidden");
        }
    }

    function updateStockSummary() {
        const totalProducts = activeProducts.length;
        const lowStockProducts = activeProducts.filter(p => p.stock > 0 && p.stock <= 5).length;
        const outOfStockProducts = activeProducts.filter(p => p.stock === 0).length;

        document.getElementById("summary-total-products").innerText = totalProducts;
        document.getElementById("summary-low-stock").innerText = lowStockProducts;
        document.getElementById("summary-out-stock").innerText = outOfStockProducts;
    }

    function renderProducts() {
        const emptyState = document.getElementById("empty-state");
        const tableWrapper = document.getElementById("stock-table-wrapper");
        const tbody = document.getElementById("stock-table-body");
        
        tbody.innerHTML = "";

        const searchQuery = document.getElementById("search-input").value.toLowerCase().trim();
        const counterFilter = document.getElementById("filter-counter-id").value;
        const stockStatusFilter = document.getElementById("filter-stock-status").value;

        // Filter products
        const filteredProducts = activeProducts.filter(product => {
            if (counterFilter && String(product.counter_id) !== counterFilter) {
                return false;
            }
            
            if (stockStatusFilter) {
                if (stockStatusFilter === 'out' && product.stock !== 0) return false;
                if (stockStatusFilter === 'low' && (product.stock === 0 || product.stock > 5)) return false;
                if (stockStatusFilter === 'good' && product.stock <= 5) return false;
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
                    <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                        <div class="flex flex-col items-center justify-center gap-2">
                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <span class="text-sm font-semibold">Tidak ada produk yang cocok dengan kriteria pencarian.</span>
                        </div>
                    </td>
                </tr>
            `;
            return;
        }

        filteredProducts.forEach(product => {
            const row = document.createElement("tr");
            row.className = "bg-neutral-primary-soft border-b border-default hover:bg-neutral-secondary-medium transition-colors duration-150";
            
            // Stock level classes & visuals
            let stockBadgeClass = "";
            let stockBadgeText = "";

            if (product.stock === 0) {
                stockBadgeClass = "bg-rose-50 text-rose-700 font-bold border border-rose-100";
                stockBadgeText = "Habis";
            } else if (product.stock <= 5) {
                stockBadgeClass = "bg-amber-50 text-amber-700 font-semibold border border-amber-100";
                stockBadgeText = "Menipis";
            } else {
                stockBadgeClass = "bg-emerald-50 text-emerald-700 font-semibold border border-emerald-100";
                stockBadgeText = "Cukup";
            }

            const statusBadgeClass = product.status 
                ? "bg-slate-100 text-slate-700 font-semibold" 
                : "bg-slate-50 text-slate-400 font-medium";
            const statusText = product.status ? "Aktif" : "Nonaktif";

            const catName = product.category ? product.category.name : 'Tanpa Kategori';
            const unitName = product.unit ? product.unit.name : '-';
            const counterName = product.counter ? product.counter.name : '-';

            row.innerHTML = `
                <th scope="row" class="px-6 py-4 font-semibold text-heading whitespace-nowrap text-left">${escapeHtml(product.name)}</th>
                <td class="px-6 py-4 text-xs font-semibold text-body">${escapeHtml(catName)}</td>
                <td class="px-6 py-4 text-xs font-semibold text-body">${escapeHtml(counterName)}</td>
                <td class="px-6 py-4 text-center">
                    <span class="px-2.5 py-1 text-sm font-bold border rounded-md shadow-xs ${product.stock === 0 ? 'bg-rose-50 border-rose-200 text-rose-700' : (product.stock <= 5 ? 'bg-amber-50 border-amber-200 text-amber-700' : 'bg-slate-50 border-slate-200 text-slate-700')}">
                        ${product.stock} <span class="text-[10px] font-normal text-slate-400 ml-0.5">${escapeHtml(product.unit ? product.unit.name : '')}</span>
                    </span>
                </td>
                <td class="px-6 py-4 text-center">
                    <span class="px-2 py-0.5 text-[10px] rounded-full shrink-0 ${stockBadgeClass}">
                        ${stockBadgeText}
                    </span>
                </td>
                <td class="px-6 py-4 text-xs">
                    <span class="px-2 py-0.5 rounded-full ${statusBadgeClass}">
                        ${statusText}
                    </span>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    function handleSearchFilterChange() {
        renderProducts();
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

    function selectStockFilter(status, name) {
        document.getElementById("filter-stock-status").value = status;
        document.getElementById("selected-stock-label").innerText = name;
        
        const button = document.getElementById("dropdownStockButton");
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

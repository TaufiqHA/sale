@extends('layouts.administrator')

@section('title', 'Manajemen Produksi')

@section('content')
<div class="relative min-h-[calc(100vh-8rem)]">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <!-- Search Form -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 flex-1 max-w-md">
            <form id="search-form" onsubmit="event.preventDefault(); handleSearchChange();" class="relative flex-1">
                <label for="search-input" class="block mb-2.5 text-sm font-medium sr-only">Cari</label>
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/></svg>
                    </div>
                    <input type="search" id="search-input" oninput="handleSearchChange()" class="block w-full p-3 ps-9 bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-lg focus:ring-sky-500 focus:border-sky-500 shadow-sm placeholder:text-slate-400" placeholder="Cari Produksi (Produk, Counter...)" />
                </div>
            </form>
        </div>

        <button onclick="openAddModal()" class="inline-flex items-center justify-center text-white bg-brand box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none cursor-pointer shrink-0 gap-1.5">
            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7 7V5"/>
            </svg>
            Tambah Produksi
        </button>
    </div>

    <!-- Loading State -->
    <div id="loading-skeleton" class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden mb-8 animate-pulse">
        <div class="h-12 bg-slate-50 border-b border-slate-100"></div>
        <div class="divide-y divide-slate-100">
            @for ($i = 0; $i < 3; $i++)
            <div class="px-6 py-5 flex items-center justify-between gap-6">
                <div class="h-4 bg-slate-100 rounded-md w-12 shrink-0"></div>
                <div class="h-4 bg-slate-100 rounded-md w-32 shrink-0"></div>
                <div class="h-4 bg-slate-100 rounded-md w-28 shrink-0"></div>
                <div class="h-4 bg-slate-100 rounded-md w-24 shrink-0"></div>
                <div class="h-4 bg-slate-100 rounded-md w-48 shrink-0 flex-1"></div>
                <div class="h-8 bg-slate-100 rounded-lg w-20 shrink-0"></div>
            </div>
            @endfor
        </div>
    </div>

    <!-- Empty State -->
    <div id="empty-state" class="hidden flex-col items-center justify-center py-16 px-4 bg-white border border-slate-100 rounded-2xl shadow-sm mb-8 text-center">
        <div class="flex items-center justify-center p-4 bg-slate-50 text-slate-400 rounded-2xl mb-4">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5h.007m-.007 3h.007m-.007 3h.007m-2.25 9H22.5M12 15.75h.007m-.007 3h.007m-.007 3h.007m0-6.75H12M3.75 4.5V18M20.25 4.5V18M3.75 4.5h16.5M12 3v12.75"></path>
            </svg>
        </div>
        <h3 class="text-base font-bold text-slate-800">Tidak Ada Produksi Dibuat</h3>
        <button onclick="openAddModal()" class="mt-4 inline-flex items-center justify-center text-white bg-brand box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none cursor-pointer gap-1.5 transition-colors duration-150">
            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7 7V5"/>
            </svg>
            Tambah Produksi
        </button>
    </div>

    <!-- Table -->
    <div id="table-wrapper" class="hidden relative overflow-x-auto bg-neutral-primary-soft shadow-xs rounded-base border border-default mb-8">
        <table class="w-full text-sm text-left rtl:text-right text-body">
            <thead class="text-sm text-body bg-neutral-secondary-medium border-b border-default-medium">
                <tr>
                    <th scope="col" class="px-6 py-4 font-semibold">ID</th>
                    <th scope="col" class="px-6 py-4 font-semibold">Tanggal</th>
                    <th scope="col" class="px-6 py-4 font-semibold">Counter</th>
                    <th scope="col" class="px-6 py-4 font-semibold">Produk</th>
                    <th scope="col" class="px-6 py-4 font-semibold text-right">Total Biaya</th>
                    <th scope="col" class="px-6 py-4 font-semibold text-center">Qty Hasil</th>
                    <th scope="col" class="px-6 py-4 font-semibold text-right">HPP</th>
                    <th scope="col" class="px-6 py-4 font-semibold text-right">Harga Jual</th>
                    <th scope="col" class="px-6 py-4 font-semibold text-right">Perkiraan Profit</th>
                    <th scope="col" class="px-6 py-4 font-semibold text-center">Status</th>
                    <th scope="col" class="px-6 py-4 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <!-- Rendered dynamically -->
            </tbody>
        </table>
    </div>
</div>

<!-- Modal (Create / Edit) -->
<div id="production-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden" role="dialog" aria-modal="true">
    <!-- Backdrop with blur -->
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300 opacity-0" id="modal-backdrop" onclick="closeModal()"></div>

    <!-- Modal content wrapper -->
    <div class="relative w-full max-w-5xl max-h-[95vh] flex flex-col transform scale-95 opacity-0 transition-all duration-300 bg-neutral-primary-soft border border-default rounded-base shadow-sm p-4 md:p-6" id="modal-panel">
        
        <!-- Modal header -->
        <div class="flex items-center justify-between border-b border-default pb-4 md:pb-5 shrink-0">
            <div>
                <h3 id="modal-title" class="text-lg font-bold text-heading">
                    Tambah Produksi Baru
                </h3>
                <p class="text-xs text-body mt-0.5">Buat data transaksi produksi baru</p>
            </div>
            <button type="button" onclick="closeModal()" class="text-body bg-transparent hover:bg-neutral-tertiary hover:text-heading rounded-base text-sm w-9 h-9 ms-auto inline-flex justify-center items-center cursor-pointer">
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/></svg>
                <span class="sr-only">Tutup modal</span>
            </button>
        </div>

        <!-- Form content scrollable -->
        <form id="production-form" onsubmit="handleFormSubmit(event)" class="overflow-y-auto flex-1 pr-1">
            <input type="hidden" id="production-id" name="id">

            <!-- Header fields grid inside the form -->
            <div class="grid gap-4 grid-cols-4 py-4 md:py-6 border-b border-default mb-6 shrink-0">
                <!-- Pilih Counter -->
                <div class="col-span-4 sm:col-span-2 md:col-span-1">
                    <label for="input-counter-id" class="block mb-2 text-sm font-medium text-heading">Pilih Counter *</label>
                    <select id="input-counter-id" onchange="onCounterChange()" class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs cursor-pointer" required>
                        <option value="">Pilih Counter...</option>
                        @foreach($counters as $counter)
                            @if($counter->status)
                                <option value="{{ $counter->id }}">{{ $counter->name }}</option>
                            @endif
                        @endforeach
                    </select>
                    <p id="error-counter_id" class="mt-2 text-xs font-medium text-rose-500 hidden"></p>
                </div>

                <!-- PRODUK BARANG -->
                <div class="col-span-4 sm:col-span-2 md:col-span-1">
                    <label for="input-product-id" class="block mb-2 text-sm font-medium text-heading">PRODUK BARANG *</label>
                    <select id="input-product-id" class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs cursor-pointer" disabled required>
                        <option value="">PRODUK BARANG</option>
                    </select>
                    <p id="error-product_id" class="mt-2 text-xs font-medium text-rose-500 hidden"></p>
                </div>

                <!-- Tanggal -->
                <div class="col-span-4 sm:col-span-2 md:col-span-1">
                    <label for="input-production-date" class="block mb-2 text-sm font-medium text-heading">Tanggal *</label>
                    <input type="date" id="input-production-date" class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs cursor-pointer" required>
                    <p id="error-production_date" class="mt-2 text-xs font-medium text-rose-500 hidden"></p>
                </div>

                <!-- Status -->
                <div class="col-span-4 sm:col-span-2 md:col-span-1">
                    <label for="input-status" class="block mb-2 text-sm font-medium text-heading">Status *</label>
                    <select id="input-status" class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs cursor-pointer" required>
                        <option value="draft">Draft</option>
                        <option value="completed">Selesai</option>
                        <option value="cancelled">Dibatalkan</option>
                    </select>
                    <p id="error-status" class="mt-2 text-xs font-medium text-rose-500 hidden"></p>
                </div>
            </div>

            <!-- Body grid (Table & Calculations) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
                
                <!-- Left Side: Table of Ingredients/Items (Spans 2 columns) -->
                <div class="lg:col-span-2 overflow-x-auto">
                    <table class="w-full min-w-[600px] lg:min-w-full border-collapse border border-slate-400 text-xs">
                        <thead>
                            <tr class="bg-slate-200 text-slate-900 border border-slate-400">
                                <th class="border border-slate-400 px-2 py-2 w-10 text-center font-bold">No</th>
                                <th class="border border-slate-400 px-2 py-2 text-left font-bold">Keterangan</th>
                                <th class="border border-slate-400 px-2 py-2 text-right w-32 font-bold">Harga satuan</th>
                                <th class="border border-slate-400 px-2 py-2 text-right w-24 font-bold">Qty</th>
                                <th class="border border-slate-400 px-2 py-2 text-right w-36 font-bold">Total</th>
                            </tr>
                        </thead>
                        <tbody id="production-items-table-body" class="bg-[#e8f4f8]">
                            <!-- JavaScript will render 10 rows dynamically -->
                        </tbody>
                    </table>
                </div>

                <!-- Right Side: Calculations Panel -->
                <div class="space-y-4">
                    <!-- Jumlah Barang Jadi -->
                    <div class="flex border border-slate-400 rounded overflow-hidden shadow-sm">
                        <div class="bg-slate-200 text-slate-900 font-bold px-3 py-2.5 text-xs flex items-center justify-between w-[55%] border-r border-slate-400">
                            <span>JUMLAH BARANG JADI</span>
                        </div>
                        <input type="text" id="input-total-result" oninput="formatAndCalculate(this)" class="w-[45%] bg-white border-none text-slate-800 font-bold text-right px-3 py-2 text-sm focus:outline-none focus:ring-0" value="0">
                    </div>
                    
                    <!-- Sub Total -->
                    <div class="flex border border-slate-400 rounded overflow-hidden shadow-sm">
                        <div class="bg-slate-200 text-slate-900 font-bold px-3 py-2.5 text-xs flex items-center justify-between w-[55%] border-r border-slate-400">
                            <span>Sub Total</span>
                        </div>
                        <div class="w-[45%] bg-white text-slate-800 font-bold text-right px-3 py-2 text-sm flex items-center justify-end" id="label-subtotal">
                            0
                        </div>
                    </div>

                    <!-- perkiraan Hpp -->
                    <div class="flex border border-slate-400 rounded overflow-hidden shadow-sm">
                        <div class="bg-slate-200 text-slate-900 font-bold px-3 py-2.5 text-xs flex items-center justify-between w-[55%] border-r border-slate-400">
                            <span>perkiraan Hpp</span>
                        </div>
                        <div class="w-[45%] bg-white text-slate-800 font-bold text-right px-3 py-2 text-sm flex items-center justify-end" id="label-hpp">
                            0
                        </div>
                    </div>

                    <!-- Harga Jual -->
                    <div class="flex border border-slate-400 rounded overflow-hidden shadow-sm">
                        <div class="bg-slate-200 text-slate-900 font-bold px-3 py-2.5 text-xs flex items-center justify-between w-[55%] border-r border-slate-400">
                            <span>Harga Jual</span>
                        </div>
                        <input type="text" id="input-selling-price" oninput="formatAndCalculate(this)" class="w-[45%] bg-white border-none text-slate-800 font-bold text-right px-3 py-2 text-sm focus:outline-none focus:ring-0" value="0">
                    </div>

                    <!-- Perkiraan Profit satuan -->
                    <div class="flex border border-slate-400 rounded overflow-hidden shadow-sm">
                        <div class="bg-slate-200 text-slate-900 font-bold px-3 py-2.5 text-xs flex items-center justify-between w-[55%] border-r border-slate-400">
                            <span>Perkiraan Profit satuan</span>
                        </div>
                        <div class="w-[45%] bg-white text-red-600 font-bold text-right px-3 py-2 text-sm flex items-center justify-end" id="label-profit">
                            0
                        </div>
                    </div>
                </div>

            </div>

            <!-- Notes/Remarks -->
            <div class="mt-6">
                <label for="input-notes" class="block mb-2 text-xs font-semibold text-slate-700">Catatan / Keterangan Tambahan</label>
                <textarea id="input-notes" rows="2" class="block w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-lg focus:ring-sky-500 focus:border-sky-500 p-2.5" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                <p id="error-notes" class="mt-1 text-xs text-rose-500 hidden"></p>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center space-x-4 border-t border-default pt-4 md:pt-6 mt-6">
                <button type="submit" id="btn-save" class="inline-flex items-center text-white bg-brand hover:bg-brand-strong box-border border border-transparent focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none cursor-pointer">
                    <svg class="w-4 h-4 me-1.5 -ms-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7 7V5"/></svg>
                    Simpan Produksi
                </button>
                <button type="button" onclick="closeModal()" class="text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none cursor-pointer">Batal</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Delete Confirmation -->
<div id="delete-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300 opacity-0" id="delete-backdrop" onclick="closeDeleteModal()"></div>
    <div class="relative w-full max-w-md bg-white border border-slate-100 rounded-2xl shadow-xl p-6 flex flex-col transform scale-95 opacity-0 transition-all duration-300" id="delete-panel">
        <h3 class="text-lg font-bold text-slate-800 mb-2">Hapus Produksi</h3>
        <p class="text-sm text-slate-600 mb-6">Apakah Anda yakin ingin menghapus produksi ID <span id="delete-production-id" class="font-bold text-slate-850"></span>? Tindakan ini tidak dapat dibatalkan.</p>
        <div class="flex justify-end gap-3">
            <button onclick="closeDeleteModal()" class="px-4 py-2.5 text-sm font-semibold text-slate-500 hover:text-slate-800 hover:bg-slate-50 rounded-xl transition duration-150 cursor-pointer">
                Batal
            </button>
            <button onclick="handleDeleteConfirm()" id="btn-delete" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold rounded-xl transition duration-150 cursor-pointer">
                Hapus
            </button>
        </div>
    </div>
</div>

<!-- Toast Notification System Container -->
<div id="toast-container" class="fixed top-6 right-6 z-50 flex flex-col gap-3 pointer-events-none"></div>

<!-- Javascript Controller Logic -->
<script>
    const csrfToken = "{{ csrf_token() }}";
    let activeProductions = [];
    let productionToDelete = null;
    
    // Seed arrays from backend variables
    const activeProducts = @json($products);
    const activeCounters = @json($counters);
    
    // State array for table rows
    let productionItems = [];
    let selectedCounterId = null;

    function onCounterChange() {
        const counterSelect = document.getElementById("input-counter-id");
        selectedCounterId = counterSelect.value;

        const productSelect = document.getElementById("input-product-id");
        productSelect.disabled = !selectedCounterId;

        renderProductOptions();
    }

    function renderProductOptions() {
        const productSelect = document.getElementById("input-product-id");
        const currentSelectedVal = productSelect.value;

        productSelect.innerHTML = '<option value="">PRODUK BARANG</option>';

        const filtered = selectedCounterId ? activeProducts.filter(p => String(p.counter_id) === String(selectedCounterId)) : [];

        filtered.forEach(p => {
            const opt = document.createElement("option");
            opt.value = p.id;
            opt.innerText = p.name;
            productSelect.appendChild(opt);
        });

        if (filtered.some(p => String(p.id) === String(currentSelectedVal))) {
            productSelect.value = currentSelectedVal;
        } else {
            productSelect.value = "";
        }
    }

    document.addEventListener("DOMContentLoaded", () => {
        fetchProductions();
        resetItemsArray();
    });

    function resetItemsArray() {
        productionItems = Array.from({ length: 10 }, () => ({
            description: "",
            unit_price: 0,
            qty: 0,
            total: 0
        }));
    }

    async function fetchProductions() {
        const skeleton = document.getElementById("loading-skeleton");
        const emptyState = document.getElementById("empty-state");
        const tableWrapper = document.getElementById("table-wrapper");

        skeleton.classList.remove("hidden");
        tableWrapper.classList.add("hidden");
        emptyState.classList.add("hidden");

        try {
            const response = await fetch("/productions", {
                headers: {
                    "Accept": "application/json",
                }
            });

            if (!response.ok) throw new Error("Gagal mengambil data produksi.");

            activeProductions = await response.json();
            renderProductions();
        } catch (error) {
            showToast("Gagal memuat catatan produksi. Silakan coba lagi.", "error");
        } finally {
            skeleton.classList.add("hidden");
        }
    }

    function formatNumber(value) {
        if (value === undefined || value === null) return '0';
        return new Intl.NumberFormat('id-ID').format(value);
    }

    function parseFormattedNumber(value) {
        if (!value) return 0;
        return parseFloat(value.toString().replace(/\./g, '').replace(/,/g, '.')) || 0;
    }

    function formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(amount);
    }

    function handleSearchChange() {
        renderProductions();
    }

    function renderProductions() {
        const emptyState = document.getElementById("empty-state");
        const tableWrapper = document.getElementById("table-wrapper");
        const tbody = document.getElementById("table-body");

        tbody.innerHTML = "";

        const searchInput = document.getElementById("search-input");
        const searchQuery = searchInput ? searchInput.value.toLowerCase().trim() : "";

        // Filter list
        const filtered = activeProductions.filter(prod => {
            if (searchQuery) {
                const counterMatch = (prod.counter && prod.counter.name) ? prod.counter.name.toLowerCase().includes(searchQuery) : false;
                const productMatch = (prod.product && prod.product.name) ? prod.product.name.toLowerCase().includes(searchQuery) : false;
                const notesMatch = prod.notes ? prod.notes.toLowerCase().includes(searchQuery) : false;
                const statusMatch = prod.status ? prod.status.toLowerCase().includes(searchQuery) : false;
                if (!counterMatch && !productMatch && !notesMatch && !statusMatch) {
                    return false;
                }
            }
            return true;
        });

        if (activeProductions.length === 0) {
            emptyState.classList.remove("hidden");
            tableWrapper.classList.add("hidden");
            return;
        }

        emptyState.classList.add("hidden");
        tableWrapper.classList.remove("hidden");

        if (filtered.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="11" class="px-6 py-12 text-center text-slate-500 opacity-60">
                        <div class="flex flex-col items-center justify-center gap-2">
                            <svg class="w-8 h-8 opacity-45 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <span class="text-sm font-semibold">Tidak ada data yang cocok dengan kriteria pencarian Anda.</span>
                        </div>
                    </td>
                </tr>
            `;
            return;
        }

        filtered.forEach(prod => {
            const row = document.createElement("tr");
            row.className = "bg-neutral-primary-soft border-b border-default hover:bg-neutral-secondary-medium transition-colors duration-150";

            const counterName = prod.counter ? escapeHtml(prod.counter.name) : '<span class="text-body opacity-50 font-normal">Tanpa Counter</span>';
            const productName = prod.product ? escapeHtml(prod.product.name) : '<span class="text-body opacity-50 font-normal">Tanpa Produk</span>';
            
            let formattedDate = '-';
            if (prod.production_date) {
                const d = new Date(prod.production_date);
                formattedDate = d.toLocaleDateString('id-ID', { dateStyle: 'medium' });
            }

            // Status Badge
            let statusBadge = `<span class="px-2.5 py-1 text-xs font-semibold text-slate-700 bg-slate-100 rounded-full">Draft</span>`;
            if (prod.status === 'completed') {
                statusBadge = `<span class="px-2.5 py-1 text-xs font-semibold text-emerald-700 bg-emerald-50 rounded-full">Selesai</span>`;
            } else if (prod.status === 'cancelled') {
                statusBadge = `<span class="px-2.5 py-1 text-xs font-semibold text-rose-700 bg-rose-50 rounded-full">Dibatalkan</span>`;
            }

            row.innerHTML = `
                <td class="px-6 py-4 font-semibold text-body">#${prod.id}</td>
                <td class="px-6 py-4 text-xs">${formattedDate}</td>
                <td class="px-6 py-4 font-medium text-heading">${counterName}</td>
                <td class="px-6 py-4 font-medium text-heading">${productName}</td>
                <td class="px-6 py-4 text-right font-bold text-slate-800">${formatCurrency(prod.total_cost)}</td>
                <td class="px-6 py-4 text-center font-semibold">${formatNumber(prod.total_result)}</td>
                <td class="px-6 py-4 text-right">${formatCurrency(prod.hpp)}</td>
                <td class="px-6 py-4 text-right">${formatCurrency(prod.selling_price)}</td>
                <td class="px-6 py-4 text-right font-bold ${prod.estimated_profit >= 0 ? 'text-emerald-600' : 'text-rose-600'}">${formatCurrency(prod.estimated_profit)}</td>
                <td class="px-6 py-4 text-center">${statusBadge}</td>
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end gap-3">
                        <button onclick="openEditModal(${prod.id})" class="font-medium text-fg-brand hover:underline cursor-pointer" title="Ubah">Ubah</button>
                        <button onclick="openDeleteModal(${prod.id})" class="font-medium text-fg-danger hover:underline cursor-pointer" title="Hapus">Hapus</button>
                    </div>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    // Dynamic Items Table Rendering
    function renderItemsTable() {
        const tbody = document.getElementById("production-items-table-body");
        tbody.innerHTML = "";

        productionItems.forEach((item, index) => {
            const row = document.createElement("tr");
            row.className = "border border-slate-400";
            
            row.innerHTML = `
                <td class="border border-slate-400 px-2 py-1 text-center font-semibold text-slate-850 bg-slate-100/50 w-10">${index + 1}</td>
                <td class="border border-slate-400 px-1 py-0.5">
                    <input type="text" oninput="handleItemPropInput(${index}, 'description', this.value)" class="w-full bg-transparent border-none text-slate-900 text-xs p-1 focus:ring-0 focus:outline-none font-medium placeholder-slate-400" placeholder="Keterangan..." value="${escapeHtml(item.description)}">
                </td>
                <td class="border border-slate-400 px-1 py-0.5 w-32">
                    <input type="text" oninput="handleItemPropInput(${index}, 'unit_price', this.value, true)" class="w-full bg-transparent border-none text-slate-900 text-xs p-1 text-right focus:ring-0 focus:outline-none font-bold" value="${formatNumber(item.unit_price)}">
                </td>
                <td class="border border-slate-400 px-1 py-0.5 w-24">
                    <input type="text" oninput="handleItemPropInput(${index}, 'qty', this.value, true)" class="w-full bg-transparent border-none text-slate-900 text-xs p-1 text-right focus:ring-0 focus:outline-none font-bold" value="${formatNumber(item.qty)}">
                </td>
                <td class="border border-slate-400 px-2 py-1 text-right w-36 font-bold text-slate-800 bg-slate-100/30">
                    <span id="item-total-${index}">${formatNumber(item.total)}</span>
                </td>
            `;
            tbody.appendChild(row);
        });

        // Add row at the bottom containing the add row button
        const addRow = document.createElement("tr");
        addRow.className = "border border-slate-400 bg-slate-100/30";
        addRow.innerHTML = `
            <td colspan="5" class="px-3 py-2 text-center">
                <button type="button" onclick="addNewItemRow()" class="inline-flex items-center text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-300 rounded px-3 py-1.5 text-xs font-semibold focus:outline-none cursor-pointer">
                    <svg class="w-4 h-4 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7 7V5"/>
                    </svg>
                    Tambah Baris
                </button>
            </td>
        `;
        tbody.appendChild(addRow);
    }

    function handleItemPropInput(index, field, value, isNumeric = false) {
        if (isNumeric) {
            const cleanInput = value.replace(/\D/g, '');
            const parsedVal = parseFormattedNumber(cleanInput);
            productionItems[index][field] = parsedVal;
            
            // Format input value display in DOM immediately
            const activeInput = document.activeElement;
            if (activeInput && activeInput.tagName === 'INPUT') {
                activeInput.value = formatNumber(parsedVal);
            }
        } else {
            productionItems[index][field] = value;
        }

        // Recalculate row total
        productionItems[index].total = productionItems[index].unit_price * productionItems[index].qty;
        
        // Update total element text
        const totalEl = document.getElementById(`item-total-${index}`);
        if (totalEl) {
            totalEl.innerText = formatNumber(productionItems[index].total);
        }

        calculateSummaries();
    }

    function addNewItemRow() {
        productionItems.push({
            description: "",
            unit_price: 0,
            qty: 0,
            total: 0
        });
        renderItemsTable();
    }

    function formatAndCalculate(input) {
        const parsedVal = parseFormattedNumber(input.value.replace(/\D/g, ''));
        input.value = formatNumber(parsedVal);
        calculateSummaries();
    }

    function calculateSummaries() {
        let subtotal = 0;
        productionItems.forEach(item => {
            subtotal += item.total;
        });

        const totalResultInput = document.getElementById("input-total-result");
        const totalResult = parseFormattedNumber(totalResultInput.value) || 1;

        const hpp = subtotal / totalResult;

        const sellingPriceInput = document.getElementById("input-selling-price");
        const sellingPrice = parseFormattedNumber(sellingPriceInput.value);

        const profit = sellingPrice - hpp;

        document.getElementById("label-subtotal").innerText = formatNumber(Math.round(subtotal));
        document.getElementById("label-hpp").innerText = formatNumber(Math.round(hpp));
        document.getElementById("label-profit").innerText = formatNumber(Math.round(profit));

        const profitLabel = document.getElementById("label-profit");
        if (profit < 0) {
            profitLabel.className = "w-[45%] bg-white text-rose-600 font-bold text-right px-3 py-2 text-sm flex items-center justify-end";
        } else {
            profitLabel.className = "w-[45%] bg-white text-orange-500 font-bold text-right px-3 py-2 text-sm flex items-center justify-end";
        }
    }

    // Modal Control Operations
    function openAddModal() {
        document.getElementById("production-id").value = "";
        document.getElementById("input-counter-id").value = "";
        
        onCounterChange();
        
        document.getElementById("input-product-id").value = "";
        
        const today = new Date().toISOString().slice(0, 10);
        document.getElementById("input-production-date").value = today;
        
        document.getElementById("input-status").value = "draft";
        document.getElementById("input-total-result").value = "0";
        document.getElementById("input-selling-price").value = "0";
        document.getElementById("input-notes").value = "";
        
        resetItemsArray();
        renderItemsTable();
        calculateSummaries();
        clearValidationErrors();

        showModal();
    }

    async function openEditModal(id) {
        clearValidationErrors();
        
        try {
            const response = await fetch(`/productions/${id}`, {
                headers: {
                    "Accept": "application/json",
                }
            });

            if (!response.ok) throw new Error("Gagal mengambil detail catatan produksi.");

            const prod = await response.json();
            
            document.getElementById("production-id").value = prod.id;
            document.getElementById("input-counter-id").value = prod.counter_id;
            
            onCounterChange();
            
            document.getElementById("input-product-id").value = prod.product_id;
            
            if (prod.production_date) {
                document.getElementById("input-production-date").value = prod.production_date;
            }
            
            document.getElementById("input-status").value = prod.status;
            document.getElementById("input-total-result").value = formatNumber(prod.total_result);
            document.getElementById("input-selling-price").value = formatNumber(Math.round(prod.selling_price));
            document.getElementById("input-notes").value = prod.notes || "";
            
            // Map items
            productionItems = prod.production_items ? prod.production_items.map(item => ({
                description: item.description,
                unit_price: parseFloat(item.unit_price),
                qty: parseFloat(item.qty),
                total: parseFloat(item.total)
            })) : [];

            // Add pad items if less than 10 rows
            while (productionItems.length < 10) {
                productionItems.push({ description: "", unit_price: 0, qty: 0, total: 0 });
            }

            renderItemsTable();
            calculateSummaries();
            showModal();
        } catch (error) {
            showToast(error.message, "error");
        }
    }

    function showModal() {
        const modal = document.getElementById("production-modal");
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
        const modal = document.getElementById("production-modal");
        const backdrop = document.getElementById("modal-backdrop");
        const panel = document.getElementById("modal-panel");

        backdrop.classList.replace("opacity-100", "opacity-0");
        panel.classList.replace("scale-100", "scale-95");
        panel.classList.replace("opacity-100", "opacity-0");

        setTimeout(() => {
            modal.classList.add("hidden");
        }, 300);
    }

    async function handleFormSubmit(event) {
        event.preventDefault();
        clearValidationErrors();

        const id = document.getElementById("production-id").value;
        const counter_id = document.getElementById("input-counter-id").value;
        const product_id = document.getElementById("input-product-id").value;
        const production_date = document.getElementById("input-production-date").value;
        const status = document.getElementById("input-status").value;
        const notes = document.getElementById("input-notes").value;

        const total_result = parseFormattedNumber(document.getElementById("input-total-result").value);
        const selling_price = parseFormattedNumber(document.getElementById("input-selling-price").value);
        
        // Sum total cost
        let total_cost = 0;
        const validItems = productionItems.filter(item => {
            if (item.description.trim() !== "" && item.qty > 0) {
                total_cost += item.total;
                return true;
            }
            return false;
        });

        if (validItems.length === 0) {
            showToast("Silakan tambahkan minimal 1 item produksi (dengan keterangan dan qty > 0)!", "error");
            return;
        }

        const hpp = total_cost / (total_result || 1);
        const estimated_profit = selling_price - hpp; // per unit profit

        const data = {
            counter_id,
            product_id,
            production_date,
            status,
            notes,
            total_cost,
            total_result,
            hpp,
            selling_price,
            estimated_profit,
            items: validItems
        };

        const isEdit = !!id;
        const url = isEdit ? `/productions/${id}` : "/productions";
        const method = isEdit ? "PUT" : "POST";

        const btnSave = document.getElementById("btn-save");
        const originalText = btnSave.innerHTML;
        btnSave.disabled = true;
        btnSave.innerHTML = `Menyimpan...`;

        try {
            const response = await fetch(url, {
                method: method,
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (response.status === 422) {
                showValidationErrors(result.errors);
                return;
            }

            if (!response.ok) throw new Error("Gagal menyimpan detail produksi.");

            showToast(`Produksi berhasil ${isEdit ? 'diperbarui' : 'dibuat'}!`, "success");
            closeModal();
            fetchProductions();
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
            const inputEl = document.getElementById(`input-${key.replace('_', '-')}`);
            if (errorEl) {
                errorEl.innerText = errors[key].join(", ");
                errorEl.classList.remove("hidden");
            }
            if (inputEl) {
                inputEl.classList.add("border-rose-500", "focus:border-rose-500", "focus:ring-rose-500/10");
            }
        });
        showToast("Ada kesalahan validasi data. Silakan cek kembali inputan Anda.", "error");
    }

    function clearValidationErrors() {
        const errorElements = document.querySelectorAll("[id^='error-']");
        errorElements.forEach(el => {
            el.innerText = "";
            el.classList.add("hidden");
        });

        const inputs = document.querySelectorAll("#production-modal input, #production-modal select");
        inputs.forEach(input => {
            input.classList.remove("border-rose-500", "focus:border-rose-500", "focus:ring-rose-500/10");
        });
    }

    // Delete Operations
    function openDeleteModal(id) {
        productionToDelete = id;
        document.getElementById("delete-production-id").innerText = `#${id}`;

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
            productionToDelete = null;
        }, 300);
    }

    async function handleDeleteConfirm() {
        if (!productionToDelete) return;

        const btnDelete = document.getElementById("btn-delete");
        const originalText = btnDelete.innerText;
        btnDelete.disabled = true;
        btnDelete.innerText = "Menghapus...";

        try {
            const response = await fetch(`/productions/${productionToDelete}`, {
                method: "DELETE",
                headers: {
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                }
            });

            if (!response.ok) throw new Error("Gagal menghapus catatan produksi.");

            showToast("Catatan produksi berhasil dihapus.", "success");
            closeDeleteModal();
            fetchProductions();
        } catch (error) {
            showToast(error.message, "error");
        } finally {
            btnDelete.disabled = false;
            btnDelete.innerText = originalText;
        }
    }

    // Toast Notification System
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
            <button onclick="this.parentElement.remove()" class="text-slate-400 hover:text-slate-600 ml-auto p-1 rounded hover:bg-slate-50 cursor-pointer">
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

    // Escape Helpers
    function escapeHtml(str) {
        if (!str) return '';
        return String(str).replace(/&/g, "&amp;")
                          .replace(/</g, "&lt;")
                          .replace(/>/g, "&gt;")
                          .replace(/"/g, "&quot;")
                          .replace(/'/g, "&#039;");
    }

    // Escape listener
    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") {
            closeModal();
            closeDeleteModal();
        }
    });
</script>
@endsection

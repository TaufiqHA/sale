@extends('layouts.administrator')

@section('title', 'Invoice & Resi')

@section('content')
<div class="relative min-h-[calc(100vh-8rem)]">
    <!-- ==========================================================================
         HEADER & MAIN TABS
         ========================================================================== -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-heading">Invoice & Resi Pengiriman</h1>
            <p class="text-sm text-body mt-1">Kelola dan cetak invoice (penjualan umum) serta resi pengiriman (umum & marketplace).</p>
        </div>

        <!-- Tab Selector: Invoice vs Resi -->
        <div class="flex items-center gap-2 p-1.5 bg-slate-200/80 rounded-2xl shrink-0 self-start md:self-auto">
            <button id="main-tab-invoice" onclick="switchMainTab('invoice')" class="flex items-center gap-2.5 px-5 py-2.5 text-sm font-bold rounded-xl transition-all cursor-pointer bg-brand text-white shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Invoice Penjualan
                <span id="badge-invoice-total" class="px-2 py-0.5 text-xs rounded-full bg-white/20 text-white font-extrabold">0</span>
            </button>
            <button id="main-tab-resi" onclick="switchMainTab('resi')" class="flex items-center gap-2.5 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-900 rounded-xl transition-all cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 2v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Resi Pengiriman
                <span id="badge-resi-total" class="px-2 py-0.5 text-xs rounded-full bg-slate-300/80 text-slate-800 font-extrabold">0</span>
            </button>
        </div>
    </div>

    <!-- ==========================================================================
         CONTROLS BAR (STATUS FILTERS & SEARCH)
         ========================================================================== -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-sm mb-4 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
        <!-- Status Filters -->
        <div class="flex items-center gap-1.5 p-1 bg-slate-100/90 rounded-xl shrink-0 overflow-x-auto">
            <button id="status-pill-unprinted" onclick="switchStatusFilter('unprinted')" class="flex items-center gap-2 px-3.5 py-1.5 text-xs font-bold rounded-lg transition-all cursor-pointer bg-amber-500 text-white shadow-xs whitespace-nowrap">
                <span class="w-2 h-2 rounded-full bg-amber-200 animate-pulse"></span>
                Belum Dicetak
                <span id="badge-status-unprinted" class="px-1.5 py-0.5 text-[11px] rounded-full bg-white/20 text-white font-extrabold">0</span>
            </button>
            <button id="status-pill-printed" onclick="switchStatusFilter('printed')" class="flex items-center gap-2 px-3.5 py-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 rounded-lg transition-all cursor-pointer whitespace-nowrap">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                Sudah Dicetak
                <span id="badge-status-printed" class="px-1.5 py-0.5 text-[11px] rounded-full bg-slate-200 text-slate-700 font-bold">0</span>
            </button>
            <button id="status-pill-all" onclick="switchStatusFilter('all')" class="flex items-center gap-2 px-3.5 py-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 rounded-lg transition-all cursor-pointer whitespace-nowrap">
                Semua
                <span id="badge-status-all" class="px-1.5 py-0.5 text-[11px] rounded-full bg-slate-200 text-slate-700 font-bold">0</span>
            </button>
        </div>

        <!-- Action & Search -->
        <div class="flex flex-col sm:flex-row items-center gap-3 flex-1 justify-end">
            <!-- Bulk Print Button -->
            <button id="btn-bulk-print" onclick="previewBulkDocuments()" class="hidden inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-brand hover:bg-brand-hover rounded-xl shadow-xs transition-all cursor-pointer whitespace-nowrap w-full sm:w-auto">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                <span>Cetak Massal</span>
                <span id="bulk-selected-badge" class="px-2 py-0.5 text-xs rounded-full bg-white/20 text-white font-extrabold">0</span>
            </button>

            <!-- Filter Tipe Resi (Resi tab specific) -->
            <div id="resi-type-filter-wrapper" class="hidden w-full sm:w-48">
                <select id="resi-type-filter" onchange="handleFilterChange()" class="block w-full p-2.5 bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-brand focus:border-brand">
                    <option value="all">Semua Tipe Resi</option>
                    <option value="umum">Umum (Toko)</option>
                    <option value="marketplace">Marketplace</option>
                </select>
            </div>

            <!-- Search Input -->
            <div class="relative w-full sm:w-72">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/>
                    </svg>
                </div>
                <input type="search" id="search-input" oninput="handleSearchChange()" class="block w-full p-2.5 ps-9 bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-brand focus:border-brand placeholder:text-slate-400" placeholder="Cari No. Invoice / Resi / Barcode..." />
            </div>
        </div>
    </div>

    <!-- ==========================================================================
         SHARED UI COMPONENTS (LOADING & EMPTY STATES)
         ========================================================================== -->
    <div id="loading-skeleton" class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden mb-8 animate-pulse">
        <div class="h-12 bg-slate-50 border-b border-slate-100"></div>
        <div class="divide-y divide-slate-100">
            @for ($i = 0; $i < 4; $i++)
            <div class="px-6 py-4 flex items-center justify-between gap-6">
                <div class="h-4 bg-slate-100 rounded-md w-10 shrink-0"></div>
                <div class="h-4 bg-slate-100 rounded-md w-32 shrink-0"></div>
                <div class="h-4 bg-slate-100 rounded-md w-28 shrink-0"></div>
                <div class="h-4 bg-slate-100 rounded-md w-36 shrink-0 flex-1"></div>
                <div class="h-4 bg-slate-100 rounded-md w-24 shrink-0"></div>
                <div class="h-8 bg-slate-100 rounded-lg w-28 shrink-0"></div>
            </div>
            @endfor
        </div>
    </div>

    <div id="empty-state" class="hidden flex-col items-center justify-center py-16 px-4 bg-white border border-slate-100 rounded-2xl shadow-sm mb-8 text-center">
        <div class="flex items-center justify-center p-4 bg-indigo-50 text-indigo-500 rounded-2xl mb-4">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3h7.5M6 20.25h12a2.25 2.25 0 002.25-2.25V8.25a2.25 2.25 0 00-.75-1.591l-5.409-5.409A2.25 2.25 0 0012.5 1.5H6A2.25 2.25 0 003.75 3.75v14.25A2.25 2.25 0 006 20.25z"></path>
            </svg>
        </div>
        <h3 id="empty-title" class="text-base font-bold text-slate-800">Tidak Ada Data Ditemukan</h3>
        <p id="empty-desc" class="text-sm text-slate-500 mt-1 max-w-sm">Invoice dan resi akan dibuat secara otomatis saat transaksi penjualan ditambahkan.</p>
    </div>

    <!-- ==========================================================================
         INVOICE DATA TABLE
         ========================================================================== -->
    <div id="invoice-table-wrapper" class="hidden relative overflow-x-auto bg-white shadow-sm rounded-2xl border border-slate-200/80 mb-8">
        <table class="w-full text-sm text-left text-slate-700">
            <thead class="text-xs uppercase tracking-wider text-slate-500 bg-slate-50/80 border-b border-slate-200">
                <tr>
                    <th scope="col" class="px-4 py-3.5 w-10 text-center">
                        <input type="checkbox" id="select-all-invoice" onchange="toggleSelectAll('invoice', this.checked)" class="w-4 h-4 rounded border-slate-300 text-brand focus:ring-brand cursor-pointer">
                    </th>
                    <th scope="col" class="px-3 py-3.5 font-semibold">#</th>
                    <th scope="col" class="px-6 py-3.5 font-semibold">No. Invoice</th>
                    <th scope="col" class="px-6 py-3.5 font-semibold">Barcode Penjualan</th>
                    <th scope="col" class="px-6 py-3.5 font-semibold">Pelanggan / Counter</th>
                    <th scope="col" class="px-6 py-3.5 font-semibold">Tanggal</th>
                    <th scope="col" class="px-6 py-3.5 font-semibold">Grand Total</th>
                    <th scope="col" class="px-6 py-3.5 font-semibold">Jumlah Cetak</th>
                    <th scope="col" class="px-6 py-3.5 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody id="invoice-table-body" class="divide-y divide-slate-100">
                <!-- Rendered dynamically via InvoiceModule -->
            </tbody>
        </table>
    </div>

    <!-- ==========================================================================
         RESI DATA TABLE
         ========================================================================== -->
    <div id="resi-table-wrapper" class="hidden relative overflow-x-auto bg-white shadow-sm rounded-2xl border border-slate-200/80 mb-8">
        <table class="w-full text-sm text-left text-slate-700">
            <thead class="text-xs uppercase tracking-wider text-slate-500 bg-slate-50/80 border-b border-slate-200">
                <tr>
                    <th scope="col" class="px-4 py-3.5 w-10 text-center">
                        <input type="checkbox" id="select-all-resi" onchange="toggleSelectAll('resi', this.checked)" class="w-4 h-4 rounded border-slate-300 text-brand focus:ring-brand cursor-pointer">
                    </th>
                    <th scope="col" class="px-3 py-3.5 font-semibold">#</th>
                    <th scope="col" class="px-6 py-3.5 font-semibold">No. Resi</th>
                    <th scope="col" class="px-6 py-3.5 font-semibold">Barcode Penjualan</th>
                    <th scope="col" class="px-6 py-3.5 font-semibold">Tipe Penjualan</th>
                    <th scope="col" class="px-6 py-3.5 font-semibold">Tujuan / Ekspedisi</th>
                    <th scope="col" class="px-6 py-3.5 font-semibold">Tanggal</th>
                    <th scope="col" class="px-6 py-3.5 font-semibold">Jumlah Cetak</th>
                    <th scope="col" class="px-6 py-3.5 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody id="resi-table-body" class="divide-y divide-slate-100">
                <!-- Rendered dynamically via ResiModule -->
            </tbody>
        </table>
    </div>
</div>

<!-- ==========================================================================
     DOCUMENT PREVIEW & PRINT MODAL
     ========================================================================== -->
<div id="document-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300 opacity-0" id="modal-backdrop" onclick="closeModal()"></div>

    <div class="relative w-full max-w-4xl max-h-[90vh] flex flex-col transform scale-95 opacity-0 transition-all duration-300 z-10" id="modal-panel">
        <div class="bg-white border border-slate-200 rounded-2xl shadow-xl flex flex-col overflow-hidden">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-5 border-b border-slate-100 bg-slate-50/80">
                <div>
                    <h3 id="modal-title" class="text-lg font-bold text-slate-800">Pratinjau Dokumen</h3>
                    <p id="modal-subtitle" class="text-xs text-slate-500 mt-0.5">Lihat rincian dokumen sebelum dicetak</p>
                </div>
                <button type="button" onclick="closeModal()" class="text-slate-400 hover:text-slate-600 p-2 rounded-xl hover:bg-slate-200/60 transition cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Modal Printable Body -->
            <div id="printable-area" class="p-6 overflow-y-auto max-h-[75vh] flex flex-col items-center justify-start min-h-[300px] text-slate-800 bg-slate-100/60 gap-6">
                <!-- Content dynamically injected -->
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end gap-3 p-4 border-t border-slate-100 bg-slate-50/80">
                <button type="button" onclick="closeModal()" class="px-4 py-2.5 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-100 transition cursor-pointer">
                    Tutup
                </button>
                <button type="button" id="btn-do-print" onclick="executePrint()" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-brand hover:bg-brand-hover rounded-xl shadow-xs transition cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Cetak Dokumen
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ==========================================================================
     STYLES (INVOICE & RESI SEPARATED)
     ========================================================================== -->
<style>
    *,
    *::before,
    *::after {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        color-adjust: exact !important;
    }

    /* --------------------------------------------------------------------------
       INVOICE SPECIFIC STYLES (Screen preview)
       -------------------------------------------------------------------------- */
    .print-document-invoice {
        width: 18cm !important;
        height: 12cm !important;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        border: none !important;
        margin: 0 auto !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    /* --------------------------------------------------------------------------
       RESI SPECIFIC STYLES (Screen preview)
       -------------------------------------------------------------------------- */
    .print-document-resi {
        width: 9cm !important;
        height: 8cm !important;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        border: none !important;
        margin: 0 auto !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    /* --------------------------------------------------------------------------
       PRINT MEDIA STYLES
       -------------------------------------------------------------------------- */
    @media print {
        *,
        *::before,
        *::after {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color-adjust: exact !important;
        }

        /* Explicitly hide non-printable UI components to avoid blank layout spacing */
        aside,
        header,
        nav,
        .relative.min-h-\[calc\(100vh-8rem\)\],
        #modal-backdrop,
        #modal-panel > div > div.flex.items-center.justify-between,
        #modal-panel > div > div.flex.items-center.justify-end {
            display: none !important;
        }

        /* Reset parent wrappers layout */
        html, body, .min-h-screen, .sm\:ml-64, main {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            height: auto !important;
            min-height: 0 !important;
            background: #ffffff !important;
            overflow: visible !important;
            border: none !important;
        }

        #document-modal {
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            height: auto !important;
            background: #ffffff !important;
            padding: 0 !important;
            margin: 0 !important;
            backdrop-filter: none !important;
            display: block !important;
            opacity: 1 !important;
            overflow: visible !important;
            z-index: 99999 !important;
        }

        #modal-panel,
        #modal-panel > div {
            max-width: none !important;
            width: 100% !important;
            height: auto !important;
            transform: none !important;
            box-shadow: none !important;
            border: none !important;
            background: transparent !important;
            margin: 0 !important;
            padding: 0 !important;
            position: static !important;
            overflow: visible !important;
        }

        #printable-area {
            max-height: none !important;
            overflow: visible !important;
            padding: 0 !important;
            margin: 0 !important;
            border: none !important;
            gap: 0 !important;
            background: transparent !important;
            display: block !important;
            float: none !important;
            width: 100% !important;
            height: auto !important;
            position: static !important;
        }

        /* Invoice Print Layout */
        .print-document-invoice {
            display: block !important;
            width: 17cm !important;
            height: 11cm !important;
            max-height: 11cm !important;
            margin: 0 auto !important;
            padding: 0 !important;
            border: none !important;
            box-sizing: border-box !important;
            box-shadow: none !important;
            page-break-inside: avoid !important;
            break-inside: avoid !important;
            page-break-after: always !important;
            break-after: page !important;
            overflow: hidden !important;
        }

        .print-document-invoice:last-child {
            page-break-after: avoid !important;
            break-after: avoid !important;
        }

        /* Resi Print Layout */
        .print-document-resi {
            display: flex !important;
            flex-direction: column !important;
            justify-content: space-between !important;
            width: 8.2cm !important;
            height: 7.2cm !important;
            max-height: 7.2cm !important;
            margin: 0 auto !important;
            padding: 0 !important;
            border: none !important;
            box-sizing: border-box !important;
            box-shadow: none !important;
            page-break-inside: avoid !important;
            break-inside: avoid !important;
            page-break-after: always !important;
            break-after: page !important;
            overflow: hidden !important;
        }

        .print-document-resi:last-child {
            page-break-after: avoid !important;
            break-after: avoid !important;
        }
    }
</style>

<!-- External Barcode Library -->
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>

<script>
    /* ==========================================================================
       GLOBAL STATE & UTILITIES
       ========================================================================== */
    let activeMainTab = 'invoice'; // 'invoice' | 'resi'
    let activeStatusFilter = 'unprinted'; // 'unprinted' | 'printed' | 'all'
    let activePrintItems = [];
    let activePrintType = 'invoice';

    document.addEventListener('DOMContentLoaded', () => {
        loadData();
    });

    async function loadData() {
        showLoading(true);
        try {
            await Promise.all([
                InvoiceModule.load(),
                ResiModule.load()
            ]);

            // Update main tab badges
            document.getElementById('badge-invoice-total').textContent = InvoiceModule.data.length;
            document.getElementById('badge-resi-total').textContent = ResiModule.data.length;

            updateStatusBadges();
            renderActiveTab();
        } catch (error) {
            console.error('Gagal memuat data:', error);
        } finally {
            showLoading(false);
        }
    }

    function updateStatusBadges() {
        const module = activeMainTab === 'invoice' ? InvoiceModule : ResiModule;
        document.getElementById('badge-status-unprinted').textContent = module.getUnprintedCount();
        document.getElementById('badge-status-printed').textContent = module.getPrintedCount();
        document.getElementById('badge-status-all').textContent = module.getTotalCount();
    }

    function switchMainTab(tabName) {
        activeMainTab = tabName;
        const btnInv = document.getElementById('main-tab-invoice');
        const btnResi = document.getElementById('main-tab-resi');
        const badgeInv = document.getElementById('badge-invoice-total');
        const badgeResi = document.getElementById('badge-resi-total');
        const resiFilterWrapper = document.getElementById('resi-type-filter-wrapper');

        const activeMainClass = 'flex items-center gap-2.5 px-5 py-2.5 text-sm font-bold rounded-xl transition-all cursor-pointer bg-brand text-white shadow-xs';
        const inactiveMainClass = 'flex items-center gap-2.5 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-900 rounded-xl transition-all cursor-pointer';

        const activeMainBadgeClass = 'px-2 py-0.5 text-xs rounded-full bg-white/20 text-white font-extrabold';
        const inactiveMainBadgeClass = 'px-2 py-0.5 text-xs rounded-full bg-slate-300/80 text-slate-800 font-extrabold';

        if (tabName === 'invoice') {
            btnInv.className = activeMainClass;
            badgeInv.className = activeMainBadgeClass;
            btnResi.className = inactiveMainClass;
            badgeResi.className = inactiveMainBadgeClass;
            resiFilterWrapper.classList.add('hidden');
        } else {
            btnResi.className = activeMainClass;
            badgeResi.className = activeMainBadgeClass;
            btnInv.className = inactiveMainClass;
            badgeInv.className = inactiveMainBadgeClass;
            resiFilterWrapper.classList.remove('hidden');
        }

        updateStatusBadges();
        renderActiveTab();
    }

    function switchStatusFilter(status) {
        activeStatusFilter = status;

        const pillUnprinted = document.getElementById('status-pill-unprinted');
        const pillPrinted = document.getElementById('status-pill-printed');
        const pillAll = document.getElementById('status-pill-all');

        const badgeUnprinted = document.getElementById('badge-status-unprinted');
        const badgePrinted = document.getElementById('badge-status-printed');
        const badgeAll = document.getElementById('badge-status-all');

        if (status === 'unprinted') {
            pillUnprinted.className = 'flex items-center gap-2 px-3.5 py-1.5 text-xs font-bold rounded-lg transition-all cursor-pointer bg-amber-500 text-white shadow-xs whitespace-nowrap';
            badgeUnprinted.className = 'px-1.5 py-0.5 text-[11px] rounded-full bg-white/20 text-white font-extrabold';
        } else {
            pillUnprinted.className = 'flex items-center gap-2 px-3.5 py-1.5 text-xs font-medium text-slate-600 hover:text-slate-900 rounded-lg transition-all cursor-pointer whitespace-nowrap';
            badgeUnprinted.className = 'px-1.5 py-0.5 text-[11px] rounded-full bg-amber-100 text-amber-800 font-bold';
        }

        if (status === 'printed') {
            pillPrinted.className = 'flex items-center gap-2 px-3.5 py-1.5 text-xs font-bold rounded-lg transition-all cursor-pointer bg-emerald-600 text-white shadow-xs whitespace-nowrap';
            badgePrinted.className = 'px-1.5 py-0.5 text-[11px] rounded-full bg-white/20 text-white font-extrabold';
        } else {
            pillPrinted.className = 'flex items-center gap-2 px-3.5 py-1.5 text-xs font-medium text-slate-600 hover:text-slate-900 rounded-lg transition-all cursor-pointer whitespace-nowrap';
            badgePrinted.className = 'px-1.5 py-0.5 text-[11px] rounded-full bg-emerald-100 text-emerald-800 font-bold';
        }

        if (status === 'all') {
            pillAll.className = 'flex items-center gap-2 px-3.5 py-1.5 text-xs font-bold rounded-lg transition-all cursor-pointer bg-slate-800 text-white shadow-xs whitespace-nowrap';
            badgeAll.className = 'px-1.5 py-0.5 text-[11px] rounded-full bg-white/20 text-white font-extrabold';
        } else {
            pillAll.className = 'flex items-center gap-2 px-3.5 py-1.5 text-xs font-medium text-slate-600 hover:text-slate-900 rounded-lg transition-all cursor-pointer whitespace-nowrap';
            badgeAll.className = 'px-1.5 py-0.5 text-[11px] rounded-full bg-slate-200 text-slate-700 font-bold';
        }

        renderActiveTab();
    }

    function handleSearchChange() {
        renderActiveTab();
    }

    function handleFilterChange() {
        renderActiveTab();
    }

    function toggleSelectAll(type, checked) {
        const module = type === 'invoice' ? InvoiceModule : ResiModule;
        module.selectAll(checked);
        updateBulkPrintUI();
        renderActiveTab();
    }

    function toggleItemSelect(type, id, checked) {
        const module = type === 'invoice' ? InvoiceModule : ResiModule;
        module.toggleSelect(id, checked);
        updateBulkPrintUI();
        updateSelectAllCheckboxState();
    }

    function updateSelectAllCheckboxState() {
        const selectAllCheckbox = document.getElementById(activeMainTab === 'invoice' ? 'select-all-invoice' : 'select-all-resi');
        if (!selectAllCheckbox) return;
        const module = activeMainTab === 'invoice' ? InvoiceModule : ResiModule;

        if (module.filteredItems.length > 0 && module.filteredItems.every(item => module.selectedIds.has(Number(item.id)))) {
            selectAllCheckbox.checked = true;
            selectAllCheckbox.indeterminate = false;
        } else if (module.filteredItems.some(item => module.selectedIds.has(Number(item.id)))) {
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = true;
        } else {
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = false;
        }
    }

    function updateBulkPrintUI() {
        const btnBulk = document.getElementById('btn-bulk-print');
        const badgeCount = document.getElementById('bulk-selected-badge');
        const module = activeMainTab === 'invoice' ? InvoiceModule : ResiModule;
        const count = module.selectedIds.size;

        if (count > 0) {
            btnBulk.classList.remove('hidden');
            badgeCount.textContent = count;
        } else {
            btnBulk.classList.add('hidden');
            badgeCount.textContent = '0';
        }
    }

    function renderActiveTab() {
        const query = (document.getElementById('search-input').value || '').toLowerCase().trim();
        const invoiceWrapper = document.getElementById('invoice-table-wrapper');
        const resiWrapper = document.getElementById('resi-table-wrapper');
        const emptyState = document.getElementById('empty-state');

        if (activeMainTab === 'invoice') {
            resiWrapper.classList.add('hidden');
            const filtered = InvoiceModule.filter(query, activeStatusFilter);

            if (filtered.length === 0) {
                invoiceWrapper.classList.add('hidden');
                emptyState.classList.remove('hidden');
                emptyState.classList.add('flex');
                if (activeStatusFilter === 'printed') {
                    document.getElementById('empty-title').textContent = 'Tidak Ada Invoice Sudah Dicetak';
                    document.getElementById('empty-desc').textContent = 'Belum ada invoice yang pernah dicetak.';
                } else if (activeStatusFilter === 'unprinted') {
                    document.getElementById('empty-title').textContent = 'Tidak Ada Invoice Belum Dicetak';
                    document.getElementById('empty-desc').textContent = 'Semua invoice penjualan telah dicetak.';
                } else {
                    document.getElementById('empty-title').textContent = 'Tidak Ada Invoice';
                    document.getElementById('empty-desc').textContent = 'Belum ada data invoice penjualan.';
                }
            } else {
                emptyState.classList.add('hidden');
                emptyState.classList.remove('flex');
                invoiceWrapper.classList.remove('hidden');
                InvoiceModule.renderTable(filtered);
            }
        } else {
            invoiceWrapper.classList.add('hidden');
            const typeFilter = document.getElementById('resi-type-filter').value;
            const filtered = ResiModule.filter(query, activeStatusFilter, typeFilter);

            if (filtered.length === 0) {
                resiWrapper.classList.add('hidden');
                emptyState.classList.remove('hidden');
                emptyState.classList.add('flex');
                if (activeStatusFilter === 'printed') {
                    document.getElementById('empty-title').textContent = 'Tidak Ada Resi Sudah Dicetak';
                    document.getElementById('empty-desc').textContent = 'Belum ada resi pengiriman yang pernah dicetak.';
                } else if (activeStatusFilter === 'unprinted') {
                    document.getElementById('empty-title').textContent = 'Tidak Ada Resi Belum Dicetak';
                    document.getElementById('empty-desc').textContent = 'Semua resi pengiriman telah dicetak.';
                } else {
                    document.getElementById('empty-title').textContent = 'Tidak Ada Resi';
                    document.getElementById('empty-desc').textContent = 'Belum ada data resi pengiriman.';
                }
            } else {
                emptyState.classList.add('hidden');
                emptyState.classList.remove('flex');
                resiWrapper.classList.remove('hidden');
                ResiModule.renderTable(filtered);
            }
        }

        updateSelectAllCheckboxState();
        updateBulkPrintUI();
    }

    function previewDocument(type, id) {
        let item = null;
        const targetId = Number(id);
        if (type === 'invoice') {
            item = InvoiceModule.data.find(x => Number(x.id) === targetId);
        } else {
            item = ResiModule.data.find(x => Number(x.id) === targetId);
        }

        if (!item) return;

        activePrintItems = [item];
        activePrintType = type;
        const area = document.getElementById('printable-area');

        const isResiMarketplace = type === 'resi' && item.type === 'marketplace';
        const isPrinted = (item.printed_count || 0) > 0;

        if (type === 'invoice') {
            document.getElementById('modal-title').textContent = isPrinted ? 'Invoice Penjualan (Cetak Ulang)' : 'Invoice Penjualan (Umum)';
            document.getElementById('modal-subtitle').textContent = `No. Invoice: ${item.invoice_number} | Ukuran Fisik Cetak: 18cm x 12cm${isPrinted ? ` | Sudah dicetak ${item.printed_count}x` : ''}`;
            area.innerHTML = InvoiceModule.generateHTML(item);
        } else if (isResiMarketplace) {
            document.getElementById('modal-title').textContent = isPrinted ? 'Resi Pengiriman (Marketplace - Cetak Ulang)' : 'Resi Pengiriman (Marketplace)';
            document.getElementById('modal-subtitle').textContent = `No. Resi: ${item.receipt_number} | Ukuran Fisik Cetak: 9cm x 8cm${isPrinted ? ` | Sudah dicetak ${item.printed_count}x` : ''}`;
            area.innerHTML = ResiModule.generateHTML(item);
            ResiModule.renderBarcodes([item]);
        } else {
            document.getElementById('modal-title').textContent = isPrinted ? 'Resi Pengiriman (Umum - Cetak Ulang)' : 'Resi Pengiriman (Umum)';
            document.getElementById('modal-subtitle').textContent = `No. Resi: ${item.receipt_number} | Ukuran Fisik Cetak: 9cm x 8cm${isPrinted ? ` | Sudah dicetak ${item.printed_count}x` : ''}`;
            area.innerHTML = ResiModule.generateHTML(item);
        }

        openModal();
    }

    function previewBulkDocuments() {
        const type = activeMainTab;
        const module = type === 'invoice' ? InvoiceModule : ResiModule;
        if (module.selectedIds.size === 0) return;

        activePrintItems = module.data.filter(item => module.selectedIds.has(Number(item.id)));
        activePrintType = type;

        const area = document.getElementById('printable-area');
        document.getElementById('modal-title').textContent = `Pratinjau Bulk Cetak ${type === 'invoice' ? 'Invoice' : 'Resi Pengiriman'} (${activePrintItems.length} Dokumen)`;
        document.getElementById('modal-subtitle').textContent = `Ukuran Fisik Cetak: ${type === 'invoice' ? '18cm x 12cm' : '9cm x 8cm'} per Halaman (Page Break Otomatis)`;

        area.innerHTML = activePrintItems.map(item => type === 'invoice' ? InvoiceModule.generateHTML(item) : ResiModule.generateHTML(item)).join('');

        if (type === 'resi') {
            ResiModule.renderBarcodes(activePrintItems);
        }

        openModal();
    }

    async function executePrint() {
        let dynamicPageStyle = document.getElementById('dynamic-page-style');
        if (!dynamicPageStyle) {
            dynamicPageStyle = document.createElement('style');
            dynamicPageStyle.id = 'dynamic-page-style';
            document.head.appendChild(dynamicPageStyle);
        }

        if (activePrintItems.length > 0) {
            const type = activePrintType;
            if (type === 'invoice') {
                dynamicPageStyle.innerHTML = '@page { size: 18cm 12cm; margin: 0.5cm; }';
            } else {
                dynamicPageStyle.innerHTML = '@page { size: 9cm 8cm; margin: 0.4cm; }';
            }

            const endpoint = type === 'invoice' ? '/invoices/bulk-print' : '/recipts/bulk-print';
            const ids = activePrintItems.map(item => Number(item.id));

            try {
                await fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ ids: ids })
                });
            } catch (err) {
                console.error('Gagal memperbarui printed_count:', err);
            }

            // Clear selection for printed items
            const module = type === 'invoice' ? InvoiceModule : ResiModule;
            ids.forEach(id => module.selectedIds.delete(id));

            window.print();
            closeModal();
            loadData();
        }
    }

    function openModal() {
        const modal = document.getElementById('document-modal');
        const backdrop = document.getElementById('modal-backdrop');
        const panel = document.getElementById('modal-panel');
        const area = document.getElementById('printable-area');

        if (area) {
            area.scrollTop = 0;
        }

        modal.classList.remove('hidden');
        setTimeout(() => {
            backdrop.classList.remove('opacity-0');
            panel.classList.remove('scale-95', 'opacity-0');
        }, 10);
    }

    function closeModal() {
        const modal = document.getElementById('document-modal');
        const backdrop = document.getElementById('modal-backdrop');
        const panel = document.getElementById('modal-panel');

        backdrop.classList.add('opacity-0');
        panel.classList.add('scale-95', 'opacity-0');

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    function showLoading(show) {
        const skeleton = document.getElementById('loading-skeleton');
        if (show) {
            skeleton.classList.remove('hidden');
            document.getElementById('invoice-table-wrapper').classList.add('hidden');
            document.getElementById('resi-table-wrapper').classList.add('hidden');
            document.getElementById('empty-state').classList.add('hidden');
            document.getElementById('empty-state').classList.remove('flex');
        } else {
            skeleton.classList.add('hidden');
        }
    }

    function formatIndoNumber(val) {
        if (val === null || val === undefined || isNaN(val)) return '0';
        const num = Number(val);
        return num.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 2 });
    }

    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    /* ==========================================================================
       INVOICE MODULE
       ========================================================================== */
    const InvoiceModule = {
        data: [],
        selectedIds: new Set(),
        filteredItems: [],

        async load() {
            try {
                const res = await fetch('/invoices', { headers: { 'Accept': 'application/json' } });
                if (res.ok) {
                    this.data = await res.json();
                }
            } catch (err) {
                console.error('Error loading invoices:', err);
            }
        },

        getUnprintedCount() {
            return this.data.filter(i => (i.printed_count || 0) === 0).length;
        },

        getPrintedCount() {
            return this.data.filter(i => (i.printed_count || 0) > 0).length;
        },

        getTotalCount() {
            return this.data.length;
        },

        filter(query, statusFilter) {
            this.filteredItems = this.data.filter(item => {
                const printedCount = item.printed_count || 0;
                let matchesStatus = true;
                if (statusFilter === 'unprinted') matchesStatus = printedCount === 0;
                if (statusFilter === 'printed') matchesStatus = printedCount > 0;

                const invNo = (item.invoice_number || '').toLowerCase();
                const barcode = (item.sale?.barcode || '').toLowerCase();
                const cust = (item.sale?.customer?.name || '').toLowerCase();
                const counter = (item.sale?.counter?.name || '').toLowerCase();

                const matchesQuery = invNo.includes(query) || barcode.includes(query) || cust.includes(query) || counter.includes(query);
                return matchesStatus && matchesQuery;
            });
            return this.filteredItems;
        },

        selectAll(checked) {
            if (checked) {
                this.filteredItems.forEach(item => this.selectedIds.add(Number(item.id)));
            } else {
                this.filteredItems.forEach(item => this.selectedIds.delete(Number(item.id)));
            }
        },

        toggleSelect(id, checked) {
            const numId = Number(id);
            if (checked) {
                this.selectedIds.add(numId);
            } else {
                this.selectedIds.delete(numId);
            }
        },

        renderTable(data) {
            const tbody = document.getElementById('invoice-table-body');
            tbody.innerHTML = '';

            data.forEach((item, index) => {
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-slate-50/80 transition-colors';

                const isChecked = this.selectedIds.has(Number(item.id));
                const createdDate = item.created_at ? new Date(item.created_at).toLocaleDateString('id-ID', {
                    year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit'
                }) : '-';

                const grandTotal = item.sale?.grand_total ? 'Rp ' + Number(item.sale.grand_total).toLocaleString('id-ID') : 'Rp 0';
                const customerName = item.sale?.customer?.name || item.sale?.counter?.name || 'Umum';

                const printedCount = item.printed_count || 0;
                const badgeClass = printedCount > 0 
                    ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' 
                    : 'bg-amber-50 text-amber-700 border border-amber-200';
                const badgeText = printedCount > 0 ? `${printedCount}x Dicetak` : 'Belum Dicetak';
                const buttonLabel = printedCount > 0 ? 'Detail / Cetak Ulang' : 'Detail / Cetak';

                tr.innerHTML = `
                    <td class="px-4 py-4 text-center">
                        <input type="checkbox" class="w-4 h-4 rounded border-slate-300 text-brand focus:ring-brand cursor-pointer" value="${item.id}" ${isChecked ? 'checked' : ''} onchange="toggleItemSelect('invoice', ${item.id}, this.checked)">
                    </td>
                    <td class="px-3 py-4 text-xs font-semibold text-slate-400">${index + 1}</td>
                    <td class="px-6 py-4 font-bold text-brand">${escapeHtml(item.invoice_number)}</td>
                    <td class="px-6 py-4 font-mono text-xs text-slate-600">${escapeHtml(item.sale?.barcode || '-')}</td>
                    <td class="px-6 py-4 font-medium text-slate-800">${escapeHtml(customerName)}</td>
                    <td class="px-6 py-4 text-xs text-slate-500">${createdDate}</td>
                    <td class="px-6 py-4 font-semibold text-slate-900">${grandTotal}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium ${badgeClass}">
                            ${badgeText}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <button onclick="previewDocument('invoice', ${item.id})" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition cursor-pointer">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                ${buttonLabel}
                            </button>
                        </div>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        },

        generateHTML(item) {
            const sale = item.sale || {};
            const items = sale.items || [];

            const createdDateStr = item.created_at ? new Date(item.created_at).toLocaleDateString('id-ID', {
                day: '2-digit', month: '2-digit', year: 'numeric'
            }) : (sale.date ? new Date(sale.date).toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' }) : '-');

            const customerName = sale.customer?.name || 'Ibu / Bapak / kakak';
            const customerPhone = sale.customer?.phone || '';
            const customerAddress = sale.customer?.address || 'Jl. Madava II, RESIDEN CITY, jakarta Utara';
            const expeditionName = sale.expedition?.name || sale.courier?.name || 'KALOG';
            const counterName = sale.counter?.name || 'COUNTER';

            const subtotalNum = Number(sale.subtotal || 0);
            const shippingNum = Number(sale.shipping_cost || 0);
            const totalWithShippingNum = subtotalNum + shippingNum;
            const discountNum = Number(sale.discount || 0);
            const grandTotalNum = Number(sale.grand_total || (totalWithShippingNum - discountNum));

            let itemsRows = items.length > 0 ? items.map((itm, idx) => {
                const qtyVal = Number(itm.qty || 0);
                const unitName = itm.product?.unit?.name || 'Kg';
                const priceNum = Number(itm.price || 0);
                const subtotalItemNum = Number(itm.subtotal || (qtyVal * priceNum));

                return `
                    <tr>
                        <td style="border: 1px solid #000000; padding: 4px 6px; text-align: center;">${idx + 1}</td>
                        <td style="border: 1px solid #000000; padding: 4px 6px; font-style: italic;">${escapeHtml(itm.product?.name || 'Produk')}</td>
                        <td style="border: 1px solid #000000; padding: 4px 6px; text-align: right;">${formatIndoNumber(qtyVal)}</td>
                        <td style="border: 1px solid #000000; padding: 4px 6px; text-align: center;">${escapeHtml(unitName)}</td>
                        <td style="border: 1px solid #000000; padding: 4px 6px; text-align: right;">${priceNum > 0 ? formatIndoNumber(priceNum) : '-'}</td>
                        <td style="border: 1px solid #000000; padding: 4px 6px; text-align: right;">${subtotalItemNum > 0 ? formatIndoNumber(subtotalItemNum) : '-'}</td>
                    </tr>
                `;
            }).join('') : `
                <tr>
                    <td colspan="6" style="border: 1px solid #000000; padding: 8px; text-align: center; color: #6b7280;">Tidak ada detail item</td>
                </tr>
            `;

            return `
                <div class="print-document-invoice" style="width: 18cm; height: 12cm; background: #ffffff; border: none; padding: 12px; box-sizing: border-box; font-family: Arial, sans-serif; font-size: 11px; color: #000000; line-height: 1.3;">
                    <!-- Header Section -->
                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 8px;">
                        <tr>
                            <td style="vertical-align: top; width: 60%; padding: 0;">
                                <div style="font-size: 11px;">Kepada Yth : &nbsp; Ibu / Bapak / kakak</div>
                                <div style="font-size: 13px; font-weight: bold; margin-top: 2px;">
                                    ${escapeHtml(customerName)} 
                                    <span style="font-weight: bold; margin-left: 12px;">${escapeHtml(customerPhone)}</span>
                                </div>
                                <div style="font-size: 11px; margin-top: 2px; line-height: 1.2;">${escapeHtml(customerAddress)}</div>
                            </td>
                            <td style="vertical-align: top; width: 40%; padding: 0;">
                                <table style="margin-left: auto; border-collapse: collapse; text-align: center; font-size: 11px;">
                                    <tr>
                                        <td style="background-color: #ffff00; -webkit-print-color-adjust: exact; print-color-adjust: exact; border: 1px solid #000000; padding: 3px 12px; font-weight: bold; min-width: 150px;">
                                            Tanggal : &nbsp; ${createdDateStr}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #ffff00; -webkit-print-color-adjust: exact; print-color-adjust: exact; border: 1px solid #000000; border-top: none; padding: 3px 12px; font-weight: bold; text-align: right;">
                                            No Transaksi : &nbsp; ${escapeHtml(item.invoice_number || sale.barcode || '-')}
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>

                    <!-- Table Items -->
                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 8px; font-size: 11px; border: 1px solid #000000;">
                        <thead>
                            <tr style="background-color: #ffc000; -webkit-print-color-adjust: exact; print-color-adjust: exact; text-align: center; font-weight: bold;">
                                <th style="border: 1px solid #000000; padding: 4px 6px; width: 6%;">No</th>
                                <th style="border: 1px solid #000000; padding: 4px 6px; text-align: center; width: 38%;">Nama Barang</th>
                                <th style="border: 1px solid #000000; padding: 4px 6px; width: 10%;">Qty</th>
                                <th style="border: 1px solid #000000; padding: 4px 6px; width: 22%;" colspan="2">Satuan</th>
                                <th style="border: 1px solid #000000; padding: 4px 6px; width: 24%;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${itemsRows}
                            <!-- Ongkir Row -->
                            <tr>
                                <td colspan="5" style="border: 1px solid #000000; text-align: right; padding: 3px 8px; font-weight: normal;">Ongkir</td>
                                <td style="border: 1px solid #000000; text-align: right; padding: 3px 8px;">${formatIndoNumber(shippingNum)}</td>
                            </tr>
                            <!-- Total Row -->
                            <tr>
                                <td colspan="5" style="border: 1px solid #000000; text-align: right; padding: 3px 8px; font-weight: normal;">Total</td>
                                <td style="border: 1px solid #000000; text-align: right; padding: 3px 8px;">${formatIndoNumber(totalWithShippingNum)}</td>
                            </tr>
                            <!-- Disc Row -->
                            <tr style="-webkit-print-color-adjust: exact; print-color-adjust: exact;">
                                <td colspan="5" style="border: 1px solid #000000; text-align: right; padding: 3px 8px; color: #ff0000; font-weight: normal;">Disc</td>
                                <td style="border: 1px solid #000000; text-align: right; padding: 3px 8px; color: #ff0000;">${discountNum > 0 ? formatIndoNumber(discountNum) : '-'}</td>
                            </tr>
                            <!-- Sub Total Row -->
                            <tr style="background-color: #dce6f1; -webkit-print-color-adjust: exact; print-color-adjust: exact; font-weight: bold;">
                                <td colspan="5" style="border: 1px solid #000000; text-align: right; padding: 4px 8px;">Sub Total</td>
                                <td style="border: 1px solid #000000; text-align: right; padding: 4px 8px;">${formatIndoNumber(grandTotalNum)}</td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Footer Section -->
                    <table style="width: 100%; border-collapse: collapse; font-size: 11px; margin-top: 12px;">
                        <tr>
                            <td style="vertical-align: bottom; width: 65%; padding: 0;">
                                <div style="font-style: italic; color: #111827; margin-bottom: 12px;">Pembayaran dianggap lunas setelah adanya bukti transfer</div>
                                <div style="font-weight: normal;">Expedisi &nbsp; : &nbsp;&nbsp;&nbsp;&nbsp; <span style="font-weight: normal; text-transform: uppercase;">${escapeHtml(expeditionName)}</span></div>
                            </td>
                            <td style="vertical-align: bottom; width: 35%; padding: 0; text-align: center;">
                                <div style="margin-bottom: 24px;">Hormat Kami</div>
                                <div style="font-weight: normal; text-transform: uppercase;">${escapeHtml(counterName)}</div>
                            </td>
                        </tr>
                    </table>
                </div>
            `;
        }
    };

    /* ==========================================================================
       RESI MODULE
       ========================================================================== */
    const ResiModule = {
        data: [],
        selectedIds: new Set(),
        filteredItems: [],

        async load() {
            try {
                const res = await fetch('/recipts', { headers: { 'Accept': 'application/json' } });
                if (res.ok) {
                    this.data = await res.json();
                }
            } catch (err) {
                console.error('Error loading resis:', err);
            }
        },

        getUnprintedCount() {
            return this.data.filter(i => (i.printed_count || 0) === 0).length;
        },

        getPrintedCount() {
            return this.data.filter(i => (i.printed_count || 0) > 0).length;
        },

        getTotalCount() {
            return this.data.length;
        },

        filter(query, statusFilter, typeFilter) {
            this.filteredItems = this.data.filter(item => {
                const printedCount = item.printed_count || 0;
                let matchesStatus = true;
                if (statusFilter === 'unprinted') matchesStatus = printedCount === 0;
                if (statusFilter === 'printed') matchesStatus = printedCount > 0;

                const resiNo = (item.receipt_number || '').toLowerCase();
                const barcode = (item.sale?.barcode || '').toLowerCase();
                const type = (item.type || '').toLowerCase();
                const cust = (item.sale?.customer?.name || '').toLowerCase();
                const mp = (item.sale?.marketplace?.name || '').toLowerCase();

                const matchesQuery = resiNo.includes(query) || barcode.includes(query) || type.includes(query) || cust.includes(query) || mp.includes(query);
                const matchesFilter = typeFilter === 'all' || item.type === typeFilter;

                return matchesStatus && matchesQuery && matchesFilter;
            });
            return this.filteredItems;
        },

        selectAll(checked) {
            if (checked) {
                this.filteredItems.forEach(item => this.selectedIds.add(Number(item.id)));
            } else {
                this.filteredItems.forEach(item => this.selectedIds.delete(Number(item.id)));
            }
        },

        toggleSelect(id, checked) {
            const numId = Number(id);
            if (checked) {
                this.selectedIds.add(numId);
            } else {
                this.selectedIds.delete(numId);
            }
        },

        renderTable(data) {
            const tbody = document.getElementById('resi-table-body');
            tbody.innerHTML = '';

            data.forEach((item, index) => {
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-slate-50/80 transition-colors';

                const isChecked = this.selectedIds.has(Number(item.id));
                const createdDate = item.created_at ? new Date(item.created_at).toLocaleDateString('id-ID', {
                    year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit'
                }) : '-';

                const isMarketplace = item.type === 'marketplace';
                const typeBadge = isMarketplace
                    ? `<span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-purple-50 text-purple-700 border border-purple-200">Marketplace (${escapeHtml(item.sale?.marketplace?.name || '-')})</span>`
                    : `<span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-50 text-blue-700 border border-blue-200">Umum / Toko</span>`;

                const courierExpedition = [
                    item.sale?.expedition?.name,
                    item.sale?.courier?.name
                ].filter(Boolean).join(' - ') || 'Reguler';

                const printedCount = item.printed_count || 0;
                const badgeClass = printedCount > 0 
                    ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' 
                    : 'bg-amber-50 text-amber-700 border border-amber-200';
                const badgeText = printedCount > 0 ? `${printedCount}x Dicetak` : 'Belum Dicetak';
                const buttonLabel = printedCount > 0 ? 'Detail / Cetak Ulang' : 'Detail / Cetak';

                tr.innerHTML = `
                    <td class="px-4 py-4 text-center">
                        <input type="checkbox" class="w-4 h-4 rounded border-slate-300 text-brand focus:ring-brand cursor-pointer" value="${item.id}" ${isChecked ? 'checked' : ''} onchange="toggleItemSelect('resi', ${item.id}, this.checked)">
                    </td>
                    <td class="px-3 py-4 text-xs font-semibold text-slate-400">${index + 1}</td>
                    <td class="px-6 py-4 font-bold text-brand">${escapeHtml(item.receipt_number)}</td>
                    <td class="px-6 py-4 font-mono text-xs text-slate-600">${escapeHtml(item.sale?.barcode || '-')}</td>
                    <td class="px-6 py-4">${typeBadge}</td>
                    <td class="px-6 py-4 font-medium text-slate-800">${escapeHtml(courierExpedition)}</td>
                    <td class="px-6 py-4 text-xs text-slate-500">${createdDate}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium ${badgeClass}">
                            ${badgeText}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <button onclick="previewDocument('resi', ${item.id})" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition cursor-pointer">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                ${buttonLabel}
                            </button>
                        </div>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        },

        generateHTML(item) {
            const sale = item.sale || {};
            const items = sale.items || [];
            const isMarketplace = item.type === 'marketplace';

            const customerName = sale.customer?.name || 'Ibu / Bapak / kakak';
            const customerPhone = sale.customer?.phone || '';
            const customerAddress = sale.customer?.address || 'Jl. Madava II, RESIDEN CITY, jakarta Utara';
            const expeditionName = sale.expedition?.name || sale.courier?.name || 'KALOG';
            const marketplaceName = sale.marketplace?.name || 'Marketplace';
            const courierName = sale.courier?.name || sale.expedition?.name || 'Kurir';

            let itemsRowsResi = items.length > 0 ? items.map(itm => `
                <tr>
                    <td style="border: 1px solid #000000; padding: 3px 6px; text-transform: lowercase;">${escapeHtml(itm.product?.name || 'Barang')}</td>
                    <td style="border: 1px solid #000000; padding: 3px 6px; text-align: right; width: 75px;">${formatIndoNumber(itm.qty)} ${escapeHtml(itm.product?.unit?.name || 'Kg')}</td>
                    <td style="border: 1px solid #000000; padding: 3px 6px; text-align: center; width: 45px; font-weight: bold;">${formatIndoNumber(itm.qty)}</td>
                </tr>
            `).join('') : `
                <tr>
                    <td colspan="3" style="border: 1px solid #000000; padding: 4px; text-align: center; color: #6b7280;">Tidak ada barang</td>
                </tr>
            `;

            if (!isMarketplace) {
                return `
                    <div class="print-document-resi" style="width: 9cm; height: 8cm; background: #ffffff; border: none; padding: 6px; box-sizing: border-box; font-family: Arial, sans-serif; font-size: 11px; color: #000000; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <!-- Recipient Box -->
                            <table style="width: 100%; border-collapse: collapse; border: 1px solid #000000; margin-bottom: 4px; font-size: 11px;">
                                <tr>
                                    <td style="width: 80px; padding: 2px 4px; border-bottom: 1px solid #000000;">Penerima</td>
                                    <td style="padding: 2px 4px; border-bottom: 1px solid #000000;">: ${escapeHtml(customerName)}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 2px 4px; border-bottom: 1px solid #000000;">Alamat</td>
                                    <td style="padding: 2px 4px; border-bottom: 1px solid #000000;">: ${escapeHtml(customerAddress)}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 2px 4px; border-bottom: 1px solid #000000;">No HP</td>
                                    <td style="padding: 2px 4px; border-bottom: 1px solid #000000;">: ${escapeHtml(customerPhone)}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 2px 4px;">No Transaksi</td>
                                    <td style="padding: 2px 4px;">: ${escapeHtml(sale.barcode || item.receipt_number || '-')}</td>
                                </tr>
                            </table>

                            <!-- Items Table -->
                            <table style="width: 100%; border-collapse: collapse; border: 1px solid #000000; font-size: 11px;">
                                <tbody>
                                    ${itemsRowsResi}
                                </tbody>
                            </table>
                        </div>

                        <!-- Expedisi Bottom Row -->
                        <table style="width: 100%; border-collapse: collapse; border: 1px solid #000000; font-size: 11px; margin-top: 4px;">
                            <tr>
                                <td style="text-align: center; padding: 4px; font-weight: normal;">
                                    Expedisi &nbsp;&nbsp; ${escapeHtml(expeditionName)}
                                </td>
                            </tr>
                        </table>
                    </div>
                `;
            } else {
                const barcodeVal = item.receipt_number || sale.barcode || 'RESI-001';
                const canvasId = `resi-barcode-canvas-${item.id}`;

                return `
                    <div class="print-document-resi" style="width: 9cm; height: 8cm; background: #ffffff; border: none; padding: 6px; box-sizing: border-box; font-family: Arial, sans-serif; font-size: 11px; color: #000000; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <!-- No RESI Header -->
                            <div style="border: 1px solid #000000; text-align: center; padding: 4px; font-weight: bold; font-size: 12px; background-color: #ffffff;">
                                No RESI &nbsp; ${escapeHtml(barcodeVal)}
                            </div>

                            <!-- Barcode Display -->
                            <div style="border: 1px solid #000000; border-top: none; text-align: center; padding: 4px 0; background-color: #ffffff; display: flex; align-items: center; justify-content: center; min-height: 48px;">
                                <svg id="${canvasId}" style="max-height: 42px; width: 85%;"></svg>
                            </div>

                            <!-- Items Table -->
                            <table style="width: 100%; border-collapse: collapse; border: 1px solid #000000; border-top: none; font-size: 11px;">
                                <tbody>
                                    ${itemsRowsResi}
                                </tbody>
                            </table>
                        </div>

                        <!-- Marketplace & Courier Bottom Row -->
                        <table style="width: 100%; border-collapse: collapse; border: 1px solid #000000; font-size: 11px; margin-top: 4px;">
                            <tr>
                                <td style="width: 50%; border-right: 1px solid #000000; text-align: center; padding: 4px; font-weight: normal;">
                                    ${escapeHtml(marketplaceName)}
                                </td>
                                <td style="width: 50%; text-align: center; padding: 4px; font-weight: normal;">
                                    ${escapeHtml(courierName)}
                                </td>
                            </tr>
                        </table>
                    </div>
                `;
            }
        },

        renderBarcodes(items) {
            setTimeout(() => {
                items.forEach(item => {
                    if (item.type === 'marketplace') {
                        try {
                            if (window.JsBarcode) {
                                const canvasId = `#resi-barcode-canvas-${item.id}`;
                                const barcodeVal = item.receipt_number || item.sale?.barcode || 'RESI-001';
                                JsBarcode(canvasId, barcodeVal, {
                                    format: "CODE128",
                                    height: 38,
                                    displayValue: false,
                                    margin: 0
                                });
                            }
                        } catch (e) {
                            console.error("Barcode render error:", e);
                        }
                    }
                });
            }, 50);
        }
    };
</script>
@endsection


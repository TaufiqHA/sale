@extends('layouts.administrator')

@section('title', 'Invoice & Resi')

@section('content')
<div class="relative min-h-[calc(100vh-8rem)]">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-heading">Invoice & Resi Pengiriman</h1>
            <p class="text-sm text-body mt-1">Kelola dan cetak invoice (penjualan umum) serta resi pengiriman (umum & marketplace).</p>
        </div>
    </div>

    <!-- Tab Bar & Controls -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-sm mb-6 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
        <!-- Tabs -->
        <div class="flex items-center gap-2 p-1.5 bg-slate-100/80 rounded-xl shrink-0">
            <button id="tab-btn-invoice" onclick="switchTab('invoice')" class="flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg transition-all cursor-pointer bg-brand text-white shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Invoice Penjualan
                <span id="badge-invoice-count" class="px-2 py-0.5 text-xs rounded-full bg-white/20 text-white font-bold">0</span>
            </button>
            <button id="tab-btn-resi" onclick="switchTab('resi')" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-900 rounded-lg transition-all cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 2v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Resi Pengiriman
                <span id="badge-resi-count" class="px-2 py-0.5 text-xs rounded-full bg-slate-200 text-slate-700 font-bold">0</span>
            </button>
        </div>

        <!-- Search & Filters -->
        <div class="flex flex-col sm:flex-row items-center gap-3 flex-1 justify-end">
            <!-- Filter Tipe Resi (Only visible on Resi tab) -->
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

    <!-- Loading Skeleton -->
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

    <!-- Empty State -->
    <div id="empty-state" class="hidden flex-col items-center justify-center py-16 px-4 bg-white border border-slate-100 rounded-2xl shadow-sm mb-8 text-center">
        <div class="flex items-center justify-center p-4 bg-indigo-50 text-indigo-500 rounded-2xl mb-4">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3h7.5M6 20.25h12a2.25 2.25 0 002.25-2.25V8.25a2.25 2.25 0 00-.75-1.591l-5.409-5.409A2.25 2.25 0 0012.5 1.5H6A2.25 2.25 0 003.75 3.75v14.25A2.25 2.25 0 006 20.25z"></path>
            </svg>
        </div>
        <h3 id="empty-title" class="text-base font-bold text-slate-800">Tidak Ada Data Ditemukan</h3>
        <p id="empty-desc" class="text-sm text-slate-500 mt-1 max-w-sm">Invoice dan resi akan dibuat secara otomatis saat transaksi penjualan ditambahkan.</p>
    </div>

    <!-- Invoice Table Container -->
    <div id="invoice-table-wrapper" class="hidden relative overflow-x-auto bg-white shadow-sm rounded-2xl border border-slate-200/80 mb-8">
        <table class="w-full text-sm text-left text-slate-700">
            <thead class="text-xs uppercase tracking-wider text-slate-500 bg-slate-50/80 border-b border-slate-200">
                <tr>
                    <th scope="col" class="px-6 py-3.5 font-semibold">#</th>
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
                <!-- Rendered dynamically -->
            </tbody>
        </table>
    </div>

    <!-- Resi Table Container -->
    <div id="resi-table-wrapper" class="hidden relative overflow-x-auto bg-white shadow-sm rounded-2xl border border-slate-200/80 mb-8">
        <table class="w-full text-sm text-left text-slate-700">
            <thead class="text-xs uppercase tracking-wider text-slate-500 bg-slate-50/80 border-b border-slate-200">
                <tr>
                    <th scope="col" class="px-6 py-3.5 font-semibold">#</th>
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
                <!-- Rendered dynamically -->
            </tbody>
        </table>
    </div>
</div>

<!-- Preview & Print Modal -->
<div id="document-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300 opacity-0" id="modal-backdrop" onclick="closeModal()"></div>

    <div class="relative w-full max-w-2xl max-h-[90vh] flex flex-col transform scale-95 opacity-0 transition-all duration-300 z-10" id="modal-panel">
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
            <div id="printable-area" class="p-6 overflow-y-auto max-h-[60vh] space-y-6 text-slate-800 bg-white">
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

<script>
    let activeTab = 'invoice';
    let rawInvoices = [];
    let rawResis = [];
    let activeDocumentItem = null;

    document.addEventListener('DOMContentLoaded', () => {
        loadData();
    });

    async function loadData() {
        showLoading(true);
        try {
            const [invRes, rcpRes] = await Promise.all([
                fetch('/invoices', { headers: { 'Accept': 'application/json' } }),
                fetch('/recipts', { headers: { 'Accept': 'application/json' } })
            ]);

            if (invRes.ok) {
                rawInvoices = await invRes.json();
            }
            if (rcpRes.ok) {
                rawResis = await rcpRes.json();
            }

            // Update badge counts
            document.getElementById('badge-invoice-count').textContent = rawInvoices.length;
            document.getElementById('badge-resi-count').textContent = rawResis.length;

            renderActiveTab();
        } catch (error) {
            console.error('Gagal memuat data:', error);
        } finally {
            showLoading(false);
        }
    }

    function switchTab(tabName) {
        activeTab = tabName;
        const btnInvoice = document.getElementById('tab-btn-invoice');
        const btnResi = document.getElementById('tab-btn-resi');
        const badgeInv = document.getElementById('badge-invoice-count');
        const badgeResi = document.getElementById('badge-resi-count');
        const resiFilterWrapper = document.getElementById('resi-type-filter-wrapper');

        if (tabName === 'invoice') {
            btnInvoice.className = 'flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg transition-all cursor-pointer bg-brand text-white shadow-xs';
            badgeInv.className = 'px-2 py-0.5 text-xs rounded-full bg-white/20 text-white font-bold';

            btnResi.className = 'flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-900 rounded-lg transition-all cursor-pointer';
            badgeResi.className = 'px-2 py-0.5 text-xs rounded-full bg-slate-200 text-slate-700 font-bold';

            resiFilterWrapper.classList.add('hidden');
        } else {
            btnResi.className = 'flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg transition-all cursor-pointer bg-brand text-white shadow-xs';
            badgeResi.className = 'px-2 py-0.5 text-xs rounded-full bg-white/20 text-white font-bold';

            btnInvoice.className = 'flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-900 rounded-lg transition-all cursor-pointer';
            badgeInv.className = 'px-2 py-0.5 text-xs rounded-full bg-slate-200 text-slate-700 font-bold';

            resiFilterWrapper.classList.remove('hidden');
        }

        renderActiveTab();
    }

    function handleSearchChange() {
        renderActiveTab();
    }

    function handleFilterChange() {
        renderActiveTab();
    }

    function renderActiveTab() {
        const query = (document.getElementById('search-input').value || '').toLowerCase().trim();
        const invoiceWrapper = document.getElementById('invoice-table-wrapper');
        const resiWrapper = document.getElementById('resi-table-wrapper');
        const emptyState = document.getElementById('empty-state');

        if (activeTab === 'invoice') {
            resiWrapper.classList.add('hidden');
            let filtered = rawInvoices.filter(item => {
                const invNo = (item.invoice_number || '').toLowerCase();
                const barcode = (item.sale?.barcode || '').toLowerCase();
                const cust = (item.sale?.customer?.name || '').toLowerCase();
                const counter = (item.sale?.counter?.name || '').toLowerCase();
                return invNo.includes(query) || barcode.includes(query) || cust.includes(query) || counter.includes(query);
            });

            if (filtered.length === 0) {
                invoiceWrapper.classList.add('hidden');
                emptyState.classList.remove('hidden');
                emptyState.classList.add('flex');
                document.getElementById('empty-title').textContent = 'Tidak Ada Invoice';
                document.getElementById('empty-desc').textContent = 'Belum ada data invoice penjualan umum.';
            } else {
                emptyState.classList.add('hidden');
                emptyState.classList.remove('flex');
                invoiceWrapper.classList.remove('hidden');
                renderInvoiceTable(filtered);
            }
        } else {
            invoiceWrapper.classList.add('hidden');
            const typeFilter = document.getElementById('resi-type-filter').value;
            let filtered = rawResis.filter(item => {
                const resiNo = (item.receipt_number || '').toLowerCase();
                const barcode = (item.sale?.barcode || '').toLowerCase();
                const type = (item.type || '').toLowerCase();
                const cust = (item.sale?.customer?.name || '').toLowerCase();
                const mp = (item.sale?.marketplace?.name || '').toLowerCase();

                const matchesQuery = resiNo.includes(query) || barcode.includes(query) || type.includes(query) || cust.includes(query) || mp.includes(query);
                const matchesFilter = typeFilter === 'all' || item.type === typeFilter;

                return matchesQuery && matchesFilter;
            });

            if (filtered.length === 0) {
                resiWrapper.classList.add('hidden');
                emptyState.classList.remove('hidden');
                emptyState.classList.add('flex');
                document.getElementById('empty-title').textContent = 'Tidak Ada Resi';
                document.getElementById('empty-desc').textContent = 'Belum ada data resi pengiriman.';
            } else {
                emptyState.classList.add('hidden');
                emptyState.classList.remove('flex');
                resiWrapper.classList.remove('hidden');
                renderResiTable(filtered);
            }
        }
    }

    function renderInvoiceTable(data) {
        const tbody = document.getElementById('invoice-table-body');
        tbody.innerHTML = '';

        data.forEach((item, index) => {
            const tr = document.createElement('tr');
            tr.className = 'hover:bg-slate-50/80 transition-colors';

            const createdDate = item.created_at ? new Date(item.created_at).toLocaleDateString('id-ID', {
                year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit'
            }) : '-';

            const grandTotal = item.sale?.grand_total ? 'Rp ' + Number(item.sale.grand_total).toLocaleString('id-ID') : 'Rp 0';
            const customerName = item.sale?.customer?.name || item.sale?.counter?.name || 'Umum';

            tr.innerHTML = `
                <td class="px-6 py-4 text-xs font-semibold text-slate-400">${index + 1}</td>
                <td class="px-6 py-4 font-bold text-brand">${escapeHtml(item.invoice_number)}</td>
                <td class="px-6 py-4 font-mono text-xs text-slate-600">${escapeHtml(item.sale?.barcode || '-')}</td>
                <td class="px-6 py-4 font-medium text-slate-800">${escapeHtml(customerName)}</td>
                <td class="px-6 py-4 text-xs text-slate-500">${createdDate}</td>
                <td class="px-6 py-4 font-semibold text-slate-900">${grandTotal}</td>
                <td class="px-6 py-4">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium ${item.printed_count > 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200'}">
                        ${item.printed_count || 0}x Dicetak
                    </span>
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <button onclick="previewDocument('invoice', ${item.id})" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            Detail / Cetak
                        </button>
                    </div>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    function renderResiTable(data) {
        const tbody = document.getElementById('resi-table-body');
        tbody.innerHTML = '';

        data.forEach((item, index) => {
            const tr = document.createElement('tr');
            tr.className = 'hover:bg-slate-50/80 transition-colors';

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

            tr.innerHTML = `
                <td class="px-6 py-4 text-xs font-semibold text-slate-400">${index + 1}</td>
                <td class="px-6 py-4 font-bold text-brand">${escapeHtml(item.receipt_number)}</td>
                <td class="px-6 py-4 font-mono text-xs text-slate-600">${escapeHtml(item.sale?.barcode || '-')}</td>
                <td class="px-6 py-4">${typeBadge}</td>
                <td class="px-6 py-4 font-medium text-slate-800">${escapeHtml(courierExpedition)}</td>
                <td class="px-6 py-4 text-xs text-slate-500">${createdDate}</td>
                <td class="px-6 py-4">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium ${item.printed_count > 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200'}">
                        ${item.printed_count || 0}x Dicetak
                    </span>
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <button onclick="previewDocument('resi', ${item.id})" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            Detail / Cetak
                        </button>
                    </div>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    function previewDocument(type, id) {
        if (type === 'invoice') {
            activeDocumentItem = rawInvoices.find(x => x.id === id);
        } else {
            activeDocumentItem = rawResis.find(x => x.id === id);
        }

        if (!activeDocumentItem) return;

        activeDocumentItem._type = type;
        const area = document.getElementById('printable-area');
        document.getElementById('modal-title').textContent = type === 'invoice' ? 'Invoice Penjualan' : 'Resi Pengiriman';
        document.getElementById('modal-subtitle').textContent = type === 'invoice'
            ? `No. Invoice: ${activeDocumentItem.invoice_number}`
            : `No. Resi: ${activeDocumentItem.receipt_number}`;

        const sale = activeDocumentItem.sale || {};
        const items = sale.items || [];
        const createdDate = activeDocumentItem.created_at ? new Date(activeDocumentItem.created_at).toLocaleDateString('id-ID', {
            year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit'
        }) : '-';

        if (type === 'invoice') {
            let itemsHtml = items.map((item, idx) => `
                <tr class="border-b border-slate-100">
                    <td class="py-2 text-xs text-slate-500">${idx + 1}</td>
                    <td class="py-2 text-sm font-medium text-slate-800">${escapeHtml(item.product?.name || 'Produk')}</td>
                    <td class="py-2 text-sm text-center text-slate-600">${item.qty}</td>
                    <td class="py-2 text-sm text-right text-slate-600">Rp ${Number(item.price).toLocaleString('id-ID')}</td>
                    <td class="py-2 text-sm text-right font-semibold text-slate-800">Rp ${Number(item.subtotal).toLocaleString('id-ID')}</td>
                </tr>
            `).join('');

            area.innerHTML = `
                <div class="border border-slate-200 rounded-xl p-6 bg-slate-50/50">
                    <div class="flex justify-between items-start border-b border-slate-200 pb-4 mb-4">
                        <div>
                            <h2 class="text-xl font-bold text-brand">INVOICE</h2>
                            <p class="text-xs font-mono text-slate-500 mt-1">${escapeHtml(activeDocumentItem.invoice_number)}</p>
                            <p class="text-xs text-slate-400">Barcode: ${escapeHtml(sale.barcode || '-')}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-slate-400">Tanggal Transaksi</p>
                            <p class="text-sm font-semibold text-slate-700">${createdDate}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
                        <div>
                            <p class="text-xs text-slate-400 font-medium">Pelanggan</p>
                            <p class="font-bold text-slate-800">${escapeHtml(sale.customer?.name || 'Umum')}</p>
                            ${sale.customer?.phone ? `<p class="text-xs text-slate-500">${escapeHtml(sale.customer.phone)}</p>` : ''}
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-slate-400 font-medium">Metode Pembayaran</p>
                            <p class="font-bold text-slate-800 uppercase">${escapeHtml(sale.payment_method || 'Tunai')}</p>
                        </div>
                    </div>

                    <table class="w-full text-left mb-6">
                        <thead>
                            <tr class="border-b border-slate-200 text-xs font-semibold text-slate-400 uppercase">
                                <th class="py-2">#</th>
                                <th class="py-2">Item</th>
                                <th class="py-2 text-center">Qty</th>
                                <th class="py-2 text-right">Harga</th>
                                <th class="py-2 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${itemsHtml || '<tr><td colspan="5" class="py-4 text-center text-xs text-slate-400">Tidak ada detail item</td></tr>'}
                        </tbody>
                    </table>

                    <div class="border-t border-slate-200 pt-4 space-y-1.5 text-sm text-right">
                        <div class="flex justify-between text-slate-600">
                            <span>Subtotal:</span>
                            <span>Rp ${Number(sale.subtotal || 0).toLocaleString('id-ID')}</span>
                        </div>
                        ${Number(sale.discount || 0) > 0 ? `
                        <div class="flex justify-between text-rose-600">
                            <span>Diskon:</span>
                            <span>- Rp ${Number(sale.discount).toLocaleString('id-ID')}</span>
                        </div>` : ''}
                        ${Number(sale.shipping_cost || 0) > 0 ? `
                        <div class="flex justify-between text-slate-600">
                            <span>Ongkos Kirim:</span>
                            <span>Rp ${Number(sale.shipping_cost).toLocaleString('id-ID')}</span>
                        </div>` : ''}
                        <div class="flex justify-between text-lg font-bold text-slate-900 border-t border-slate-200 pt-2 mt-2">
                            <span>Grand Total:</span>
                            <span class="text-brand">Rp ${Number(sale.grand_total || 0).toLocaleString('id-ID')}</span>
                        </div>
                    </div>
                </div>
            `;
        } else {
            area.innerHTML = `
                <div class="border-2 border-dashed border-slate-300 rounded-xl p-6 bg-slate-50/50">
                    <div class="flex justify-between items-center border-b border-slate-200 pb-4 mb-4">
                        <div>
                            <span class="px-2.5 py-1 text-xs font-bold rounded-full ${activeDocumentItem.type === 'marketplace' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700'}">
                                RESI PENGIRIMAN (${activeDocumentItem.type.toUpperCase()})
                            </span>
                            <h2 class="text-2xl font-black text-slate-800 tracking-wider mt-2">${escapeHtml(activeDocumentItem.receipt_number)}</h2>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-slate-400">Barcode Penjualan</p>
                            <p class="text-sm font-mono font-bold text-slate-700">${escapeHtml(sale.barcode || '-')}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 text-sm mb-6">
                        <div class="bg-white p-3 rounded-lg border border-slate-200">
                            <p class="text-xs text-slate-400 font-medium">Penerima / Tujuan</p>
                            <p class="font-bold text-slate-800 mt-1">${escapeHtml(sale.customer?.name || sale.marketplace?.name || 'Pelanggan Umum')}</p>
                            <p class="text-xs text-slate-500 mt-0.5">${escapeHtml(sale.customer?.address || 'Alamat tidak dicantumkan')}</p>
                        </div>
                        <div class="bg-white p-3 rounded-lg border border-slate-200">
                            <p class="text-xs text-slate-400 font-medium">Kurir / Ekspedisi</p>
                            <p class="font-bold text-slate-800 mt-1">${escapeHtml(sale.expedition?.name || sale.courier?.name || 'Pengiriman Langsung')}</p>
                            <p class="text-xs text-slate-500 mt-0.5">Tanggal: ${createdDate}</p>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-lg border border-slate-200">
                        <p class="text-xs font-bold text-slate-400 uppercase mb-2">Ringkasan Barang</p>
                        <ul class="text-sm divide-y divide-slate-100">
                            ${items.map(i => `<li class="py-1.5 flex justify-between"><span>${escapeHtml(i.product?.name || 'Item')}</span><span class="font-semibold text-slate-700">x${i.qty}</span></li>`).join('')}
                        </ul>
                    </div>
                </div>
            `;
        }

        openModal();
    }

    async function executePrint() {
        if (!activeDocumentItem) return;

        const type = activeDocumentItem._type;
        const id = activeDocumentItem.id;
        const updatedCount = (activeDocumentItem.printed_count || 0) + 1;

        // Perform API update to increment printed_count
        const endpoint = type === 'invoice' ? `/invoices/${id}` : `/recipts/${id}`;
        try {
            await fetch(endpoint, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    sales_id: activeDocumentItem.sales_id,
                    [type === 'invoice' ? 'invoice_number' : 'receipt_number']: type === 'invoice' ? activeDocumentItem.invoice_number : activeDocumentItem.receipt_number,
                    type: activeDocumentItem.type,
                    printed_count: updatedCount
                })
            });
        } catch (err) {
            console.error('Gagal memperbarui printed_count:', err);
        }

        // Trigger native print window
        window.print();

        closeModal();
        loadData();
    }

    function openModal() {
        const modal = document.getElementById('document-modal');
        const backdrop = document.getElementById('modal-backdrop');
        const panel = document.getElementById('modal-panel');

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

    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }
</script>
@endsection

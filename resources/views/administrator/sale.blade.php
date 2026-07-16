@extends('layouts.administrator')

@section('title', 'Sales Management')

@section('content')
<div class="relative min-h-[calc(100vh-8rem)]">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <!-- Search Form -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 flex-1 max-w-md">
            <form id="search-form" onsubmit="event.preventDefault(); handleSearchChange();" class="relative flex-1">
                <label for="search-input" class="block mb-2.5 text-sm font-medium text-heading sr-only">Search</label>
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-body" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/></svg>
                    </div>
                    <input type="search" id="search-input" oninput="handleSearchChange()" class="block w-full p-3 ps-9 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand shadow-xs placeholder:text-body" placeholder="Search Sales (Barcode, Customer, Counter...)" />
                </div>
            </form>
        </div>

        <button onclick="openAddModal()" class="inline-flex items-center justify-center text-white bg-brand box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none cursor-pointer shrink-0 gap-1.5">
            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7 7V5"/>
            </svg>
            Add Sale
        </button>
    </div>

    <!-- Loading State (Table Skeleton) -->
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
        <h3 class="text-base font-bold text-slate-800">No Sales Created</h3>
        <button onclick="openAddModal()" class="mt-4 inline-flex items-center justify-center text-white bg-brand box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none cursor-pointer gap-1.5">
            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7 7V5"/>
            </svg>
            Create Sale
        </button>
    </div>

    <!-- Sales Table -->
    <div id="sales-table-wrapper" class="hidden relative overflow-x-auto bg-neutral-primary-soft shadow-xs rounded-base border border-default mb-8">
        <table class="w-full text-sm text-left rtl:text-right text-body">
            <thead class="text-sm text-body bg-neutral-secondary-medium border-b border-default-medium">
                <tr>
                    <th scope="col" class="px-6 py-3 font-medium">ID</th>
                    <th scope="col" class="px-6 py-3 font-medium">Counter</th>
                    <th scope="col" class="px-6 py-3 font-medium">Customer</th>
                    <th scope="col" class="px-6 py-3 font-medium">Barcode</th>
                    <th scope="col" class="px-6 py-3 font-medium">Type</th>
                    <th scope="col" class="px-6 py-3 font-medium">Date</th>
                    <th scope="col" class="px-6 py-3 font-medium">Grand Total</th>
                    <th scope="col" class="px-6 py-3 font-medium">Payment</th>
                    <th scope="col" class="px-6 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody id="sales-table-body">
                <!-- Rendered dynamically -->
            </tbody>
        </table>
    </div>
</div>

<!-- Modal (Create / Edit) -->
<div id="sale-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden" role="dialog" aria-modal="true">
    <!-- Backdrop with blur -->
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300 opacity-0" id="modal-backdrop" onclick="closeModal()"></div>

    <!-- Modal content wrapper -->
    <div class="relative w-full max-w-2xl max-h-[95vh] flex flex-col transform scale-95 opacity-0 transition-all duration-300" id="modal-panel">
        <div class="relative bg-neutral-primary-soft border border-default rounded-base shadow-sm p-4 md:p-6 flex flex-col overflow-hidden max-h-full">
            <!-- Modal header -->
            <div class="flex items-center justify-between border-b border-default pb-4 md:pb-5 shrink-0">
                <h3 id="modal-title" class="text-lg font-medium text-heading">
                    Add Sale
                </h3>
                <button type="button" onclick="closeModal()" class="text-body bg-transparent hover:bg-neutral-tertiary hover:text-heading rounded-base text-sm w-9 h-9 ms-auto inline-flex justify-center items-center cursor-pointer">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/></svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <form id="sale-form" onsubmit="handleFormSubmit(event)" class="overflow-y-auto flex-1 pr-1">
                <input type="hidden" id="sale-id" name="id">

                <div class="grid gap-4 grid-cols-2 py-4 md:py-6">
                    <!-- Counter Select -->
                    <div class="col-span-2 sm:col-span-1">
                        <label for="input-counter-id" class="block mb-2.5 text-sm font-medium text-heading">Counter</label>
                        <select id="input-counter-id" name="counter_id" class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs" required>
                            <option value="">Select Counter...</option>
                            @foreach($counters as $counter)
                                @if($counter->status)
                                    <option value="{{ $counter->id }}">{{ $counter->name }}</option>
                                @endif
                            @endforeach
                        </select>
                        <p id="error-counter_id" class="mt-2 text-xs font-medium text-rose-500 hidden"></p>
                    </div>

                    <!-- Customer Select (Nullable) -->
                    <div class="col-span-2 sm:col-span-1">
                        <label for="input-customer-id" class="block mb-2.5 text-sm font-medium text-heading">Customer</label>
                        <select id="input-customer-id" name="customer_id" class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs">
                            <option value="">None (Umum)</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                        <p id="error-customer_id" class="mt-2 text-xs font-medium text-rose-500 hidden"></p>
                    </div>

                    <!-- Barcode -->
                    <div class="col-span-2 sm:col-span-1">
                        <label for="input-barcode" class="block mb-2.5 text-sm font-medium text-heading">Barcode</label>
                        <input type="text" id="input-barcode" name="barcode" class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body" placeholder="Barcode / SKU" required>
                        <p id="error-barcode" class="mt-2 text-xs font-medium text-rose-500 hidden"></p>
                    </div>

                    <!-- Date -->
                    <div class="col-span-2 sm:col-span-1">
                        <label for="input-date" class="block mb-2.5 text-sm font-medium text-heading">Date</label>
                        <input type="datetime-local" id="input-date" name="date" class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs" required>
                        <p id="error-date" class="mt-2 text-xs font-medium text-rose-500 hidden"></p>
                    </div>

                    <!-- Type (Enum: umum, marketplace) -->
                    <div class="col-span-2 sm:col-span-1">
                        <label for="input-type" class="block mb-2.5 text-sm font-medium text-heading">Type</label>
                        <select id="input-type" name="type" onchange="toggleMarketplaceFields()" class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs" required>
                            <option value="umum">Umum</option>
                            <option value="marketplace">Marketplace</option>
                        </select>
                        <p id="error-type" class="mt-2 text-xs font-medium text-rose-500 hidden"></p>
                    </div>

                    <!-- Payment Method (Enum: tunai, transfer, compliment) -->
                    <div class="col-span-2 sm:col-span-1">
                        <label for="input-payment-method" class="block mb-2.5 text-sm font-medium text-heading">Payment Method</label>
                        <select id="input-payment-method" name="payment_method" class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs" required>
                            <option value="tunai">Tunai</option>
                            <option value="transfer">Transfer</option>
                            <option value="compliment">Compliment</option>
                        </select>
                        <p id="error-payment_method" class="mt-2 text-xs font-medium text-rose-500 hidden"></p>
                    </div>

                    <!-- Marketplace (Only if type is marketplace) -->
                    <div id="marketplace-field-wrapper" class="col-span-2 sm:col-span-1 hidden">
                        <label for="input-marketplace-id" class="block mb-2.5 text-sm font-medium text-heading">Marketplace</label>
                        <select id="input-marketplace-id" name="marketplace_id" class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs">
                            <option value="">Select Marketplace...</option>
                            @foreach($marketplaces as $marketplace)
                                <option value="{{ $marketplace->id }}">{{ $marketplace->name }}</option>
                            @endforeach
                        </select>
                        <p id="error-marketplace_id" class="mt-2 text-xs font-medium text-rose-500 hidden"></p>
                    </div>

                    <!-- Expedition (Nullable) -->
                    <div class="col-span-2 sm:col-span-1">
                        <label for="input-expedition-id" class="block mb-2.5 text-sm font-medium text-heading">Expedition</label>
                        <select id="input-expedition-id" name="expedition_id" class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs">
                            <option value="">None</option>
                            @foreach($expeditions as $expedition)
                                <option value="{{ $expedition->id }}">{{ $expedition->name }}</option>
                            @endforeach
                        </select>
                        <p id="error-expedition_id" class="mt-2 text-xs font-medium text-rose-500 hidden"></p>
                    </div>

                    <!-- Courier (Nullable) -->
                    <div class="col-span-2 sm:col-span-1">
                        <label for="input-courier-id" class="block mb-2.5 text-sm font-medium text-heading">Courier / Ekspedisi</label>
                        <select id="input-courier-id" name="courier_id" class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs">
                            <option value="">None</option>
                            @foreach($couriers as $courier)
                                <option value="{{ $courier->id }}">{{ $courier->name }} ({{ $courier->type }})</option>
                            @endforeach
                        </select>
                        <p id="error-courier_id" class="mt-2 text-xs font-medium text-rose-500 hidden"></p>
                    </div>

                    <div class="col-span-2 border-t border-default my-2"></div>

                    <!-- Subtotal -->
                    <div class="col-span-2 sm:col-span-1">
                        <label for="input-subtotal" class="block mb-2.5 text-sm font-medium text-heading">Subtotal</label>
                        <input type="number" id="input-subtotal" name="subtotal" step="0.01" min="0" oninput="calculateGrandTotal()" class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body font-semibold text-slate-800" placeholder="0.00" required>
                        <p id="error-subtotal" class="mt-2 text-xs font-medium text-rose-500 hidden"></p>
                    </div>

                    <!-- Discount -->
                    <div class="col-span-2 sm:col-span-1">
                        <label for="input-discount" class="block mb-2.5 text-sm font-medium text-heading">Discount</label>
                        <input type="number" id="input-discount" name="discount" step="0.01" min="0" oninput="calculateGrandTotal()" class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body font-semibold text-rose-700" placeholder="0.00" value="0.00">
                        <p id="error-discount" class="mt-2 text-xs font-medium text-rose-500 hidden"></p>
                    </div>

                    <!-- Grand Total -->
                    <div class="col-span-2">
                        <label for="input-grand-total" class="block mb-2.5 text-sm font-medium text-heading">Grand Total</label>
                        <input type="number" id="input-grand-total" name="grand_total" step="0.01" min="0" readonly class="bg-slate-100 border border-default-medium text-heading text-lg font-bold rounded-base block w-full px-3 py-3 shadow-xs text-brand" placeholder="0.00" required>
                        <p id="error-grand_total" class="mt-2 text-xs font-medium text-rose-500 hidden"></p>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex items-center space-x-4 border-t border-default pt-4 md:pt-6">
                    <button type="submit" id="btn-save" class="inline-flex items-center text-white bg-brand hover:bg-brand-strong box-border border border-transparent focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none cursor-pointer">
                        <svg class="w-4 h-4 me-1.5 -ms-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7 7V5"/></svg>
                        Save Sale
                    </button>
                    <button type="button" onclick="closeModal()" class="text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none cursor-pointer">Cancel</button>
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
                <h3 class="text-base font-bold text-slate-800">Delete Sale</h3>
                <p class="text-sm text-slate-500 mt-1">Are you sure you want to delete sale with Barcode <span id="delete-sale-barcode" class="font-semibold text-slate-700"></span>? This action cannot be undone.</p>
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

<!-- Toast System -->
<div id="toast-container" class="fixed top-6 right-6 z-50 flex flex-col gap-3 pointer-events-none"></div>

<!-- JavaScript Logic -->
<script>
    const csrfToken = "{{ csrf_token() }}";
    let activeSales = [];
    let saleToDelete = null;

    document.addEventListener("DOMContentLoaded", () => {
        fetchSales();
    });

    async function fetchSales() {
        const skeleton = document.getElementById("loading-skeleton");
        const emptyState = document.getElementById("empty-state");
        const tableWrapper = document.getElementById("sales-table-wrapper");

        skeleton.classList.remove("hidden");
        tableWrapper.classList.add("hidden");
        emptyState.classList.add("hidden");

        try {
            const response = await fetch("/sales", {
                headers: {
                    "Accept": "application/json",
                }
            });

            if (!response.ok) throw new Error("Failed to fetch sales.");

            activeSales = await response.json();
            renderSales();
        } catch (error) {
            showToast("Failed loading sales. Please try again.", "error");
        } finally {
            skeleton.classList.add("hidden");
        }
    }

    function formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(amount);
    }

    function renderSales() {
        const emptyState = document.getElementById("empty-state");
        const tableWrapper = document.getElementById("sales-table-wrapper");
        const tbody = document.getElementById("sales-table-body");

        tbody.innerHTML = "";

        const searchInput = document.getElementById("search-input");
        const searchQuery = searchInput ? searchInput.value.toLowerCase().trim() : "";

        // Filter sales
        const filteredSales = activeSales.filter(sale => {
            if (searchQuery) {
                const barcodeMatch = sale.barcode ? sale.barcode.toLowerCase().includes(searchQuery) : false;
                const counterMatch = (sale.counter && sale.counter.name) ? sale.counter.name.toLowerCase().includes(searchQuery) : false;
                const customerMatch = (sale.customer && sale.customer.name) ? sale.customer.name.toLowerCase().includes(searchQuery) : false;
                const paymentMatch = sale.payment_method ? sale.payment_method.toLowerCase().includes(searchQuery) : false;
                const typeMatch = sale.type ? sale.type.toLowerCase().includes(searchQuery) : false;
                if (!barcodeMatch && !counterMatch && !customerMatch && !paymentMatch && !typeMatch) {
                    return false;
                }
            }
            return true;
        });

        if (activeSales.length === 0) {
            emptyState.classList.remove("hidden");
            tableWrapper.classList.add("hidden");
            return;
        }

        emptyState.classList.add("hidden");
        tableWrapper.classList.remove("hidden");

        if (filteredSales.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="9" class="px-6 py-12 text-center text-body opacity-60">
                        <div class="flex flex-col items-center justify-center gap-2">
                            <svg class="w-8 h-8 opacity-40 text-body" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <span class="text-sm font-semibold">No sales match your criteria.</span>
                        </div>
                    </td>
                </tr>
            `;
            return;
        }

        filteredSales.forEach(sale => {
            const row = document.createElement("tr");
            row.className = "bg-neutral-primary-soft border-b border-default hover:bg-neutral-secondary-medium transition-colors duration-150";

            const counterName = sale.counter ? escapeHtml(sale.counter.name) : '<span class="text-body opacity-50 font-normal">None</span>';
            const customerName = sale.customer ? escapeHtml(sale.customer.name) : '<span class="text-body opacity-50 font-normal">None (Umum)</span>';
            
            // Format date
            let formattedDate = '-';
            if (sale.date) {
                const d = new Date(sale.date);
                formattedDate = d.toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' });
            }

            // Badges for Type
            const typeBadge = sale.type === 'marketplace'
                ? `<span class="px-2.5 py-1 text-xs font-semibold text-indigo-700 bg-indigo-50 rounded-full">Marketplace</span>`
                : `<span class="px-2.5 py-1 text-xs font-semibold text-slate-700 bg-slate-100 rounded-full">Umum</span>`;

            // Badges for Payment
            let paymentBadge = `<span class="px-2.5 py-1 text-xs font-semibold text-slate-700 bg-slate-100 rounded-full">${escapeHtml(sale.payment_method)}</span>`;
            if (sale.payment_method === 'tunai') {
                paymentBadge = `<span class="px-2.5 py-1 text-xs font-semibold text-emerald-700 bg-emerald-50 rounded-full">Tunai</span>`;
            } else if (sale.payment_method === 'transfer') {
                paymentBadge = `<span class="px-2.5 py-1 text-xs font-semibold text-blue-700 bg-blue-50 rounded-full">Transfer</span>`;
            } else if (sale.payment_method === 'compliment') {
                paymentBadge = `<span class="px-2.5 py-1 text-xs font-semibold text-amber-700 bg-amber-50 rounded-full">Compliment</span>`;
            }

            row.innerHTML = `
                <td class="px-6 py-4 font-semibold text-body">#${sale.id}</td>
                <td class="px-6 py-4 font-medium text-heading">${counterName}</td>
                <td class="px-6 py-4 font-medium text-heading">${customerName}</td>
                <td class="px-6 py-4 text-xs font-semibold text-body">${escapeHtml(sale.barcode)}</td>
                <td class="px-6 py-4">${typeBadge}</td>
                <td class="px-6 py-4 text-xs text-body">${formattedDate}</td>
                <td class="px-6 py-4 font-bold text-slate-800">${formatCurrency(sale.grand_total)}</td>
                <td class="px-6 py-4">${paymentBadge}</td>
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end gap-3">
                        <button onclick="openEditModal(${sale.id})" class="font-medium text-fg-brand hover:underline cursor-pointer" title="Edit">Edit</button>
                        <button onclick="openDeleteModal(${sale.id}, '${escapeQuote(sale.barcode)}')" class="font-medium text-fg-danger hover:underline cursor-pointer" title="Delete">Delete</button>
                    </div>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    function handleSearchChange() {
        renderSales();
    }

    function toggleMarketplaceFields() {
        const typeSelect = document.getElementById("input-type");
        const wrapper = document.getElementById("marketplace-field-wrapper");
        if (typeSelect.value === "marketplace") {
            wrapper.classList.remove("hidden");
        } else {
            wrapper.classList.add("hidden");
            document.getElementById("input-marketplace-id").value = "";
        }
    }

    function calculateGrandTotal() {
        const subtotal = parseFloat(document.getElementById("input-subtotal").value) || 0;
        const discount = parseFloat(document.getElementById("input-discount").value) || 0;
        const grandTotalInput = document.getElementById("input-grand-total");
        
        const grandTotal = Math.max(0, subtotal - discount);
        grandTotalInput.value = grandTotal.toFixed(2);
    }

    // Modal Helpers
    function openAddModal() {
        document.getElementById("modal-title").innerText = "Add New Sale";
        document.getElementById("sale-id").value = "";
        document.getElementById("sale-form").reset();

        // Default date to now
        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        document.getElementById("input-date").value = now.toISOString().slice(0, 16);
        document.getElementById("input-discount").value = "0.00";

        toggleMarketplaceFields();
        calculateGrandTotal();
        clearValidationErrors();
        showModal();
    }

    function openEditModal(id) {
        const sale = activeSales.find(s => s.id === id);
        if (!sale) return;

        document.getElementById("modal-title").innerText = "Edit Sale";
        document.getElementById("sale-id").value = sale.id;
        document.getElementById("input-counter-id").value = sale.counter_id;
        document.getElementById("input-customer-id").value = sale.customer_id || "";
        document.getElementById("input-barcode").value = sale.barcode;
        
        // Format date to local datetime string
        if (sale.date) {
            const d = new Date(sale.date);
            d.setMinutes(d.getMinutes() - d.getTimezoneOffset());
            document.getElementById("input-date").value = d.toISOString().slice(0, 16);
        } else {
            document.getElementById("input-date").value = "";
        }

        document.getElementById("input-type").value = sale.type;
        document.getElementById("input-payment-method").value = sale.payment_method;
        document.getElementById("input-marketplace-id").value = sale.marketplace_id || "";
        document.getElementById("input-expedition-id").value = sale.expedition_id || "";
        document.getElementById("input-courier-id").value = sale.courier_id || "";
        document.getElementById("input-subtotal").value = parseFloat(sale.subtotal).toFixed(2);
        document.getElementById("input-discount").value = parseFloat(sale.discount).toFixed(2);
        document.getElementById("input-grand-total").value = parseFloat(sale.grand_total).toFixed(2);

        toggleMarketplaceFields();
        clearValidationErrors();
        showModal();
    }

    function showModal() {
        const modal = document.getElementById("sale-modal");
        const backdrop = document.getElementById("modal-backdrop");
        const panel = document.getElementById("modal-panel");

        modal.classList.remove("hidden");
        setTimeout(() => {
            backdrop.classList.replace("opacity-0", "opacity-100");
            panel.classList.replace("scale-95", "scale-100");
            panel.classList.replace("opacity-0", "opacity-100");
        }, 10);
    }

    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") {
            closeModal();
            closeDeleteModal();
        }
    });

    function closeModal() {
        const modal = document.getElementById("sale-modal");
        const backdrop = document.getElementById("modal-backdrop");
        const panel = document.getElementById("modal-panel");

        backdrop.classList.replace("opacity-100", "opacity-0");
        panel.classList.replace("scale-100", "scale-95");
        panel.classList.replace("opacity-100", "opacity-0");

        setTimeout(() => {
            modal.classList.add("hidden");
        }, 300);
    }

    // Form Handling
    async function handleFormSubmit(event) {
        event.preventDefault();
        clearValidationErrors();

        const id = document.getElementById("sale-id").value;
        const counter_id = document.getElementById("input-counter-id").value;
        const customer_id = document.getElementById("input-customer-id").value || null;
        const expedition_id = document.getElementById("input-expedition-id").value || null;
        const barcode = document.getElementById("input-barcode").value;
        const date = document.getElementById("input-date").value;
        const type = document.getElementById("input-type").value;
        const payment_method = document.getElementById("input-payment-method").value;
        const marketplace_id = document.getElementById("input-marketplace-id").value || null;
        const courier_id = document.getElementById("input-courier-id").value || null;
        const subtotal = document.getElementById("input-subtotal").value;
        const discount = document.getElementById("input-discount").value || 0;
        const grand_total = document.getElementById("input-grand-total").value;

        const data = {
            counter_id,
            customer_id,
            expedition_id,
            barcode,
            date,
            type,
            payment_method,
            marketplace_id,
            courier_id,
            subtotal,
            discount,
            grand_total
        };

        const isEdit = !!id;
        const url = isEdit ? `/sales/${id}` : "/sales";
        const method = isEdit ? "PUT" : "POST";

        const btnSave = document.getElementById("btn-save");
        const originalBtnText = btnSave.innerHTML;
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

            if (!response.ok) throw new Error("Could not save sale details.");

            showToast(`Sale successfully ${isEdit ? 'updated' : 'created'}!`, "success");
            closeModal();
            fetchSales();
        } catch (error) {
            showToast(error.message, "error");
        } finally {
            btnSave.disabled = false;
            btnSave.innerHTML = originalBtnText;
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
    }

    // Clear Errors
    function clearValidationErrors() {
        const errorElements = document.querySelectorAll("[id^='error-']");
        errorElements.forEach(el => {
            el.innerText = "";
            el.classList.add("hidden");
        });

        const inputs = document.querySelectorAll("#sale-form input, #sale-form select");
        inputs.forEach(input => {
            input.classList.remove("border-rose-500", "focus:border-rose-500", "focus:ring-rose-500/10");
        });
    }

    // Delete Flow
    function openDeleteModal(id, barcode) {
        saleToDelete = id;
        document.getElementById("delete-sale-barcode").innerText = barcode;

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
            saleToDelete = null;
        }, 300);
    }

    async function handleDeleteConfirm() {
        if (!saleToDelete) return;

        const btnDelete = document.getElementById("btn-delete");
        const originalText = btnDelete.innerText;
        btnDelete.disabled = true;
        btnDelete.innerText = "Deleting...";

        try {
            const response = await fetch(`/sales/${saleToDelete}`, {
                method: "DELETE",
                headers: {
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                }
            });

            if (!response.ok) throw new Error("Failed to delete sale.");

            showToast("Sale successfully deleted.", "success");
            closeDeleteModal();
            fetchSales();
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

        // Trigger entrance
        setTimeout(() => {
            toast.classList.replace("translate-y-2", "translate-y-0");
            toast.classList.replace("opacity-0", "opacity-100");
        }, 10);

        // Auto removal
        setTimeout(() => {
            toast.classList.replace("translate-y-0", "translate-y-2");
            toast.classList.replace("opacity-100", "opacity-0");
            setTimeout(() => toast.remove(), 300);
        }, 4000);
    }

    // Utilities
    function escapeHtml(str) {
        if (!str) return '';
        return String(str).replace(/&/g, "&amp;")
                          .replace(/</g, "&lt;")
                          .replace(/>/g, "&gt;")
                          .replace(/"/g, "&quot;")
                          .replace(/'/g, "&#039;");
    }

    function escapeQuote(str) {
        if (!str) return '';
        return String(str).replace(/'/g, "\\'");
    }
</script>
@endsection

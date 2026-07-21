@extends('layouts.administrator')

@section('title', 'Tambah Penjualan')

@section('content')
<div class="max-w-5xl mx-auto">

    <!-- Main Card Container -->
    <div class="bg-neutral-primary-soft border border-default rounded-base shadow-sm p-4 md:p-6">
        <form id="sale-form" onsubmit="handleFormSubmit(event)">
            <!-- Header fields grid -->
            <div class="grid gap-4 grid-cols-3 py-4 md:py-6">
                <!-- Pilih Counter -->
                <div class="col-span-3 md:col-span-1">
                    <label for="input-counter-id" class="block mb-2 text-sm font-medium text-heading">Pilih Counter</label>
                    <select id="input-counter-id" name="counter_id" onchange="onCounterChange()" class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs" required>
                        <option value="">Pilih Counter...</option>
                        @foreach($counters as $counter)
                            @if($counter->status)
                                <option value="{{ $counter->id }}">{{ $counter->name }}</option>
                            @endif
                        @endforeach
                    </select>
                    <p id="error-counter_id" class="mt-2 text-xs font-medium text-rose-500 hidden"></p>
                </div>

                <!-- Tanggal -->
                <div class="col-span-3 md:col-span-1">
                    <label for="input-date" class="block mb-2 text-sm font-medium text-heading">Tanggal</label>
                    <input type="datetime-local" id="input-date" name="date" class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs" required>
                    <p id="error-date" class="mt-2 text-xs font-medium text-rose-500 hidden"></p>
                </div>

                <!-- Type (Enum: umum, marketplace) -->
                <div class="col-span-3 md:col-span-1">
                    <label for="input-type" class="block mb-2 text-sm font-medium text-heading">Tipe</label>
                    <select id="input-type" name="type" onchange="onTypeChange()" class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs" required>
                        <option value="umum">UMUM</option>
                        <option value="marketplace">MARKETPLACE</option>
                    </select>
                    <p id="error-type" class="mt-2 text-xs font-medium text-rose-500 hidden"></p>
                </div>

                <!-- UMUM fields wrapper -->
                <div id="umum-fields-wrapper" class="col-span-3 grid grid-cols-2 gap-4">
                    <!-- Cari Data Pelanggan -->
                    <div class="col-span-2 md:col-span-1">
                        <label for="input-customer-id" class="block mb-2 text-sm font-medium text-heading">Cari Data Pelanggan</label>
                        <div class="flex gap-2">
                            <select id="input-customer-id" name="customer_id" onchange="onCustomerChange()" class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs">
                                <option value="">Pilih Pelanggan...</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" data-counter-id="{{ $customer->counter_id }}" data-phone="{{ $customer->phone }}" data-address="{{ $customer->address }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                            <button type="button" id="btn-manage-customers" onclick="openManageCustomers()" class="inline-flex items-center justify-center text-white bg-brand hover:bg-brand-hover px-3 py-2 sm:px-4 sm:py-2.5 rounded-base text-xs sm:text-sm font-medium focus:outline-none cursor-pointer shrink-0">
                                Kelola
                            </button>
                        </div>
                        <p id="error-customer_id" class="mt-2 text-xs font-medium text-rose-500 hidden"></p>

                        <!-- Customer details preview boxes -->
                        <div id="customer-details-preview" class="mt-3 space-y-2 hidden">
                            <div id="customer-preview-phone" class="p-2.5 bg-slate-800/40 text-heading text-xs rounded-base border border-slate-700/50 font-medium"></div>
                            <div id="customer-preview-address" class="p-2.5 bg-slate-800/40 text-heading text-xs rounded-base border border-slate-700/50 font-medium"></div>
                        </div>
                    </div>

                    <!-- Expedition Select -->
                    <div class="col-span-2 md:col-span-1">
                        <label for="input-expedition-id" class="block mb-2 text-sm font-medium text-heading">Ekspedisi</label>
                        <div class="flex gap-2">
                            <select id="input-expedition-id" name="expedition_id" class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs">
                                <option value="">Pilih Ekspedisi...</option>
                                @foreach($expeditions as $expedition)
                                    <option value="{{ $expedition->id }}">{{ $expedition->name }}</option>
                                @endforeach
                            </select>
                            <button type="button" onclick="openManageExpeditions()" class="inline-flex items-center justify-center text-white bg-brand hover:bg-brand-hover px-3 py-2 sm:px-4 sm:py-2.5 rounded-base text-xs sm:text-sm font-medium focus:outline-none cursor-pointer shrink-0">
                                Kelola
                            </button>
                        </div>
                        <p id="error-expedition_id" class="mt-2 text-xs font-medium text-rose-500 hidden"></p>
                    </div>
                </div>

                <!-- MARKETPLACE fields wrapper -->
                <div id="marketplace-fields-wrapper" class="col-span-3 grid grid-cols-2 gap-4 hidden">
                    <!-- Left Side: Barcode -->
                    <div class="col-span-2 md:col-span-1 flex flex-col justify-between">
                        <div>
                            <label for="input-barcode" class="block mb-2 text-sm font-medium text-heading">Masukkan Kode Barcode</label>
                            <input type="text" id="input-barcode" name="barcode" oninput="onBarcodeChange()" class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body" placeholder="Masukkan Barcode">
                            <p id="error-barcode" class="mt-2 text-xs font-medium text-rose-500 hidden"></p>
                        </div>

                        <!-- Barcode Visual Preview -->
                        <div id="barcode-visual-preview" class="mt-3 flex flex-col items-center p-3 bg-white rounded-base border border-slate-200 hidden">
                            <div id="barcode-preview-text" class="text-xs font-bold text-slate-800 tracking-widest mb-1"></div>
                            <div id="barcode-preview-graphic" class="text-slate-900 w-full flex justify-center"></div>
                        </div>
                    </div>

                    <!-- Right Side: Marketplace & Courier -->
                    <div class="col-span-2 md:col-span-1 space-y-4">
                        <!-- Marketplace Select -->
                        <div>
                            <label for="input-marketplace-id" class="block mb-2 text-sm font-medium text-heading">Marketplace</label>
                            <div class="flex gap-2">
                                <select id="input-marketplace-id" name="marketplace_id" class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs">
                                    <option value="">Pilih Marketplace...</option>
                                    @foreach($marketplaces as $marketplace)
                                        <option value="{{ $marketplace->id }}">{{ $marketplace->name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" onclick="openManageMarketplaces()" class="inline-flex items-center justify-center text-white bg-brand hover:bg-brand-hover px-3 py-2 sm:px-4 sm:py-2.5 rounded-base text-xs sm:text-sm font-medium focus:outline-none cursor-pointer shrink-0">
                                    Kelola
                                </button>
                            </div>
                            <p id="error-marketplace_id" class="mt-2 text-xs font-medium text-rose-500 hidden"></p>
                        </div>

                        <!-- Courier Select -->
                        <div>
                            <label for="input-courier-id" class="block mb-2 text-sm font-medium text-heading">Kurir</label>
                            <div class="flex gap-2">
                                <select id="input-courier-id" name="courier_id" class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs">
                                    <option value="">Pilih Kurir...</option>
                                    @foreach($couriers as $courier)
                                        <option value="{{ $courier->id }}">{{ $courier->name }} ({{ $courier->type }})</option>
                                    @endforeach
                                </select>
                                <button type="button" onclick="openManageCouriers()" class="inline-flex items-center justify-center text-white bg-brand hover:bg-brand-hover px-3 py-2 sm:px-4 sm:py-2.5 rounded-base text-xs sm:text-sm font-medium focus:outline-none cursor-pointer shrink-0">
                                    Kelola
                                </button>
                            </div>
                            <p id="error-courier_id" class="mt-2 text-xs font-medium text-rose-500 hidden"></p>
                        </div>
                    </div>
                </div>

                <!-- SALE ITEMS SECTION -->
                <div class="col-span-3 border-t border-default my-2"></div>
                <div class="col-span-3">
                    <h4 class="text-sm font-bold text-heading mb-3 uppercase tracking-wider">ITEM PENJUALAN</h4>
                    
                    <!-- Item input row -->
                    <div class="grid grid-cols-12 gap-3 items-end bg-neutral-secondary-medium p-3 rounded-base mb-4 border border-default-medium">
                        <div class="col-span-12 md:col-span-6">
                            <label for="select-product-id" class="block mb-2 text-xs font-semibold text-heading">-- Pilih Produk --</label>
                            <select id="select-product-id" onchange="onProductChange()" class="bg-neutral-primary-soft border border-default text-heading text-sm rounded-base block w-full px-2.5 py-2">
                                <option value="">-- Pilih Produk --</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->sell_price }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-3 md:col-span-2">
                            <label for="input-item-qty" class="block mb-2 text-xs font-semibold text-heading">Qty</label>
                            <input type="number" id="input-item-qty" min="1" step="1" value="1" oninput="calculateItemSubtotal()" class="bg-neutral-primary-soft border border-default text-heading text-sm rounded-base block w-full px-2.5 py-2 text-center">
                        </div>
                        <div class="col-span-6 md:col-span-3">
                            <label for="input-item-price" class="block mb-2 text-xs font-semibold text-heading">Harga</label>
                            <input type="text" id="input-item-price" oninput="onPriceInput(this)" readonly class="bg-slate-100 border border-default text-heading text-sm rounded-base block w-full px-2.5 py-2 text-right cursor-not-allowed">
                        </div>
                        <div class="col-span-3 md:col-span-1 text-right">
                            <button type="button" onclick="addSaleItem()" class="w-full inline-flex items-center justify-center p-2 bg-brand text-white hover:bg-brand-strong rounded-base font-bold cursor-pointer h-[38px]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                            </button>
                        </div>

                        <!-- Dynamic Wholeprice Radio Options Container -->
                        <div id="item-wholeprice-wrapper" class="col-span-12 hidden bg-neutral-primary-soft p-3 rounded-lg border border-default-medium mt-1">
                            <div class="text-xs font-semibold text-heading mb-2">Pilihan Tipe Harga:</div>
                            <div class="flex flex-wrap gap-4">
                                <label class="inline-flex items-center cursor-pointer text-xs text-body font-medium">
                                    <input type="radio" id="price-type-normal" name="price_type" value="normal" checked onchange="onPriceTypeChange('normal')" class="mr-2 text-brand focus:ring-brand">
                                    Harga Normal (<span id="normal-price-label">Rp 0</span>)
                                </label>
                                <label class="inline-flex items-center cursor-pointer text-xs text-body font-medium">
                                    <input type="radio" id="price-type-wholeprice" name="price_type" value="wholeprice" onchange="onPriceTypeChange('wholeprice')" class="mr-2 text-brand focus:ring-brand">
                                    Harga Grosir
                                </label>
                            </div>
                            <div id="wholeprice-tiers-selection" class="hidden mt-3 pl-4 border-l-2 border-slate-200 flex flex-col gap-2">
                                <!-- Tiers dynamically rendered here -->
                            </div>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <div class="overflow-x-auto border border-default rounded-base max-h-64 overflow-y-auto">
                        <table class="w-full min-w-[600px] md:min-w-full text-xs text-left text-body">
                            <thead class="bg-neutral-secondary-medium text-heading uppercase font-semibold border-b border-default">
                                <tr>
                                    <th class="px-4 py-2 w-16 text-center">Gambar</th>
                                    <th class="px-4 py-2">Produk</th>
                                    <th class="px-4 py-2 text-center w-20">Qty</th>
                                    <th class="px-4 py-2 text-right w-28">Harga</th>
                                    <th class="px-4 py-2 text-right w-28">Subtotal</th>
                                    <th class="px-4 py-2 text-center w-20">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="sale-items-list-body">
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-body opacity-60">Belum ada item ditambahkan</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-span-3 border-t border-default my-2"></div>

                <!-- Totals Row Layout (Horizontal) -->
                <div class="col-span-3 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4">
                    <!-- Subtotal (Can be hidden dynamically) -->
                    <div id="subtotal-input-wrapper" class="col-span-1">
                        <label for="input-subtotal" class="block mb-2 text-sm font-medium text-heading">Subtotal *</label>
                        <input type="text" id="input-subtotal" name="subtotal" readonly class="bg-slate-100 border border-default-medium text-heading text-sm rounded-base block w-full px-3 py-2.5 shadow-xs font-semibold" placeholder="0" required>
                        <p id="error-subtotal" class="mt-2 text-xs font-medium text-rose-500 hidden"></p>
                    </div>

                    <!-- Discount -->
                    <div id="discount-input-wrapper" class="col-span-1">
                        <label for="input-discount" class="block mb-2 text-sm font-medium text-heading">Diskon</label>
                        <input type="text" id="input-discount" name="discount" oninput="onPriceInput(this); calculateGrandTotal()" class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body font-semibold text-rose-700" placeholder="0" value="0">
                        <p id="error-discount" class="mt-2 text-xs font-medium text-rose-500 hidden"></p>
                    </div>

                    <!-- Shipping Cost (Ongkir) -->
                    <div id="shipping-cost-input-wrapper" class="col-span-1">
                        <label for="input-shipping-cost" class="block mb-2 text-sm font-medium text-heading">Ongkir</label>
                        <input type="text" id="input-shipping-cost" name="shipping_cost" oninput="onPriceInput(this); calculateGrandTotal()" class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body font-semibold text-slate-800" placeholder="0" value="0">
                        <p id="error-shipping_cost" class="mt-2 text-xs font-medium text-rose-500 hidden"></p>
                    </div>

                    <!-- Grand Total (Can be hidden dynamically) -->
                    <div id="grand-total-input-wrapper" class="col-span-1">
                        <label for="input-grand-total" class="block mb-2 text-sm font-medium text-heading">Grand Total *</label>
                        <input type="text" id="input-grand-total" name="grand_total" readonly class="bg-slate-100 border border-default-medium text-heading text-sm font-bold rounded-base block w-full px-3 py-2.5 shadow-xs text-brand" placeholder="0" required>
                        <p id="error-grand_total" class="mt-2 text-xs font-medium text-rose-500 hidden"></p>
                    </div>

                    <!-- Payment Method -->
                    <div id="payment-method-input-wrapper" class="col-span-1">
                        <label for="input-payment-method" class="block mb-2 text-sm font-medium text-heading">Metode Pembayaran *</label>
                        <select id="input-payment-method" name="payment_method" class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs" required>
                            <option value="tunai">TUNAI</option>
                            <option value="transfer">TRANSFER</option>
                            <option value="compliment">COMPLIMENT</option>
                        </select>
                        <p id="error-payment_method" class="mt-2 text-xs font-medium text-rose-500 hidden"></p>
                    </div>
                </div>
            </div>

            <!-- Form Footer / Actions -->
            <div class="flex items-center space-x-4 border-default pt-6 mt-6">
                <button type="submit" id="btn-save" class="inline-flex items-center text-white bg-brand hover:bg-brand-strong box-border border border-transparent focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none cursor-pointer">
                    <svg class="w-4 h-4 me-1.5 -ms-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7 7V5"/>
                    </svg>
                    Simpan Penjualan
                </button>
                <a href="{{ route('sales.index') }}" class="text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none cursor-pointer">Batal</a>
            </div>
        </form>
    </div>
</div>

<!-- Modal (Kelola Customers of Selected Counter) -->
<div id="manage-customers-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300 opacity-0" id="manage-customers-backdrop" onclick="closeManageCustomers()"></div>
    <div class="relative w-full max-w-xl max-h-[90vh] flex flex-col transform scale-95 opacity-0 transition-all duration-300 bg-neutral-primary-soft border border-default rounded-base shadow-sm p-4 md:p-6" id="manage-customers-panel">
        <!-- Modal header -->
        <div class="flex items-center justify-between border-b border-default pb-4 shrink-0">
            <div>
                <h3 id="manage-customers-title" class="text-md font-bold text-heading">Kelola Pelanggan</h3>
                <p id="manage-customers-subtitle" class="text-xs text-body mt-0.5"></p>
            </div>
            <button type="button" onclick="closeManageCustomers()" class="text-body bg-transparent hover:bg-neutral-tertiary hover:text-heading rounded-base text-sm w-8 h-8 ms-auto inline-flex justify-center items-center cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Modal body -->
        <div class="overflow-y-auto flex-1 py-4 space-y-6">
            <!-- Add Customer Form -->
            <form id="inline-customer-form" onsubmit="handleInlineCustomerSubmit(event)" class="bg-neutral-secondary-medium p-4 rounded-base border border-default-medium space-y-3">
                <h4 class="text-xs font-bold text-heading uppercase tracking-wider">Tambah Pelanggan Baru</h4>
                <div class="grid grid-cols-2 gap-3">
                    <div class="col-span-2 sm:col-span-1">
                        <label for="inline-customer-name" class="block mb-1 text-xs font-medium text-heading">Nama Pelanggan *</label>
                        <input type="text" id="inline-customer-name" required class="bg-neutral-primary-soft border border-default text-heading text-xs rounded-base block w-full px-2.5 py-2 placeholder:text-body" placeholder="contoh: John Doe">
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label for="inline-customer-phone" class="block mb-1 text-xs font-medium text-heading">No Telepon *</label>
                        <input type="text" id="inline-customer-phone" required class="bg-neutral-primary-soft border border-default text-heading text-xs rounded-base block w-full px-2.5 py-2 placeholder:text-body" placeholder="contoh: 08123456789">
                    </div>
                    <div class="col-span-2">
                        <label for="inline-customer-address" class="block mb-1 text-xs font-medium text-heading">Alamat *</label>
                        <textarea id="inline-customer-address" required rows="2" class="bg-neutral-primary-soft border border-default text-heading text-xs rounded-base block w-full p-2.5 placeholder:text-body" placeholder="contoh: Jl. Raya No. 1"></textarea>
                    </div>
                </div>
                <div class="text-right">
                    <button type="submit" class="inline-flex items-center text-white bg-brand hover:bg-brand-strong font-medium rounded-base text-xs px-3.5 py-2 cursor-pointer shadow-xs">
                        <svg class="w-3.5 h-3.5 me-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Simpan Pelanggan
                    </button>
                </div>
            </form>

            <!-- Customer List -->
            <div class="space-y-2">
                <h4 class="text-xs font-bold text-heading uppercase tracking-wider">Daftar Pelanggan Terdaftar</h4>
                <div class="overflow-x-auto border border-default rounded-base max-h-48 overflow-y-auto">
                    <table class="w-full min-w-[400px] md:min-w-full text-xs text-left text-body">
                        <thead class="bg-neutral-secondary-medium text-heading uppercase font-semibold border-b border-default">
                            <tr>
                                <th class="px-3 py-2">Nama</th>
                                <th class="px-3 py-2">No Telepon</th>
                                <th class="px-3 py-2">Alamat</th>
                            </tr>
                        </thead>
                        <tbody id="inline-customer-list-body">
                            <!-- Rendered dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal (Kelola Expeditions) -->
<div id="manage-expeditions-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300 opacity-0" id="manage-expeditions-backdrop" onclick="closeManageExpeditions()"></div>
    <div class="relative w-full max-w-lg max-h-[90vh] flex flex-col transform scale-95 opacity-0 transition-all duration-300 bg-neutral-primary-soft border border-default rounded-base shadow-sm p-4 md:p-6" id="manage-expeditions-panel">
        <!-- Modal header -->
        <div class="flex items-center justify-between border-b border-default pb-4 shrink-0">
            <div>
                <h3 class="text-md font-bold text-heading">Kelola Ekspedisi</h3>
                <p class="text-xs text-body mt-0.5">Tambah dan lihat ekspedisi terdaftar</p>
            </div>
            <button type="button" onclick="closeManageExpeditions()" class="text-body bg-transparent hover:bg-neutral-tertiary hover:text-heading rounded-base text-sm w-8 h-8 ms-auto inline-flex justify-center items-center cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Modal body -->
        <div class="overflow-y-auto flex-1 py-4 space-y-6">
            <!-- Add Expedition Form -->
            <form id="inline-expedition-form" onsubmit="handleInlineExpeditionSubmit(event)" class="bg-neutral-secondary-medium p-4 rounded-base border border-default-medium space-y-3">
                <h4 class="text-xs font-bold text-heading uppercase tracking-wider">Tambah Ekspedisi Baru</h4>
                <div class="flex flex-col sm:flex-row gap-3 sm:items-end">
                    <div class="w-full sm:flex-1">
                        <label for="inline-expedition-name" class="block mb-1 text-xs font-medium text-heading">Nama Ekspedisi *</label>
                        <input type="text" id="inline-expedition-name" required class="bg-neutral-primary-soft border border-default text-heading text-xs rounded-base block w-full px-2.5 py-2 placeholder:text-body" placeholder="contoh: JNE, J&T">
                    </div>
                    <button type="submit" class="inline-flex items-center justify-center text-white bg-brand hover:bg-brand-strong font-medium rounded-base text-xs px-3.5 py-2 cursor-pointer shadow-xs h-[34px] w-full sm:w-auto shrink-0">
                        <svg class="w-3.5 h-3.5 me-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Simpan
                    </button>
                </div>
            </form>

            <!-- Expedition List -->
            <div class="space-y-2">
                <h4 class="text-xs font-bold text-heading uppercase tracking-wider">Daftar Ekspedisi Terdaftar</h4>
                <div class="overflow-x-auto border border-default rounded-base max-h-48 overflow-y-auto">
                    <table class="w-full min-w-[300px] md:min-w-full text-xs text-left text-body">
                        <thead class="bg-neutral-secondary-medium text-heading uppercase font-semibold border-b border-default">
                            <tr>
                                <th class="px-3 py-2">ID</th>
                                <th class="px-3 py-2">Nama Ekspedisi</th>
                                <th class="px-3 py-2 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="inline-expedition-list-body">
                            <!-- Rendered dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal (Kelola Couriers) -->
<div id="manage-couriers-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300 opacity-0" id="manage-couriers-backdrop" onclick="closeManageCouriers()"></div>
    <div class="relative w-full max-w-lg max-h-[90vh] flex flex-col transform scale-95 opacity-0 transition-all duration-300 bg-neutral-primary-soft border border-default rounded-base shadow-sm p-4 md:p-6" id="manage-couriers-panel">
        <!-- Modal header -->
        <div class="flex items-center justify-between border-b border-default pb-4 shrink-0">
            <div>
                <h3 class="text-md font-bold text-heading">Kelola Kurir</h3>
                <p class="text-xs text-body mt-0.5">Tambah dan lihat kurir terdaftar</p>
            </div>
            <button type="button" onclick="closeManageCouriers()" class="text-body bg-transparent hover:bg-neutral-tertiary hover:text-heading rounded-base text-sm w-8 h-8 ms-auto inline-flex justify-center items-center cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Modal body -->
        <div class="overflow-y-auto flex-1 py-4 space-y-6">
            <!-- Add Courier Form -->
            <form id="inline-courier-form" onsubmit="handleInlineCourierSubmit(event)" class="bg-neutral-secondary-medium p-4 rounded-base border border-default-medium space-y-3">
                <h4 class="text-xs font-bold text-heading uppercase tracking-wider">Tambah Kurir Baru</h4>
                <div class="grid grid-cols-2 gap-3 items-end">
                    <div class="col-span-2 sm:col-span-1">
                        <label for="inline-courier-name" class="block mb-1 text-xs font-medium text-heading">Nama Kurir *</label>
                        <input type="text" id="inline-courier-name" required class="bg-neutral-primary-soft border border-default text-heading text-xs rounded-base block w-full px-2.5 py-2 placeholder:text-body" placeholder="contoh: Sicepat, GrabExpress">
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label for="inline-courier-type" class="block mb-1 text-xs font-medium text-heading">Tipe Kurir *</label>
                        <select id="inline-courier-type" required class="bg-neutral-primary-soft border border-default text-heading text-xs rounded-base block w-full px-2.5 py-2">
                            <option value="umum">Umum</option>
                            <option value="marketplace">Marketplace</option>
                            <option value="keduanya">Keduanya</option>
                        </select>
                    </div>
                    <div class="col-span-2 text-right">
                        <button type="submit" class="inline-flex items-center text-white bg-brand hover:bg-brand-strong font-medium rounded-base text-xs px-3.5 py-2 cursor-pointer shadow-xs">
                            <svg class="w-3.5 h-3.5 me-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                            Simpan Kurir
                        </button>
                    </div>
                </div>
            </form>

            <!-- Courier List -->
            <div class="space-y-2">
                <h4 class="text-xs font-bold text-heading uppercase tracking-wider">Daftar Kurir Terdaftar</h4>
                <div class="overflow-x-auto border border-default rounded-base max-h-48 overflow-y-auto">
                    <table class="w-full min-w-[300px] md:min-w-full text-xs text-left text-body">
                        <thead class="bg-neutral-secondary-medium text-heading uppercase font-semibold border-b border-default">
                            <tr>
                                <th class="px-3 py-2">Nama Kurir</th>
                                <th class="px-3 py-2">Tipe</th>
                                <th class="px-3 py-2 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="inline-courier-list-body">
                            <!-- Rendered dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal (Kelola Marketplaces) -->
<div id="manage-marketplaces-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300 opacity-0" id="manage-marketplaces-backdrop" onclick="closeManageMarketplaces()"></div>
    <div class="relative w-full max-w-lg max-h-[90vh] flex flex-col transform scale-95 opacity-0 transition-all duration-300 bg-neutral-primary-soft border border-default rounded-base shadow-sm p-4 md:p-6" id="manage-marketplaces-panel">
        <!-- Modal header -->
        <div class="flex items-center justify-between border-b border-default pb-4 shrink-0">
            <div>
                <h3 class="text-md font-bold text-heading">Kelola Marketplace</h3>
                <p class="text-xs text-body mt-0.5">Tambah dan lihat marketplace terdaftar</p>
            </div>
            <button type="button" onclick="closeManageMarketplaces()" class="text-body bg-transparent hover:bg-neutral-tertiary hover:text-heading rounded-base text-sm w-8 h-8 ms-auto inline-flex justify-center items-center cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Modal body -->
        <div class="overflow-y-auto flex-1 py-4 space-y-6">
            <!-- Add Marketplace Form -->
            <form id="inline-marketplace-form" onsubmit="handleInlineMarketplaceSubmit(event)" class="bg-neutral-secondary-medium p-4 rounded-base border border-default-medium space-y-3">
                <h4 class="text-xs font-bold text-heading uppercase tracking-wider">Tambah Marketplace Baru</h4>
                <div class="flex flex-col sm:flex-row gap-3 sm:items-end">
                    <div class="w-full sm:flex-1">
                        <label for="inline-marketplace-name" class="block mb-1 text-xs font-medium text-heading">Nama Marketplace *</label>
                        <input type="text" id="inline-marketplace-name" required class="bg-neutral-primary-soft border border-default text-heading text-xs rounded-base block w-full px-2.5 py-2 placeholder:text-body" placeholder="contoh: Tokopedia, Shopee">
                    </div>
                    <button type="submit" class="inline-flex items-center justify-center text-white bg-brand hover:bg-brand-strong font-medium rounded-base text-xs px-3.5 py-2 cursor-pointer shadow-xs h-[34px] w-full sm:w-auto shrink-0">
                        <svg class="w-3.5 h-3.5 me-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Simpan
                    </button>
                </div>
            </form>

            <!-- Marketplace List -->
            <div class="space-y-2">
                <h4 class="text-xs font-bold text-heading uppercase tracking-wider">Daftar Marketplace Terdaftar</h4>
                <div class="overflow-x-auto border border-default rounded-base max-h-48 overflow-y-auto">
                    <table class="w-full min-w-[300px] md:min-w-full text-xs text-left text-body">
                        <thead class="bg-neutral-secondary-medium text-heading uppercase font-semibold border-b border-default">
                            <tr>
                                <th class="px-3 py-2">ID</th>
                                <th class="px-3 py-2">Nama Marketplace</th>
                                <th class="px-3 py-2 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="inline-marketplace-list-body">
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
    let activeCustomers = @json($customers);
    let activeProducts = @json($products);
    let activeExpeditions = @json($expeditions);
    let activeCouriers = @json($couriers);
    let activeMarketplaces = @json($marketplaces);
    let selectedCounterId = null;
    let saleItems = [];

    document.addEventListener("DOMContentLoaded", () => {
        // Hide wholeprice wrapper
        document.getElementById("item-wholeprice-wrapper").classList.add("hidden");
        
        saleItems = [];
        renderSaleItemsTable();

        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        document.getElementById("input-date").value = now.toISOString().slice(0, 16);
        document.getElementById("input-discount").value = "0";
        document.getElementById("input-shipping-cost").value = "0";

        selectedCounterId = null;
        renderCustomerOptions();
        renderProductOptions();
        renderExpeditionOptions();
        renderCourierOptions();
        renderMarketplaceOptions();
        
        toggleItemPenjualanAccess();
        onTypeChange();
        calculateGrandTotal();
        clearValidationErrors();
    });

    function formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(amount);
    }

    function onCounterChange() {
        const counterSelect = document.getElementById("input-counter-id");
        selectedCounterId = counterSelect.value;

        // Clear sale items if counter changes to prevent cross-counter mismatch
        if (saleItems.length > 0) {
            saleItems = [];
            renderSaleItemsTable();
            calculateGrandTotal();
            showToast("Item penjualan dibersihkan karena counter diubah.", "info");
        }

        // Re-render options for this counter
        renderCustomerOptions();
        renderProductOptions();
        toggleItemPenjualanAccess();
    }

    // Render dropdown customers
    function renderCustomerOptions() {
        const customerSelect = document.getElementById("input-customer-id");
        const currentSelectedVal = customerSelect.value;
        
        customerSelect.innerHTML = '<option value="">Pilih Pelanggan...</option>';
        
        const filtered = selectedCounterId ? activeCustomers.filter(c => String(c.counter_id) === String(selectedCounterId)) : [];
        
        filtered.forEach(c => {
            const opt = document.createElement("option");
            opt.value = c.id;
            opt.setAttribute("data-phone", c.phone || '');
            opt.setAttribute("data-address", c.address || '');
            opt.innerText = c.name;
            customerSelect.appendChild(opt);
        });
        
        if (filtered.some(c => String(c.id) === String(currentSelectedVal))) {
            customerSelect.value = currentSelectedVal;
        } else {
            customerSelect.value = "";
        }
        onCustomerChange();
    }

    // Render dropdown products
    function renderProductOptions() {
        const productSelect = document.getElementById("select-product-id");
        const currentSelectedVal = productSelect.value;
        
        productSelect.innerHTML = '<option value="">-- Pilih Produk --</option>';
        
        const filtered = selectedCounterId ? activeProducts.filter(p => String(p.counter_id) === String(selectedCounterId)) : [];
        
        filtered.forEach(p => {
            const opt = document.createElement("option");
            opt.value = p.id;
            opt.setAttribute("data-price", p.sell_price || 0);
            opt.setAttribute("data-image", p.image || "");
            opt.innerText = p.name;
            productSelect.appendChild(opt);
        });
        
        if (filtered.some(p => String(p.id) === String(currentSelectedVal))) {
            productSelect.value = currentSelectedVal;
        } else {
            productSelect.value = "";
        }
    }

    function toggleItemPenjualanAccess() {
        const isCounterSelected = !!selectedCounterId;
        
        const customerSelect = document.getElementById("input-customer-id");
        const manageCustomerBtn = document.getElementById("btn-manage-customers");
        
        if (customerSelect) {
            customerSelect.disabled = !isCounterSelected;
        }
        if (manageCustomerBtn) {
            manageCustomerBtn.disabled = !isCounterSelected;
            if (isCounterSelected) {
                manageCustomerBtn.classList.remove("opacity-50", "cursor-not-allowed");
                manageCustomerBtn.classList.add("cursor-pointer");
            } else {
                manageCustomerBtn.classList.add("opacity-50", "cursor-not-allowed");
                manageCustomerBtn.classList.remove("cursor-pointer");
            }
        }
        
        const productSelect = document.getElementById("select-product-id");
        const qtyInput = document.getElementById("input-item-qty");
        const priceInput = document.getElementById("input-item-price");
        const addButton = document.querySelector("button[onclick='addSaleItem()']");
        
        if (productSelect) {
            productSelect.disabled = !isCounterSelected;
        }
        if (qtyInput) {
            qtyInput.disabled = !isCounterSelected;
        }
        if (priceInput) {
            priceInput.disabled = !isCounterSelected;
        }
        
        if (addButton) {
            addButton.disabled = !isCounterSelected;
            if (isCounterSelected) {
                addButton.classList.remove("opacity-50", "cursor-not-allowed");
                addButton.classList.add("cursor-pointer");
            } else {
                addButton.classList.add("opacity-50", "cursor-not-allowed");
                addButton.classList.remove("cursor-pointer");
            }
        }
    }

    function onCustomerChange() {
        const customerSelect = document.getElementById("input-customer-id");
        const selectedOption = customerSelect.options[customerSelect.selectedIndex];
        
        const previewDiv = document.getElementById("customer-details-preview");
        const phoneDiv = document.getElementById("customer-preview-phone");
        const addressDiv = document.getElementById("customer-preview-address");
        
        if (selectedOption && selectedOption.value) {
            const phone = selectedOption.getAttribute("data-phone") || '-';
            const address = selectedOption.getAttribute("data-address") || '-';
            
            phoneDiv.innerText = `No. Telp: ${phone}`;
            addressDiv.innerText = `Alamat: ${address}`;
            previewDiv.classList.remove("hidden");
        } else {
            previewDiv.classList.add("hidden");
            phoneDiv.innerText = "";
            addressDiv.innerText = "";
        }
    }

    function onTypeChange() {
        const type = document.getElementById("input-type").value;
        const umumWrapper = document.getElementById("umum-fields-wrapper");
        const marketplaceWrapper = document.getElementById("marketplace-fields-wrapper");
        const subtotalWrapper = document.getElementById("subtotal-input-wrapper");
        const grandTotalWrapper = document.getElementById("grand-total-input-wrapper");
        const discountWrapper = document.getElementById("discount-input-wrapper");
        const shippingCostWrapper = document.getElementById("shipping-cost-input-wrapper");
        const paymentMethodWrapper = document.getElementById("payment-method-input-wrapper");
        const paymentMethodInput = document.getElementById("input-payment-method");
        const priceInput = document.getElementById("input-item-price");
        
        if (type === "marketplace") {
            umumWrapper.classList.add("hidden");
            marketplaceWrapper.classList.remove("hidden");
            subtotalWrapper.classList.add("hidden");
            grandTotalWrapper.classList.add("hidden");
            discountWrapper.classList.add("hidden");
            shippingCostWrapper.classList.add("hidden");
            
            if (paymentMethodWrapper) {
                paymentMethodWrapper.classList.add("hidden");
            }
            if (paymentMethodInput && (paymentMethodInput.value === "tunai" || !paymentMethodInput.value)) {
                paymentMethodInput.value = "transfer";
            }
            
            document.getElementById("input-customer-id").value = "";
            document.getElementById("input-expedition-id").value = "";
            onCustomerChange();
            
            const barcodeInput = document.getElementById("input-barcode");
            if (barcodeInput.value.startsWith("UMUM-")) {
                barcodeInput.value = "";
            }
            onBarcodeChange();
            
            // Editable and empty for marketplace
            priceInput.removeAttribute("readonly");
            priceInput.value = "";
            priceInput.classList.remove("bg-slate-100", "cursor-not-allowed");
            priceInput.classList.add("bg-neutral-primary-soft");
            
            // Hide wholeprice tiers
            document.getElementById("item-wholeprice-wrapper").classList.add("hidden");
            
            // Reset discount and shipping cost
            document.getElementById("input-discount").value = "0";
            document.getElementById("input-shipping-cost").value = "0";
            calculateGrandTotal();
        } else {
            umumWrapper.classList.remove("hidden");
            marketplaceWrapper.classList.add("hidden");
            subtotalWrapper.classList.remove("hidden");
            grandTotalWrapper.classList.remove("hidden");
            discountWrapper.classList.remove("hidden");
            shippingCostWrapper.classList.remove("hidden");
            
            if (paymentMethodWrapper) {
                paymentMethodWrapper.classList.remove("hidden");
            }
            
            document.getElementById("input-marketplace-id").value = "";
            document.getElementById("input-courier-id").value = "";
            document.getElementById("input-barcode").value = "UMUM-" + Date.now();
            
            // Read-only for general sales
            priceInput.setAttribute("readonly", true);
            priceInput.classList.add("bg-slate-100", "cursor-not-allowed");
            priceInput.classList.remove("bg-neutral-primary-soft");
            
            onProductChange();
        }
    }

    function generateRandomBarcode() {
        let barcode = "";
        for (let i = 0; i < 12; i++) {
            barcode += Math.floor(Math.random() * 10);
        }
        return barcode;
    }

    function generateBarcodeSVG(text) {
        if (!text) return '';
        let svg = `<svg class="w-48 h-12" viewBox="0 0 100 40" preserveAspectRatio="none">`;
        let x = 2;
        for (let i = 0; i < text.length; i++) {
            const charCode = text.charCodeAt(i);
            const w1 = ((charCode * 7) % 3) + 1;
            const w2 = ((charCode * 13) % 2) + 1;
            svg += `<rect x="${x}" y="2" width="${w1 * 0.4}" height="36" fill="#1e293b"/>`;
            x += w1 * 0.7;
            svg += `<rect x="${x}" y="2" width="${w2 * 0.4}" height="36" fill="#1e293b"/>`;
            x += w2 * 0.7 + 1.2;
        }
        svg += `</svg>`;
        return svg;
    }

    function onBarcodeChange() {
        const barcodeInput = document.getElementById("input-barcode");
        const barcodeVal = barcodeInput.value.trim();
        
        const textEl = document.getElementById("barcode-preview-text");
        const graphicEl = document.getElementById("barcode-preview-graphic");
        
        if (barcodeVal) {
            textEl.innerText = barcodeVal;
            graphicEl.innerHTML = generateBarcodeSVG(barcodeVal);
            document.getElementById("barcode-visual-preview").classList.remove("hidden");
        } else {
            textEl.innerText = "-";
            graphicEl.innerHTML = "";
            document.getElementById("barcode-visual-preview").classList.add("hidden");
        }
    }

    function formatNumberInput(value) {
        let clean = value.replace(/\D/g, '');
        if (!clean) return '';
        return new Intl.NumberFormat('id-ID').format(parseInt(clean));
    }

    function parseFormattedNumber(value) {
        if (!value) return 0;
        return parseFloat(value.replace(/\./g, '')) || 0;
    }

    function onPriceInput(el) {
        el.value = formatNumberInput(el.value);
    }

    // SALE ITEMS LOGIC
    function onProductChange() {
        const productSelect = document.getElementById("select-product-id");
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const priceInput = document.getElementById("input-item-price");
        const qtyInput = document.getElementById("input-item-qty");
        const wholepriceWrapper = document.getElementById("item-wholeprice-wrapper");
        const normalPriceLabel = document.getElementById("normal-price-label");
        const tiersContainer = document.getElementById("wholeprice-tiers-selection");
        
        wholepriceWrapper.classList.add("hidden");
        document.getElementById("price-type-normal").checked = true;
        tiersContainer.classList.add("hidden");
        tiersContainer.innerHTML = "";
        
        if (selectedOption && selectedOption.value) {
            const productId = selectedOption.value;
            const defaultPrice = parseFloat(selectedOption.getAttribute("data-price")) || 0;
            
            const type = document.getElementById("input-type").value;
            if (type === "marketplace") {
                priceInput.value = "";
            } else {
                priceInput.value = formatNumberInput(Math.round(defaultPrice).toString());
            }
            
            qtyInput.value = 1;
            
            const productObj = activeProducts.find(p => String(p.id) === String(productId));
            if (productObj && productObj.unit && String(productObj.unit.name).toLowerCase() === 'kg') {
                qtyInput.setAttribute("step", "any");
                qtyInput.setAttribute("min", "0.01");
            } else {
                qtyInput.setAttribute("step", "1");
                qtyInput.setAttribute("min", "1");
            }

            if (type !== "marketplace" && productObj && productObj.is_wholeprice && productObj.wholeprices && productObj.wholeprices.length > 0) {
                normalPriceLabel.innerText = formatCurrency(defaultPrice);
                productObj.wholeprices.forEach((tier, index) => {
                    const div = document.createElement("div");
                    div.className = "flex items-center gap-2";
                    div.innerHTML = `
                        <label class="inline-flex items-center cursor-pointer text-xs text-body">
                            <input type="radio" name="selected_wholeprice_tier" value="${tier.id}" data-min-qty="${tier.minimum_qty}" data-price="${tier.wholeprice_price}" onchange="selectWholepriceTier(this)" class="mr-2 text-brand focus:ring-brand" ${index === 0 ? 'checked' : ''}>
                            Min. Qty ${tier.minimum_qty} - ${formatCurrency(tier.wholeprice_price)} / unit
                        </label>
                    `;
                    tiersContainer.appendChild(div);
                });
                
                wholepriceWrapper.classList.remove("hidden");
            }
        } else {
            priceInput.value = "";
            qtyInput.value = 1;
            qtyInput.setAttribute("step", "1");
            qtyInput.setAttribute("min", "1");
        }
    }

    function onPriceTypeChange(type) {
        const productSelect = document.getElementById("select-product-id");
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const priceInput = document.getElementById("input-item-price");
        const tiersContainer = document.getElementById("wholeprice-tiers-selection");

        if (type === 'normal') {
            tiersContainer.classList.add("hidden");
            if (selectedOption && selectedOption.value) {
                const defaultPrice = parseFloat(selectedOption.getAttribute("data-price")) || 0;
                priceInput.value = formatNumberInput(Math.round(defaultPrice).toString());
            }
        } else {
            tiersContainer.classList.remove("hidden");
            const firstTierInput = tiersContainer.querySelector('input[name="selected_wholeprice_tier"]');
            if (firstTierInput) {
                firstTierInput.checked = true;
                selectWholepriceTier(firstTierInput);
            }
        }
    }

    function selectWholepriceTier(tierRadio) {
        const priceInput = document.getElementById("input-item-price");
        const qtyInput = document.getElementById("input-item-qty");
        
        const minQty = parseFloat(tierRadio.getAttribute("data-min-qty")) || 1;
        const tierPrice = parseFloat(tierRadio.getAttribute("data-price")) || 0;
        
        priceInput.value = formatNumberInput(Math.round(tierPrice).toString());
        
        const currentQty = parseFloat(qtyInput.value) || 0;
        if (currentQty < minQty) {
            qtyInput.value = minQty;
        }
    }

    function calculateItemSubtotal() {
        const qtyInput = document.getElementById("input-item-qty");
        const isWholepriceChecked = document.getElementById("price-type-wholeprice").checked;
        if (isWholepriceChecked) {
            const selectedTierRadio = document.querySelector('input[name="selected_wholeprice_tier"]:checked');
            if (selectedTierRadio) {
                const minQty = parseFloat(selectedTierRadio.getAttribute("data-min-qty")) || 1;
                const currentQty = parseFloat(qtyInput.value) || 0;
                if (currentQty < minQty) {
                    showToast(`Quantity kurang dari minimal pembelian untuk harga grosir ini (${minQty}). Harga dialihkan kembali ke harga normal.`, "warning");
                    document.getElementById("price-type-normal").checked = true;
                    onPriceTypeChange('normal');
                }
            }
        }
    }

    function addSaleItem() {
        const productSelect = document.getElementById("select-product-id");
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const qtyInput = document.getElementById("input-item-qty");
        const priceInput = document.getElementById("input-item-price");
        
        if (!selectedOption || !selectedOption.value) {
            showToast("Silakan pilih produk terlebih dahulu!", "error");
            return;
        }
        
        const productId = selectedOption.value;
        const productName = selectedOption.innerText;
        const productImage = selectedOption.getAttribute("data-image") || "";
        const qty = parseFloat(qtyInput.value) || 0;
        const price = parseFormattedNumber(priceInput.value);
        
        const productObj = activeProducts.find(p => String(p.id) === String(productId));
        const isKg = productObj && productObj.unit && String(productObj.unit.name).toLowerCase() === 'kg';
        
        if (isKg) {
            if (qty <= 0) {
                showToast("Quantity untuk produk Kg harus lebih dari 0!", "error");
                return;
            }
        } else {
            if (qty < 1) {
                showToast("Quantity minimal harus 1!", "error");
                return;
            }
            if (!Number.isInteger(qty)) {
                showToast("Quantity untuk produk ini harus berupa angka bulat (bukan desimal)!", "error");
                return;
            }
        }
        
        if (priceInput.value.trim() === "") {
            showToast("Silakan masukkan harga terlebih dahulu!", "error");
            return;
        }
        
        if (price < 0) {
            showToast("Harga tidak boleh negatif!", "error");
            return;
        }

        const isWholeprice = document.getElementById("price-type-wholeprice").checked;
        let wholepriceId = null;
        if (isWholeprice) {
            const selectedTierRadio = document.querySelector('input[name="selected_wholeprice_tier"]:checked');
            if (selectedTierRadio) {
                wholepriceId = selectedTierRadio.value;
            }
        }
        
        const existingIndex = saleItems.findIndex(item => 
            String(item.product_id) === String(productId) &&
            !!item.is_wholeprice === isWholeprice &&
            String(item.wholeprice_id) === String(wholepriceId)
        );
        
        if (existingIndex > -1) {
            saleItems[existingIndex].qty += qty;
            saleItems[existingIndex].subtotal = saleItems[existingIndex].qty * saleItems[existingIndex].price;
        } else {
            saleItems.push({
                product_id: productId,
                product_name: productName,
                product_image: productImage,
                qty: qty,
                price: price,
                subtotal: qty * price,
                is_wholeprice: isWholeprice ? 1 : 0,
                wholeprice_id: wholepriceId
            });
        }
        
        productSelect.value = "";
        qtyInput.value = "1";
        priceInput.value = "";
        
        document.getElementById("item-wholeprice-wrapper").classList.add("hidden");
        
        renderSaleItemsTable();
        calculateGrandTotal();
    }

    function removeSaleItem(index) {
        saleItems.splice(index, 1);
        renderSaleItemsTable();
        calculateGrandTotal();
    }

    function renderSaleItemsTable() {
        const tbody = document.getElementById("sale-items-list-body");
        tbody.innerHTML = "";
        
        if (saleItems.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="px-4 py-6 text-center text-body opacity-60">Belum ada item ditambahkan</td></tr>';
            return;
        }
        
        saleItems.forEach((item, index) => {
            const row = document.createElement("tr");
            row.className = "border-b border-default hover:bg-neutral-secondary-medium/30";
            
            const imageHtml = item.product_image 
                ? `<img src="/storage/${item.product_image}" class="w-10 h-10 object-cover rounded mx-auto border border-default" alt="${escapeHtml(item.product_name)}">`
                : `<div class="w-10 h-10 bg-slate-100 border border-default rounded flex items-center justify-center text-slate-400 mx-auto">
                     <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                    </div>`;
            
            row.innerHTML = `
                <td class="px-4 py-2">
                    ${imageHtml}
                </td>
                <td class="px-4 py-2 font-medium text-heading">
                    <div>${escapeHtml(item.product_name)}</div>
                    ${item.is_wholeprice ? '<span class="px-1.5 py-0.5 text-[9px] bg-emerald-50 text-emerald-700 font-semibold rounded-md border border-emerald-200 mt-1 inline-block">Grosir</span>' : ''}
                </td>
                <td class="px-4 py-2 text-center font-semibold">${item.qty}</td>
                <td class="px-4 py-2 text-right">${formatCurrency(item.price)}</td>
                <td class="px-4 py-2 text-right font-bold text-slate-800">${formatCurrency(item.subtotal)}</td>
                <td class="px-4 py-2 text-center">
                    <button type="button" onclick="removeSaleItem(${index})" class="text-rose-500 hover:text-rose-700 font-bold focus:outline-none cursor-pointer">Hapus</button>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    function calculateGrandTotal() {
        const subtotal = saleItems.reduce((sum, item) => sum + item.subtotal, 0);
        const discount = parseFormattedNumber(document.getElementById("input-discount").value || "0");
        const shippingCost = parseFormattedNumber(document.getElementById("input-shipping-cost").value || "0");
        
        const grandTotal = Math.max(0, subtotal - discount + shippingCost);
        
        document.getElementById("input-subtotal").value = formatNumberInput(Math.round(subtotal).toString());
        document.getElementById("input-grand-total").value = formatNumberInput(Math.round(grandTotal).toString());
    }

    // INLINE CUSTOMER MANAGEMENT
    function openManageCustomers() {
        if (!selectedCounterId) {
            showToast("Silakan pilih counter terlebih dahulu!", "error");
            return;
        }
        
        const counterSelect = document.getElementById("input-counter-id");
        const counterName = counterSelect.options[counterSelect.selectedIndex].innerText;
        document.getElementById("manage-customers-subtitle").innerText = `Counter: ${counterName}`;
        
        document.getElementById("inline-customer-form").reset();
        renderInlineCustomerList();
        
        const modal = document.getElementById("manage-customers-modal");
        const backdrop = document.getElementById("manage-customers-backdrop");
        const panel = document.getElementById("manage-customers-panel");
        
        modal.classList.remove("hidden");
        setTimeout(() => {
            backdrop.classList.replace("opacity-0", "opacity-100");
            panel.classList.replace("scale-95", "scale-100");
            panel.classList.replace("opacity-0", "opacity-100");
        }, 10);
    }

    function closeManageCustomers() {
        const modal = document.getElementById("manage-customers-modal");
        const backdrop = document.getElementById("manage-customers-backdrop");
        const panel = document.getElementById("manage-customers-panel");
        
        backdrop.classList.replace("opacity-100", "opacity-0");
        panel.classList.replace("scale-100", "scale-95");
        panel.classList.replace("opacity-100", "opacity-0");
        
        setTimeout(() => {
            modal.classList.add("hidden");
        }, 300);
    }

    function renderInlineCustomerList() {
        const tbody = document.getElementById("inline-customer-list-body");
        tbody.innerHTML = "";
        
        const filtered = activeCustomers.filter(c => String(c.counter_id) === String(selectedCounterId));
        if (filtered.length === 0) {
            tbody.innerHTML = '<tr><td colspan="3" class="px-3 py-4 text-center text-body opacity-60">Belum ada pelanggan terdaftar di counter ini.</td></tr>';
            return;
        }
        
        filtered.forEach(c => {
            const row = document.createElement("tr");
            row.className = "border-b border-default hover:bg-neutral-secondary-medium/50";
            row.innerHTML = `
                <td class="px-3 py-2 font-medium text-heading">${escapeHtml(c.name)}</td>
                <td class="px-3 py-2 text-body">${escapeHtml(c.phone)}</td>
                <td class="px-3 py-2 text-body max-w-xs truncate">${escapeHtml(c.address)}</td>
            `;
            tbody.appendChild(row);
        });
    }

    async function handleInlineCustomerSubmit(event) {
        event.preventDefault();
        
        const name = document.getElementById("inline-customer-name").value.trim();
        const phone = document.getElementById("inline-customer-phone").value.trim();
        const address = document.getElementById("inline-customer-address").value.trim();
        
        const data = {
            counter_id: selectedCounterId,
            name,
            phone,
            address
        };
        
        try {
            const response = await fetch("/customers", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (response.status === 422) {
                const firstErrKey = Object.keys(result.errors)[0];
                showToast(result.errors[firstErrKey][0], "error");
                return;
            }
            
            if (!response.ok) throw new Error("Could not save customer.");
            
            activeCustomers.push(result);
            showToast("Pelanggan berhasil ditambahkan!", "success");
            
            renderCustomerOptions();
            document.getElementById("input-customer-id").value = result.id;
            onCustomerChange();
            
            closeManageCustomers();
        } catch (error) {
            showToast(error.message, "error");
        }
    }

    // INLINE EXPEDITION MANAGEMENT
    function openManageExpeditions() {
        document.getElementById("inline-expedition-form").reset();
        renderInlineEditionList();
        
        const modal = document.getElementById("manage-expeditions-modal");
        const backdrop = document.getElementById("manage-expeditions-backdrop");
        const panel = document.getElementById("manage-expeditions-panel");
        
        modal.classList.remove("hidden");
        setTimeout(() => {
            backdrop.classList.replace("opacity-0", "opacity-100");
            panel.classList.replace("scale-95", "scale-100");
            panel.classList.replace("opacity-0", "opacity-100");
        }, 10);
    }

    function closeManageExpeditions() {
        const modal = document.getElementById("manage-expeditions-modal");
        const backdrop = document.getElementById("manage-expeditions-backdrop");
        const panel = document.getElementById("manage-expeditions-panel");
        
        backdrop.classList.replace("opacity-100", "opacity-0");
        panel.classList.replace("scale-100", "scale-95");
        panel.classList.replace("opacity-100", "opacity-0");
        
        setTimeout(() => {
            modal.classList.add("hidden");
        }, 300);
    }

    function renderInlineEditionList() {
        const tbody = document.getElementById("inline-expedition-list-body");
        tbody.innerHTML = "";
        
        if (activeExpeditions.length === 0) {
            tbody.innerHTML = '<tr><td colspan="3" class="px-3 py-4 text-center text-body opacity-60">Belum ada ekspedisi terdaftar.</td></tr>';
            return;
        }
        
        activeExpeditions.forEach(e => {
            const row = document.createElement("tr");
            row.className = "border-b border-default hover:bg-neutral-secondary-medium/50";
            row.innerHTML = `
                <td class="px-3 py-2 font-medium text-heading">${e.id}</td>
                <td class="px-3 py-2 text-body">${escapeHtml(e.name)}</td>
                <td class="px-3 py-2 text-right">
                    <button type="button" onclick="deleteInlineExpedition(${e.id})" class="text-rose-500 hover:text-rose-700 font-semibold cursor-pointer">Hapus</button>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    async function handleInlineExpeditionSubmit(event) {
        event.preventDefault();
        
        const name = document.getElementById("inline-expedition-name").value.trim();
        const data = { name };
        
        try {
            const response = await fetch("/expeditions", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (response.status === 422) {
                const firstErrKey = Object.keys(result.errors)[0];
                showToast(result.errors[firstErrKey][0], "error");
                return;
            }
            
            if (!response.ok) throw new Error("Could not save expedition.");
            
            activeExpeditions.push(result);
            showToast("Ekspedisi berhasil ditambahkan!", "success");
            
            renderExpeditionOptions();
            document.getElementById("input-expedition-id").value = result.id;
            
            closeManageExpeditions();
        } catch (error) {
            showToast(error.message, "error");
        }
    }

    function renderExpeditionOptions() {
        const expeditionSelect = document.getElementById("input-expedition-id");
        const currentSelectedVal = expeditionSelect.value;
        
        expeditionSelect.innerHTML = '<option value="">Pilih Ekspedisi...</option>';
        activeExpeditions.forEach(e => {
            const opt = document.createElement("option");
            opt.value = e.id;
            opt.innerText = e.name;
            expeditionSelect.appendChild(opt);
        });
        
        if (activeExpeditions.some(e => String(e.id) === String(currentSelectedVal))) {
            expeditionSelect.value = currentSelectedVal;
        } else {
            expeditionSelect.value = "";
        }
    }

    // INLINE COURIER MANAGEMENT
    function openManageCouriers() {
        document.getElementById("inline-courier-form").reset();
        renderInlineCourierList();
        
        const modal = document.getElementById("manage-couriers-modal");
        const backdrop = document.getElementById("manage-couriers-backdrop");
        const panel = document.getElementById("manage-couriers-panel");
        
        modal.classList.remove("hidden");
        setTimeout(() => {
            backdrop.classList.replace("opacity-0", "opacity-100");
            panel.classList.replace("scale-95", "scale-100");
            panel.classList.replace("opacity-0", "opacity-100");
        }, 10);
    }

    function closeManageCouriers() {
        const modal = document.getElementById("manage-couriers-modal");
        const backdrop = document.getElementById("manage-couriers-backdrop");
        const panel = document.getElementById("manage-couriers-panel");
        
        backdrop.classList.replace("opacity-100", "opacity-0");
        panel.classList.replace("scale-100", "scale-95");
        panel.classList.replace("opacity-100", "opacity-0");
        
        setTimeout(() => {
            modal.classList.add("hidden");
        }, 300);
    }

    // Render list of courier on manage modal
    function renderInlineCourierList() {
        const tbody = document.getElementById("inline-courier-list-body");
        tbody.innerHTML = "";
        
        if (activeCouriers.length === 0) {
            tbody.innerHTML = '<tr><td colspan="3" class="px-3 py-4 text-center text-body opacity-60">Belum ada kurir terdaftar.</td></tr>';
            return;
        }
        
        activeCouriers.forEach(c => {
            const row = document.createElement("tr");
            row.className = "border-b border-default hover:bg-neutral-secondary-medium/50";
            row.innerHTML = `
                <td class="px-3 py-2 font-medium text-heading">${escapeHtml(c.name)}</td>
                <td class="px-3 py-2 text-body uppercase text-[10px] font-semibold">${c.type}</td>
                <td class="px-3 py-2 text-right">
                    <button type="button" onclick="deleteInlineCourier(${c.id})" class="text-rose-500 hover:text-rose-700 font-semibold cursor-pointer">Hapus</button>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    async function handleInlineCourierSubmit(event) {
        event.preventDefault();
        
        const name = document.getElementById("inline-courier-name").value.trim();
        const type = document.getElementById("inline-courier-type").value;
        const data = { name, type };
        
        try {
            const response = await fetch("/couriers", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (response.status === 422) {
                const firstErrKey = Object.keys(result.errors)[0];
                showToast(result.errors[firstErrKey][0], "error");
                return;
            }
            
            if (!response.ok) throw new Error("Could not save courier.");
            
            activeCouriers.push(result);
            showToast("Kurir berhasil ditambahkan!", "success");
            
            renderCourierOptions();
            document.getElementById("input-courier-id").value = result.id;
            
            closeManageCouriers();
        } catch (error) {
            showToast(error.message, "error");
        }
    }

    function renderCourierOptions() {
        const courierSelect = document.getElementById("input-courier-id");
        const currentSelectedVal = courierSelect.value;
        
        courierSelect.innerHTML = '<option value="">Pilih Kurir...</option>';
        activeCouriers.forEach(c => {
            const opt = document.createElement("option");
            opt.value = c.id;
            opt.innerText = `${c.name} (${c.type})`;
            courierSelect.appendChild(opt);
        });
        
        if (activeCouriers.some(c => String(c.id) === String(currentSelectedVal))) {
            courierSelect.value = currentSelectedVal;
        } else {
            courierSelect.value = "";
        }
    }

    // INLINE MARKETPLACE MANAGEMENT
    function openManageMarketplaces() {
        document.getElementById("inline-marketplace-form").reset();
        renderInlineMarketplaceList();
        
        const modal = document.getElementById("manage-marketplaces-modal");
        const backdrop = document.getElementById("manage-marketplaces-backdrop");
        const panel = document.getElementById("manage-marketplaces-panel");
        
        modal.classList.remove("hidden");
        setTimeout(() => {
            backdrop.classList.replace("opacity-0", "opacity-100");
            panel.classList.replace("scale-95", "scale-100");
            panel.classList.replace("opacity-0", "opacity-100");
        }, 10);
    }

    function closeManageMarketplaces() {
        const modal = document.getElementById("manage-marketplaces-modal");
        const backdrop = document.getElementById("manage-marketplaces-backdrop");
        const panel = document.getElementById("manage-marketplaces-panel");
        
        backdrop.classList.replace("opacity-100", "opacity-0");
        panel.classList.replace("scale-100", "scale-95");
        panel.classList.replace("opacity-100", "opacity-0");
        
        setTimeout(() => {
            modal.classList.add("hidden");
        }, 300);
    }

    function renderInlineMarketplaceList() {
        const tbody = document.getElementById("inline-marketplace-list-body");
        tbody.innerHTML = "";
        
        if (activeMarketplaces.length === 0) {
            tbody.innerHTML = '<tr><td colspan="3" class="px-3 py-4 text-center text-body opacity-60">Belum ada marketplace terdaftar.</td></tr>';
            return;
        }
        
        activeMarketplaces.forEach(m => {
            const row = document.createElement("tr");
            row.className = "border-b border-default hover:bg-neutral-secondary-medium/50";
            row.innerHTML = `
                <td class="px-3 py-2 font-medium text-heading">${m.id}</td>
                <td class="px-3 py-2 text-body">${escapeHtml(m.name)}</td>
                <td class="px-3 py-2 text-right">
                    <button type="button" onclick="deleteInlineMarketplace(${m.id})" class="text-rose-500 hover:text-rose-700 font-semibold cursor-pointer">Hapus</button>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    async function handleInlineMarketplaceSubmit(event) {
        event.preventDefault();
        
        const name = document.getElementById("inline-marketplace-name").value.trim();
        const data = { name };
        
        try {
            const response = await fetch("/marketplaces", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (response.status === 422) {
                const firstErrKey = Object.keys(result.errors)[0];
                showToast(result.errors[firstErrKey][0], "error");
                return;
            }
            
            if (!response.ok) throw new Error("Could not save marketplace.");
            
            activeMarketplaces.push(result);
            showToast("Marketplace berhasil ditambahkan!", "success");
            
            renderMarketplaceOptions();
            document.getElementById("input-marketplace-id").value = result.id;
            
            closeManageMarketplaces();
        } catch (error) {
            showToast(error.message, "error");
        }
    }

    function renderMarketplaceOptions() {
        const marketplaceSelect = document.getElementById("input-marketplace-id");
        const currentSelectedVal = marketplaceSelect.value;
        
        marketplaceSelect.innerHTML = '<option value="">Pilih Marketplace...</option>';
        activeMarketplaces.forEach(m => {
            const opt = document.createElement("option");
            opt.value = m.id;
            opt.innerText = m.name;
            marketplaceSelect.appendChild(opt);
        });
        
        if (activeMarketplaces.some(m => String(m.id) === String(currentSelectedVal))) {
            marketplaceSelect.value = currentSelectedVal;
        } else {
            marketplaceSelect.value = "";
        }
    }

    // INLINE DELETE ACTIONS
    async function deleteInlineExpedition(id) {
        if (!confirm("Apakah Anda yakin ingin menghapus ekspedisi ini?")) return;
        
        try {
            const response = await fetch(`/expeditions/${id}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                    "Accept": "application/json"
                }
            });
            
            if (!response.ok) throw new Error("Gagal menghapus ekspedisi.");
            
            activeExpeditions = activeExpeditions.filter(e => String(e.id) !== String(id));
            showToast("Ekspedisi berhasil dihapus!", "success");
            renderExpeditionOptions();
            renderInlineEditionList();
        } catch (error) {
            showToast(error.message, "error");
        }
    }

    async function deleteInlineCourier(id) {
        if (!confirm("Apakah Anda yakin ingin menghapus kurir ini?")) return;
        
        try {
            const response = await fetch(`/couriers/${id}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                    "Accept": "application/json"
                }
            });
            
            if (!response.ok) throw new Error("Gagal menghapus kurir.");
            
            activeCouriers = activeCouriers.filter(c => String(c.id) !== String(id));
            showToast("Kurir berhasil dihapus!", "success");
            renderCourierOptions();
            renderInlineCourierList();
        } catch (error) {
            showToast(error.message, "error");
        }
    }

    async function deleteInlineMarketplace(id) {
        if (!confirm("Apakah Anda yakin ingin menghapus marketplace ini?")) return;
        
        try {
            const response = await fetch(`/marketplaces/${id}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                    "Accept": "application/json"
                }
            });
            
            if (!response.ok) throw new Error("Gagal menghapus marketplace.");
            
            activeMarketplaces = activeMarketplaces.filter(m => String(m.id) !== String(id));
            showToast("Marketplace berhasil dihapus!", "success");
            renderMarketplaceOptions();
            renderInlineMarketplaceList();
        } catch (error) {
            showToast(error.message, "error");
        }
    }

    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") {
            closeManageCustomers();
            closeManageExpeditions();
            closeManageCouriers();
            closeManageMarketplaces();
        }
    });

    // Form Submit handling
    async function handleFormSubmit(event) {
        event.preventDefault();
        clearValidationErrors();
        
        if (saleItems.length === 0) {
            showToast("Silakan tambahkan minimal 1 item penjualan terlebih dahulu!", "error");
            return;
        }

        const counter_id = document.getElementById("input-counter-id").value;
        const customer_id = document.getElementById("input-customer-id").value || null;
        const expedition_id = document.getElementById("input-expedition-id").value || null;
        const barcode = document.getElementById("input-barcode").value;
        const date = document.getElementById("input-date").value;
        const type = document.getElementById("input-type").value;
        const payment_method = document.getElementById("input-payment-method").value;
        const marketplace_id = document.getElementById("input-marketplace-id").value || null;
        const courier_id = document.getElementById("input-courier-id").value || null;
        const subtotal = parseFormattedNumber(document.getElementById("input-subtotal").value);
        const discount = parseFormattedNumber(document.getElementById("input-discount").value || "0");
        const shipping_cost = parseFormattedNumber(document.getElementById("input-shipping-cost").value || "0");
        const grand_total = parseFormattedNumber(document.getElementById("input-grand-total").value);

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
            shipping_cost,
            grand_total,
            items: saleItems
        };

        const btnSave = document.getElementById("btn-save");
        const originalBtnText = btnSave.innerHTML;
        btnSave.disabled = true;
        btnSave.innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Menyimpan...
        `;

        try {
            const response = await fetch("/sales", {
                method: "POST",
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

            if (!response.ok) throw new Error("Gagal menyimpan detail penjualan.");

            showToast("Penjualan berhasil dibuat!", "success");
            setTimeout(() => {
                window.location.href = "/sales";
            }, 1000);
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
        return String(str).replace(/&/g, "&amp;")
                          .replace(/</g, "&lt;")
                          .replace(/>/g, "&gt;")
                          .replace(/"/g, "&quot;")
                          .replace(/'/g, "&#039;");
    }
</script>
@endsection

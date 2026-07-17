@extends('layouts.administrator')

@section('title', 'Dashboard')

@section('content')
<div class="relative min-h-[calc(100vh-8rem)]">
    <!-- Header with Filters -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end gap-4 mb-8">
        <!-- Filters -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
            <!-- Counter Filter -->
            <div class="relative shrink-0">
                <input type="hidden" id="filter-counter-id" value="">
                <button id="dropdownFilterCounterButton" data-dropdown-toggle="dropdown-filter-counter" class="w-full sm:w-48 inline-flex items-center justify-between text-body bg-neutral-secondary-medium border border-default-medium hover:bg-neutral-tertiary focus:ring-4 focus:ring-neutral-tertiary-medium font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none cursor-pointer" type="button">
                    <span id="selected-counter-label">Semua Counter</span>
                    <svg class="w-4 h-4 ms-1.5 -me-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/></svg>
                </button>
                <div id="dropdown-filter-counter" class="z-10 hidden bg-neutral-primary-soft border border-default rounded-base shadow-lg w-48">
                    <ul class="p-2 text-sm text-body font-medium" id="filter-counter-options">
                        <li>
                            <button type="button" onclick="selectCounterFilter('', 'Semua Counter')" class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary hover:text-heading rounded text-left cursor-pointer">
                                Semua Counter
                            </button>
                        </li>
                        @foreach($counters as $counter)
                        <li>
                            <button type="button" onclick="selectCounterFilter('{{ $counter->id }}', '{{ $counter->name }}')" class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary hover:text-heading rounded text-left cursor-pointer">
                                {{ $counter->name }}
                            </button>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Category Filter -->
            <div class="relative shrink-0">
                <input type="hidden" id="filter-category-id" value="">
                <button id="dropdownFilterCategoryButton" data-dropdown-toggle="dropdown-filter-category" class="w-full sm:w-48 inline-flex items-center justify-between text-body bg-neutral-secondary-medium border border-default-medium hover:bg-neutral-tertiary focus:ring-4 focus:ring-neutral-tertiary-medium font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none cursor-pointer" type="button">
                    <span id="selected-category-label">Semua Kategori</span>
                    <svg class="w-4 h-4 ms-1.5 -me-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/></svg>
                </button>
                <div id="dropdown-filter-category" class="z-10 hidden bg-neutral-primary-soft border border-default rounded-base shadow-lg w-48">
                    <ul class="p-2 text-sm text-body font-medium" id="filter-category-options">
                        <li>
                            <button type="button" onclick="selectCategoryFilter('', 'Semua Kategori')" class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary hover:text-heading rounded text-left cursor-pointer">
                                Semua Kategori
                            </button>
                        </li>
                        @foreach($categories as $category)
                        <li>
                            <button type="button" onclick="selectCategoryFilter('{{ $category->id }}', '{{ $category->name }}')" class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary hover:text-heading rounded text-left cursor-pointer">
                                {{ $category->name }}
                            </button>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Widgets Row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Card 1: Total Omset -->
        <div class="bg-neutral-primary-soft p-6 rounded-xl border border-default shadow-xs flex items-center justify-between transition duration-200 hover:shadow-md">
            <div>
                <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Omset</h3>
                <p id="widget-total-omset" class="text-2xl font-bold text-heading mt-2">Rp 0</p>
            </div>
            <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.307a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />
                </svg>
            </div>
        </div>

        <!-- Card 2: Total Keuntungan -->
        <div class="bg-neutral-primary-soft p-6 rounded-xl border border-default shadow-xs flex items-center justify-between transition duration-200 hover:shadow-md">
            <div>
                <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Keuntungan (Estimasi)</h3>
                <p id="widget-total-keuntungan" class="text-2xl font-bold text-heading mt-2">Rp 0</p>
            </div>
            <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-1.95-.58-1-.727-1-1.912 0-2.64.95-.69 2.583-.787 3.73-.245L14 9M12 3v3m0 12v3" />
                </svg>
            </div>
        </div>

        <!-- Card 3: Barang Terjual -->
        <div class="bg-neutral-primary-soft p-6 rounded-xl border border-default shadow-xs flex items-center justify-between transition duration-200 hover:shadow-md">
            <div>
                <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Barang Terjual</h3>
                <p id="widget-total-qty" class="text-2xl font-bold text-heading mt-2">0 Unit</p>
            </div>
            <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Chart Row -->
    <div class="bg-neutral-primary-soft border border-default rounded-xl p-6 mb-8 shadow-xs">
        <div class="flex items-center gap-2 mb-6">
            <span class="inline-block w-1.5 h-5 bg-emerald-500 rounded-full"></span>
            <h2 class="text-sm font-bold text-heading uppercase tracking-wider">Grafik Penjualan</h2>
        </div>
        <div class="relative w-full h-80">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <!-- Bottom Tables Row (Produk Terlaris & Detail Transaksi Terakhir) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Left Column: Produk Terlaris -->
        <div class="bg-neutral-primary-soft border border-default rounded-xl p-6 shadow-xs flex flex-col justify-between min-h-[300px]">
            <div>
                <div class="flex items-center gap-2 mb-6">
                    <span class="inline-block w-1.5 h-5 bg-emerald-500 rounded-full"></span>
                    <h2 class="text-sm font-bold text-heading uppercase tracking-wider">Produk Terlaris</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-body">
                        <thead class="text-xs text-slate-400 font-semibold uppercase tracking-wider border-b border-default-medium">
                            <tr>
                                <th class="px-4 py-3 font-medium">Produk</th>
                                <th class="px-4 py-3 font-medium text-center">Terjual</th>
                                <th class="px-4 py-3 font-medium text-right">Omset</th>
                            </tr>
                        </thead>
                        <tbody id="best-sellers-body" class="divide-y divide-default-medium text-body">
                            <!-- Rendered dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="best-sellers-empty" class="hidden text-center py-12 text-sm text-slate-400">
                Belum ada data penjualan produk.
            </div>
        </div>

        <!-- Right Column: Detail Transaksi Terakhir -->
        <div class="bg-neutral-primary-soft border border-default rounded-xl p-6 shadow-xs flex flex-col justify-between min-h-[300px]">
            <div>
                <div class="flex items-center gap-2 mb-6">
                    <span class="inline-block w-1.5 h-5 bg-emerald-500 rounded-full"></span>
                    <h2 class="text-sm font-bold text-heading uppercase tracking-wider">Detail Transaksi Terakhir</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-body">
                        <thead class="text-xs text-slate-400 font-semibold uppercase tracking-wider border-b border-default-medium">
                            <tr>
                                <th class="px-4 py-3 font-medium">Tanggal</th>
                                <th class="px-4 py-3 font-medium">Invoice</th>
                                <th class="px-4 py-3 font-medium text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody id="latest-transactions-body" class="divide-y divide-default-medium text-body">
                            <!-- Rendered dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="latest-transactions-empty" class="hidden text-center py-12 text-sm text-slate-400">
                Belum ada transaksi terakhir.
            </div>
        </div>
    </div>
</div>

<!-- Load Chart.js from CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    let salesChart = null;

    document.addEventListener("DOMContentLoaded", function() {
        fetchDashboardData();
    });

    async function fetchDashboardData() {
        const counterId = document.getElementById('filter-counter-id').value;
        const categoryId = document.getElementById('filter-category-id').value;

        try {
            const response = await fetch(`/administrator/dashboard/stats?counter_id=${counterId}&category_id=${categoryId}`, {
                headers: {
                    'Accept': 'application/json'
                }
            });
            if (!response.ok) throw new Error("Gagal memuat data statistik.");
            
            const data = await response.json();
            
            // 1. Update Widget Cards
            document.getElementById('widget-total-omset').innerText = formatRupiah(data.summary.total_omset);
            document.getElementById('widget-total-keuntungan').innerText = formatRupiah(data.summary.total_keuntungan);
            document.getElementById('widget-total-qty').innerText = `${formatThousandSeparator(data.summary.total_qty)} Unit`;

            // 2. Update Sales Chart
            updateChart(data.chart);

            // 3. Update Best Sellers
            const bestSellersBody = document.getElementById('best-sellers-body');
            const bestSellersEmpty = document.getElementById('best-sellers-empty');
            if (data.best_sellers.length === 0) {
                bestSellersBody.innerHTML = '';
                bestSellersEmpty.classList.remove('hidden');
            } else {
                bestSellersEmpty.classList.add('hidden');
                let html = '';
                data.best_sellers.forEach(item => {
                    html += `
                        <tr class="hover:bg-neutral-secondary-medium/20 transition-colors duration-150">
                            <td class="px-4 py-3.5 font-medium text-heading">${escapeHtml(item.product_name)}</td>
                            <td class="px-4 py-3.5 text-center font-semibold text-body">${formatThousandSeparator(item.total_qty)}</td>
                            <td class="px-4 py-3.5 text-right font-semibold text-heading">${formatRupiah(item.total_omset)}</td>
                        </tr>
                    `;
                });
                bestSellersBody.innerHTML = html;
            }

            // 4. Update Latest Transactions
            const latestTransactionsBody = document.getElementById('latest-transactions-body');
            const latestTransactionsEmpty = document.getElementById('latest-transactions-empty');
            if (data.latest_transactions.length === 0) {
                latestTransactionsBody.innerHTML = '';
                latestTransactionsEmpty.classList.remove('hidden');
            } else {
                latestTransactionsEmpty.classList.add('hidden');
                let html = '';
                data.latest_transactions.forEach(item => {
                    html += `
                        <tr class="hover:bg-neutral-secondary-medium/20 transition-colors duration-150">
                            <td class="px-4 py-3.5 text-slate-500">${escapeHtml(formatDate(item.date))}</td>
                            <td class="px-4 py-3.5 font-semibold text-[#1e50d0]">${escapeHtml(item.barcode)}</td>
                            <td class="px-4 py-3.5 text-right font-bold text-heading">${formatRupiah(item.filtered_total)}</td>
                        </tr>
                    `;
                });
                latestTransactionsBody.innerHTML = html;
            }

        } catch (e) {
            console.error(e);
        }
    }

    function selectCounterFilter(id, name) {
        document.getElementById("filter-counter-id").value = id;
        document.getElementById("selected-counter-label").innerText = name;
        
        const button = document.getElementById("dropdownFilterCounterButton");
        if (button) {
            button.click();
        }
        fetchDashboardData();
    }

    function selectCategoryFilter(id, name) {
        document.getElementById("filter-category-id").value = id;
        document.getElementById("selected-category-label").innerText = name;
        
        const button = document.getElementById("dropdownFilterCategoryButton");
        if (button) {
            button.click();
        }
        fetchDashboardData();
    }

    function updateChart(chartData) {
        const labels = chartData.map(item => item.date);
        const dataPoints = chartData.map(item => item.omset);

        if (salesChart) {
            salesChart.data.labels = labels;
            salesChart.data.datasets[0].data = dataPoints;
            salesChart.update();
            return;
        }

        const ctx = document.getElementById('salesChart').getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 320);
        gradient.addColorStop(0, 'rgba(30, 80, 208, 0.2)');
        gradient.addColorStop(1, 'rgba(30, 80, 208, 0.0)');

        salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Omset',
                    data: dataPoints,
                    borderColor: '#1e50d0',
                    borderWidth: 3,
                    pointBackgroundColor: '#1e50d0',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return 'Omset: ' + formatRupiah(context.raw);
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                family: 'Instrument Sans, sans-serif',
                                size: 11
                            },
                            color: '#64748b'
                        }
                    },
                    y: {
                        border: {
                            dash: [5, 5]
                        },
                        grid: {
                            color: '#f1f5f9'
                        },
                        ticks: {
                            font: {
                                family: 'Instrument Sans, sans-serif',
                                size: 11
                            },
                            color: '#64748b',
                            callback: function(value) {
                                return formatRupiah(value);
                            }
                        }
                    }
                }
            }
        });
    }

    // Helper Functions
    function formatThousandSeparator(value) {
        if (value == null) return "0";
        let val = Math.round(parseFloat(value));
        if (isNaN(val)) return "0";
        return new Intl.NumberFormat("id-ID").format(val);
    }

    function formatRupiah(amount) {
        if (amount == null) return "Rp 0";
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(amount);
    }

    // Explicitly clean Rp currency formatting back to standard view style
    function formatRupiahString(amount) {
        return formatRupiah(amount).replace(/,00$/, "");
    }

    function escapeHtml(str) {
        if (!str) return '';
        return str.toString().replace(/&/g, "&amp;")
                  .replace(/</g, "&lt;")
                  .replace(/>/g, "&gt;")
                  .replace(/"/g, "&quot;")
                  .replace(/'/g, "&#039;");
    }

    function formatDate(dateString) {
        if (!dateString) return "";
        const d = new Date(dateString);
        if (isNaN(d.getTime())) return dateString;
        const day = d.getDate();
        const months = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];
        const month = months[d.getMonth()];
        const year = d.getFullYear();
        return `${day} ${month} ${year}`;
    }
</script>
@endsection

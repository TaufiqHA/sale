@extends('layouts.administrator')

@section('title', 'Dashboard')

@section('content')
    <!-- Quick stats -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 mb-8">
        
        <!-- Card 1 -->
        <div class="bg-white p-6 rounded-xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="p-3 bg-[#1e50d0]/10 text-[#1e50d0] rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm text-slate-400 font-medium">Total Revenue</p>
                <h3 class="text-2xl font-bold text-slate-800">Rp 12.450.000</h3>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white p-6 rounded-xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="p-3 bg-emerald-50 text-emerald-600 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm text-slate-400 font-medium">Total Orders</p>
                <h3 class="text-2xl font-bold text-slate-800">324</h3>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white p-6 rounded-xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="p-3 bg-amber-50 text-amber-600 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm text-slate-400 font-medium">Active Members</p>
                <h3 class="text-2xl font-bold text-slate-800">1,289</h3>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="bg-white p-6 rounded-xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="p-3 bg-purple-50 text-purple-600 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm text-slate-400 font-medium">Sales Growth</p>
                <h3 class="text-2xl font-bold text-slate-800">+12.3%</h3>
            </div>
        </div>

    </div>

    <!-- Recent Activities Section -->
    <div class="bg-white border border-slate-100 rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-lg font-bold text-slate-800">Recent Transactions</h2>
            <a href="#" class="text-sm font-semibold text-[#1e50d0] hover:text-[#1641b3]">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                        <th class="px-6 py-4">Transaction ID</th>
                        <th class="px-6 py-4">Customer</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4">Amount</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
                    <tr>
                        <td class="px-6 py-4 font-semibold text-[#1e50d0]">#TRX-94827</td>
                        <td class="px-6 py-4">Budi Setiadi</td>
                        <td class="px-6 py-4">16 Jul 2026</td>
                        <td class="px-6 py-4 font-medium">Rp 450.000</td>
                        <td class="px-6 py-4"><span class="px-2.5 py-1 text-xs font-semibold text-emerald-700 bg-emerald-50 rounded-full">Success</span></td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 font-semibold text-[#1e50d0]">#TRX-94826</td>
                        <td class="px-6 py-4">Siti Aminah</td>
                        <td class="px-6 py-4">16 Jul 2026</td>
                        <td class="px-6 py-4 font-medium">Rp 125.000</td>
                        <td class="px-6 py-4"><span class="px-2.5 py-1 text-xs font-semibold text-emerald-700 bg-emerald-50 rounded-full">Success</span></td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 font-semibold text-[#1e50d0]">#TRX-94825</td>
                        <td class="px-6 py-4">Agus Prasetyo</td>
                        <td class="px-6 py-4">15 Jul 2026</td>
                        <td class="px-6 py-4 font-medium">Rp 1.200.000</td>
                        <td class="px-6 py-4"><span class="px-2.5 py-1 text-xs font-semibold text-amber-700 bg-amber-50 rounded-full">Pending</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection

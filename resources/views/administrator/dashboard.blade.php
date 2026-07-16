@extends('layouts.administrator')

@section('title', 'Dashboard')

@section('content')
    <!-- Statistics Component -->
    <div class="w-full bg-neutral-primary border border-default rounded-base shadow-xs mb-8">
        <dl class="grid max-w-screen-xl grid-cols-2 gap-8 p-4 mx-auto text-heading sm:grid-cols-4 sm:p-8">
            <div class="flex flex-col">
                <dt class="mb-2 text-2xl font-semibold tracking-tight text-heading">Rp 12.450.000</dt>
                <dd class="text-body text-xs font-semibold uppercase tracking-wider text-slate-400">Total Revenue</dd>
            </div>
            <div class="flex flex-col">
                <dt class="mb-2 text-2xl font-semibold tracking-tight text-heading">324</dt>
                <dd class="text-body text-xs font-semibold uppercase tracking-wider text-slate-400">Total Orders</dd>
            </div>
            <div class="flex flex-col">
                <dt class="mb-2 text-2xl font-semibold tracking-tight text-heading">1,289</dt>
                <dd class="text-body text-xs font-semibold uppercase tracking-wider text-slate-400">Active Members</dd>
            </div>
            <div class="flex flex-col">
                <dt class="mb-2 text-2xl font-semibold tracking-tight text-heading">+12.3%</dt>
                <dd class="text-body text-xs font-semibold uppercase tracking-wider text-slate-400">Sales Growth</dd>
            </div>
        </dl>
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

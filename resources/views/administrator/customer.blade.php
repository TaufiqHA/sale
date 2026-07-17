@extends('layouts.administrator')

@section('title', 'Data Pelanggan')

@section('content')
<div class="relative min-h-[calc(100vh-8rem)]">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <!-- Search Form -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 flex-1 max-w-md">
            <form id="search-form" onsubmit="event.preventDefault(); handleSearchChange();" class="relative flex-1">
                <label for="search-input" class="block mb-2.5 text-sm font-medium text-heading sr-only">Cari</label>
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-body" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/></svg>
                    </div>
                    <input type="search" id="search-input" oninput="handleSearchChange()" class="block w-full p-3 ps-9 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand shadow-xs placeholder:text-body" placeholder="Cari Pelanggan..." />
                </div>
            </form>
        </div>

        <button onclick="openAddModal()" class="inline-flex items-center justify-center text-white bg-brand box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none cursor-pointer shrink-0 gap-1.5">
            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7 7V5"/>
            </svg>
            Tambah Pelanggan
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
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"></path>
            </svg>
        </div>
        <h3 class="text-base font-bold text-slate-800">Tidak Ada Pelanggan Dibuat</h3>
        <button onclick="openAddModal()" class="mt-4 inline-flex items-center justify-center text-white bg-brand box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none cursor-pointer gap-1.5">
            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7 7V5"/>
            </svg>
            Tambah Pelanggan
        </button>
    </div>

    <!-- Customer Table -->
    <div id="customer-table-wrapper" class="hidden relative overflow-x-auto bg-neutral-primary-soft shadow-xs rounded-base border border-default mb-8">
        <table class="w-full text-sm text-left rtl:text-right text-body">
            <thead class="text-sm text-body bg-neutral-secondary-medium border-b border-default-medium">
                <tr>
                    <th scope="col" class="px-6 py-3 font-medium">ID</th>
                    <th scope="col" class="px-6 py-3 font-medium">Nama Pelanggan</th>
                    <th scope="col" class="px-6 py-3 font-medium">Counter</th>
                    <th scope="col" class="px-6 py-3 font-medium">No Telepon</th>
                    <th scope="col" class="px-6 py-3 font-medium">Alamat</th>
                    <th scope="col" class="px-6 py-3 font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody id="customer-table-body">
                <!-- Rendered dynamically -->
            </tbody>
        </table>
    </div>
</div>

<!-- Modal (Create / Edit) -->
<div id="customer-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden" role="dialog" aria-modal="true">
    <!-- Backdrop with blur -->
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300 opacity-0" id="modal-backdrop" onclick="closeModal()"></div>

    <!-- Modal content wrapper -->
    <div class="relative w-full max-w-lg max-h-[95vh] flex flex-col transform scale-95 opacity-0 transition-all duration-300" id="modal-panel">
        <div class="relative bg-neutral-primary-soft border border-default rounded-base shadow-sm p-4 md:p-6 flex flex-col overflow-hidden max-h-full">
            <!-- Modal header -->
            <div class="flex items-center justify-between border-b border-default pb-4 md:pb-5 shrink-0">
                <h3 id="modal-title" class="text-lg font-medium text-heading">
                    Tambah Pelanggan
                </h3>
                <button type="button" onclick="closeModal()" class="text-body bg-transparent hover:bg-neutral-tertiary hover:text-heading rounded-base text-sm w-9 h-9 ms-auto inline-flex justify-center items-center cursor-pointer">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/></svg>
                    <span class="sr-only">Tutup modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <form id="customer-form" onsubmit="handleFormSubmit(event)" class="overflow-y-auto flex-1 pr-1">
                <input type="hidden" id="customer-id" name="id">

                <div class="grid gap-4 grid-cols-2 py-4 md:py-6">
                    <!-- Counter Select -->
                    <div class="col-span-2">
                        <label for="input-counter-id" class="block mb-2.5 text-sm font-medium text-heading">Counter</label>
                        <select id="input-counter-id" name="counter_id" class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs" required>
                            <option value="">Pilih Counter...</option>
                        </select>
                        <p id="error-counter_id" class="mt-2 text-xs font-medium text-rose-500 hidden"></p>
                    </div>

                    <!-- Name -->
                    <div class="col-span-2 sm:col-span-1">
                        <label for="input-name" class="block mb-2.5 text-sm font-medium text-heading">Nama Pelanggan</label>
                        <input type="text" id="input-name" name="name" class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body" placeholder="contoh: John Doe" required>
                        <p id="error-name" class="mt-2 text-xs font-medium text-rose-500 hidden"></p>
                    </div>

                    <!-- Phone -->
                    <div class="col-span-2 sm:col-span-1">
                        <label for="input-phone" class="block mb-2.5 text-sm font-medium text-heading">No Telepon</label>
                        <input type="text" id="input-phone" name="phone" class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body" placeholder="contoh: 08123456789" required>
                        <p id="error-phone" class="mt-2 text-xs font-medium text-rose-500 hidden"></p>
                    </div>

                    <!-- Address -->
                    <div class="col-span-2">
                        <label for="input-address" class="block mb-2.5 text-sm font-medium text-heading">Alamat</label>
                        <textarea id="input-address" name="address" rows="3" class="block bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full p-3.5 shadow-xs placeholder:text-body" placeholder="contoh: Jl. Raya No. 1" required></textarea>
                        <p id="error-address" class="mt-2 text-xs font-medium text-rose-500 hidden"></p>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex items-center space-x-4 border-t border-default pt-4 md:pt-6">
                    <button type="submit" id="btn-save" class="inline-flex items-center text-white bg-brand hover:bg-brand-strong box-border border border-transparent focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none cursor-pointer">
                        <svg class="w-4 h-4 me-1.5 -ms-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7 7V5"/></svg>
                        Simpan Pelanggan
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
                <h3 class="text-base font-bold text-slate-800">Hapus Pelanggan</h3>
                <p class="text-sm text-slate-500 mt-1">Apakah Anda yakin ingin menghapus pelanggan <span id="delete-customer-name" class="font-semibold text-slate-700"></span>? Tindakan ini tidak dapat dibatalkan.</p>
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

<!-- Toast System -->
<div id="toast-container" class="fixed top-6 right-6 z-50 flex flex-col gap-3 pointer-events-none"></div>

<!-- JavaScript Logic -->
<script>
    const csrfToken = "{{ csrf_token() }}";
    let activeCustomers = [];
    let activeCounters = [];
    let customerToDelete = null;

    document.addEventListener("DOMContentLoaded", () => {
        fetchCounters();
        fetchCustomers();
    });

    async function fetchCounters() {
        try {
            const response = await fetch("/counters", {
                headers: {
                    "Accept": "application/json",
                }
            });

            if (!response.ok) throw new Error("Gagal mengambil data counter.");

            activeCounters = await response.json();
            populateCounterDropdown();
        } catch (error) {
            showToast("Gagal memuat counter. Silakan muat ulang.", "error");
        }
    }

    function populateCounterDropdown() {
        const select = document.getElementById("input-counter-id");
        select.innerHTML = '<option value="">Pilih Counter...</option>';
        activeCounters.forEach(counter => {
            if (counter.status) {
                const option = document.createElement("option");
                option.value = counter.id;
                option.textContent = counter.name;
                select.appendChild(option);
            }
        });
    }

    async function fetchCustomers() {
        const skeleton = document.getElementById("loading-skeleton");
        const emptyState = document.getElementById("empty-state");
        const tableWrapper = document.getElementById("customer-table-wrapper");

        skeleton.classList.remove("hidden");
        tableWrapper.classList.add("hidden");
        emptyState.classList.add("hidden");

        try {
            const response = await fetch("/customers", {
                headers: {
                    "Accept": "application/json",
                }
            });

            if (!response.ok) throw new Error("Gagal mengambil data pelanggan.");

            activeCustomers = await response.json();
            renderCustomers();
        } catch (error) {
            showToast("Gagal memuat pelanggan. Silakan coba lagi.", "error");
        } finally {
            skeleton.classList.add("hidden");
        }
    }

    function renderCustomers() {
        const emptyState = document.getElementById("empty-state");
        const tableWrapper = document.getElementById("customer-table-wrapper");
        const tbody = document.getElementById("customer-table-body");

        tbody.innerHTML = "";

        const searchInput = document.getElementById("search-input");
        const searchQuery = searchInput ? searchInput.value.toLowerCase().trim() : "";

        // Filter customers
        const filteredCustomers = activeCustomers.filter(customer => {
            if (searchQuery) {
                const nameMatch = customer.name ? customer.name.toLowerCase().includes(searchQuery) : false;
                const phoneMatch = customer.phone ? customer.phone.toLowerCase().includes(searchQuery) : false;
                const addressMatch = customer.address ? customer.address.toLowerCase().includes(searchQuery) : false;
                const counterMatch = (customer.counter && customer.counter.name) ? customer.counter.name.toLowerCase().includes(searchQuery) : false;
                if (!nameMatch && !phoneMatch && !addressMatch && !counterMatch) {
                    return false;
                }
            }
            return true;
        });

        if (activeCustomers.length === 0) {
            emptyState.classList.remove("hidden");
            tableWrapper.classList.add("hidden");
            return;
        }

        emptyState.classList.add("hidden");
        tableWrapper.classList.remove("hidden");

        if (filteredCustomers.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-body opacity-60">
                        <div class="flex flex-col items-center justify-center gap-2">
                            <svg class="w-8 h-8 opacity-40 text-body" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <span class="text-sm font-semibold">Tidak ada pelanggan yang cocok dengan kriteria Anda.</span>
                        </div>
                    </td>
                </tr>
            `;
            return;
        }

        filteredCustomers.forEach(customer => {
            const row = document.createElement("tr");
            row.className = "bg-neutral-primary-soft border-b border-default hover:bg-neutral-secondary-medium transition-colors duration-150";

            const counterName = customer.counter ? escapeHtml(customer.counter.name) : '<span class="text-body opacity-50 font-normal">Tanpa Counter</span>';

            row.innerHTML = `
                <td class="px-6 py-4 font-semibold text-body">#${customer.id}</td>
                <th scope="row" class="px-6 py-4 font-medium text-heading whitespace-nowrap text-left">${escapeHtml(customer.name)}</th>
                <td class="px-6 py-4 text-xs font-semibold text-body">${counterName}</td>
                <td class="px-6 py-4 text-xs font-semibold text-body">${escapeHtml(customer.phone)}</td>
                <td class="px-6 py-4 text-xs max-w-[250px] truncate" title="${escapeHtml(customer.address)}">${escapeHtml(customer.address)}</td>
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end gap-3">
                        <button onclick="openEditModal(${customer.id})" class="font-medium text-fg-brand hover:underline cursor-pointer" title="Ubah">Ubah</button>
                        <button onclick="openDeleteModal(${customer.id}, '${escapeQuote(customer.name)}')" class="font-medium text-fg-danger hover:underline cursor-pointer" title="Hapus">Hapus</button>
                    </div>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    function handleSearchChange() {
        renderCustomers();
    }

    // Modal Helpers
    function openAddModal() {
        document.getElementById("modal-title").innerText = "Tambah Pelanggan Baru";
        document.getElementById("customer-id").value = "";
        document.getElementById("customer-form").reset();

        clearValidationErrors();
        showModal();
    }

    function openEditModal(id) {
        const customer = activeCustomers.find(c => c.id === id);
        if (!customer) return;

        document.getElementById("modal-title").innerText = "Ubah Pelanggan";
        document.getElementById("customer-id").value = customer.id;
        document.getElementById("input-counter-id").value = customer.counter_id;
        document.getElementById("input-name").value = customer.name;
        document.getElementById("input-phone").value = customer.phone;
        document.getElementById("input-address").value = customer.address;

        clearValidationErrors();
        showModal();
    }

    function showModal() {
        const modal = document.getElementById("customer-modal");
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
        const modal = document.getElementById("customer-modal");
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

        const id = document.getElementById("customer-id").value;
        const counter_id = document.getElementById("input-counter-id").value;
        const name = document.getElementById("input-name").value;
        const phone = document.getElementById("input-phone").value;
        const address = document.getElementById("input-address").value;

        const data = { counter_id, name, phone, address };
        const isEdit = !!id;
        const url = isEdit ? `/customers/${id}` : "/customers";
        const method = isEdit ? "PUT" : "POST";

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
                // Validation error
                showValidationErrors(result.errors);
                return;
            }

            if (!response.ok) throw new Error("Gagal menyimpan detail pelanggan.");

            showToast(`Pelanggan "${name}" berhasil ${isEdit ? 'diperbarui' : 'dibuat'}!`, "success");
            closeModal();
            fetchCustomers();
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

    // Clear Errors
    function clearValidationErrors() {
        const errorElements = document.querySelectorAll("[id^='error-']");
        errorElements.forEach(el => {
            el.innerText = "";
            el.classList.add("hidden");
        });

        const inputs = document.querySelectorAll("#customer-form input, #customer-form textarea, #customer-form select");
        inputs.forEach(input => {
            input.classList.remove("border-rose-500", "focus:border-rose-500", "focus:ring-rose-500/10");
        });
    }

    // Delete Flow
    function openDeleteModal(id, name) {
        customerToDelete = id;
        document.getElementById("delete-customer-name").innerText = name;

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
            customerToDelete = null;
        }, 300);
    }

    async function handleDeleteConfirm() {
        if (!customerToDelete) return;

        const btnDelete = document.getElementById("btn-delete");
        const originalText = btnDelete.innerText;
        btnDelete.disabled = true;
        btnDelete.innerText = "Menghapus...";

        try {
            const response = await fetch(`/customers/${customerToDelete}`, {
                method: "DELETE",
                headers: {
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                }
            });

            if (!response.ok) throw new Error("Gagal menghapus pelanggan.");

            showToast("Pelanggan berhasil dihapus.", "success");
            closeDeleteModal();
            fetchCustomers();
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
        return str.replace(/&/g, "&amp;")
                  .replace(/</g, "&lt;")
                  .replace(/>/g, "&gt;")
                  .replace(/"/g, "&quot;")
                  .replace(/'/g, "&#039;");
    }

    function escapeQuote(str) {
        if (!str) return '';
        return str.replace(/'/g, "\\'");
    }
</script>
@endsection

@extends('layouts.administrator')

@section('title', 'Counter Management')

@section('content')
<div class="relative min-h-[calc(100vh-8rem)]">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        {{-- <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Counter Management</h1>
            <p class="text-sm text-slate-500 font-medium mt-1">Manage physical registers, checkout desks, and their operational status.</p>
        </div> --}}
        <button onclick="openAddModal()" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-[#1e50d0] hover:bg-[#1641b3] active:scale-[0.98] text-white text-sm font-semibold rounded-xl transition duration-200 shadow-sm shadow-[#1e50d0]/20 cursor-pointer">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
            </svg>
            Add Counter
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
                <div class="h-4 bg-slate-100 rounded-md w-48 shrink-0 flex-1"></div>
                <div class="h-4 bg-slate-100 rounded-md w-28 shrink-0"></div>
                <div class="h-4 bg-slate-100 rounded-md w-40 shrink-0 flex-1"></div>
                <div class="h-6 bg-slate-100 rounded-full w-16 shrink-0"></div>
                <div class="h-8 bg-slate-100 rounded-lg w-20 shrink-0"></div>
            </div>
            @endfor
        </div>
    </div>

    <!-- Empty State -->
    <div id="empty-state" class="hidden flex-col items-center justify-center py-16 px-4 bg-white border border-slate-100 rounded-2xl shadow-sm mb-8 text-center">
        <div class="p-4 bg-slate-50 text-slate-400 rounded-2xl mb-4">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25"></path>
            </svg>
        </div>
        <h3 class="text-base font-bold text-slate-800">No Counters Created</h3>
        <p class="text-sm text-slate-500 max-w-sm mt-1">Get started by creating your first register or checkout counter location.</p>
        <button onclick="openAddModal()" class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-[#1e50d0]/10 hover:bg-[#1e50d0]/20 text-[#1e50d0] text-sm font-semibold rounded-xl transition duration-150 cursor-pointer">
            Create Counter
        </button>
    </div>

    <!-- Counter Table -->
    <div id="counter-table-wrapper" class="hidden bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden mb-8">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Counter Name</th>
                        <th class="px-6 py-4">Location / Address</th>
                        <th class="px-6 py-4">Phone</th>
                        <th class="px-6 py-4">Description</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="counter-table-body" class="divide-y divide-slate-100 text-sm text-slate-600">
                    <!-- Rendered dynamically -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal (Create / Edit) -->
<div id="counter-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden" role="dialog" aria-modal="true">
    <!-- Backdrop with blur -->
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300 opacity-0" id="modal-backdrop" onclick="closeModal()"></div>
    
    <!-- Modal content -->
    <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden transform scale-95 opacity-0 transition-all duration-300" id="modal-panel">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
            <h3 id="modal-title" class="text-lg font-bold text-slate-800">Add Counter</h3>
            <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600 transition p-1.5 rounded-lg hover:bg-slate-50">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <form id="counter-form" onsubmit="handleFormSubmit(event)" class="p-6 space-y-4">
            <input type="hidden" id="counter-id" name="id">
            
            <!-- Name -->
            <div class="space-y-1.5">
                <label for="input-name" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Counter Name</label>
                <input type="text" id="input-name" name="name" placeholder="e.g. Cashier 1" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#1e50d0] focus:ring-2 focus:ring-[#1e50d0]/10 transition duration-150 text-sm placeholder:text-slate-400" required>
                <p id="error-name" class="text-xs font-medium text-rose-500 hidden"></p>
            </div>

            <!-- Phone -->
            <div class="space-y-1.5">
                <label for="input-phone" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Phone</label>
                <input type="text" id="input-phone" name="phone" placeholder="e.g. 08123456789" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#1e50d0] focus:ring-2 focus:ring-[#1e50d0]/10 transition duration-150 text-sm placeholder:text-slate-400">
                <p id="error-phone" class="text-xs font-medium text-rose-500 hidden"></p>
            </div>

            <!-- Address -->
            <div class="space-y-1.5">
                <label for="input-address" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Address / Location</label>
                <textarea id="input-address" name="address" rows="2" placeholder="e.g. 1st Floor, Main Hall" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#1e50d0] focus:ring-2 focus:ring-[#1e50d0]/10 transition duration-150 text-sm placeholder:text-slate-400 resize-none"></textarea>
                <p id="error-address" class="text-xs font-medium text-rose-500 hidden"></p>
            </div>

            <!-- Description -->
            <div class="space-y-1.5">
                <label for="input-description" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Description</label>
                <textarea id="input-description" name="description" rows="2" placeholder="Describe register purpose, layout, or restrictions..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#1e50d0] focus:ring-2 focus:ring-[#1e50d0]/10 transition duration-150 text-sm placeholder:text-slate-400 resize-none"></textarea>
                <p id="error-description" class="text-xs font-medium text-rose-500 hidden"></p>
            </div>

            <!-- Status Checkbox -->
            <div class="flex items-center gap-3 py-2">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="input-status" name="status" class="sr-only peer" checked>
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-[#1e50d0]/10 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#1e50d0]"></div>
                    <span class="ml-3 text-sm font-semibold text-slate-700">Active</span>
                </label>
                <p id="error-status" class="text-xs font-medium text-rose-500 hidden"></p>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeModal()" class="px-4 py-2.5 text-sm font-semibold text-slate-500 hover:text-slate-800 hover:bg-slate-50 rounded-xl transition duration-150">
                    Cancel
                </button>
                <button type="submit" id="btn-save" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-[#1e50d0] hover:bg-[#1641b3] text-white text-sm font-semibold rounded-xl transition duration-150">
                    Save Counter
                </button>
            </div>
        </form>
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
                <h3 class="text-base font-bold text-slate-800">Delete Counter</h3>
                <p class="text-sm text-slate-500 mt-1">Are you sure you want to delete <span id="delete-counter-name" class="font-semibold text-slate-700"></span>? This action cannot be undone.</p>
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
    let activeCounters = [];
    let counterToDelete = null;

    document.addEventListener("DOMContentLoaded", () => {
        fetchCounters();
    });

    async function fetchCounters() {
        const skeleton = document.getElementById("loading-skeleton");
        const emptyState = document.getElementById("empty-state");
        const tableWrapper = document.getElementById("counter-table-wrapper");

        skeleton.classList.remove("hidden");
        tableWrapper.classList.add("hidden");
        emptyState.classList.add("hidden");

        try {
            const response = await fetch("/counters", {
                headers: {
                    "Accept": "application/json",
                }
            });

            if (!response.ok) throw new Error("Failed to fetch counters.");

            activeCounters = await response.json();
            renderCounters();
        } catch (error) {
            showToast("Failed loading counters. Please try again.", "error");
        } finally {
            skeleton.classList.add("hidden");
        }
    }

    function renderCounters() {
        const emptyState = document.getElementById("empty-state");
        const tableWrapper = document.getElementById("counter-table-wrapper");
        const tbody = document.getElementById("counter-table-body");
        
        tbody.innerHTML = "";

        if (activeCounters.length === 0) {
            emptyState.classList.remove("hidden");
            tableWrapper.classList.add("hidden");
            return;
        }

        emptyState.classList.add("hidden");
        tableWrapper.classList.remove("hidden");

        activeCounters.forEach(counter => {
            const row = document.createElement("tr");
            row.className = "hover:bg-slate-50/50 transition-colors duration-150";
            
            const badgeClass = counter.status 
                ? "bg-emerald-50 text-emerald-700 font-semibold" 
                : "bg-slate-100 text-slate-500 font-medium";
            const badgeText = counter.status ? "Active" : "Inactive";

            row.innerHTML = `
                <td class="px-6 py-4 font-semibold text-slate-400">#${counter.id}</td>
                <td class="px-6 py-4 font-bold text-slate-800">${escapeHtml(counter.name)}</td>
                <td class="px-6 py-4 text-xs max-w-[200px] truncate" title="${escapeHtml(counter.address || '')}">
                    ${counter.address ? escapeHtml(counter.address) : '<span class="text-slate-300 font-normal">No address</span>'}
                </td>
                <td class="px-6 py-4 text-xs font-semibold text-slate-600">
                    ${counter.phone ? escapeHtml(counter.phone) : '<span class="text-slate-300 font-normal">No phone</span>'}
                </td>
                <td class="px-6 py-4 text-xs max-w-[250px] truncate" title="${escapeHtml(counter.description || '')}">
                    ${counter.description ? escapeHtml(counter.description) : '<span class="text-slate-300 font-normal">No description</span>'}
                </td>
                <td class="px-6 py-4">
                    <span class="px-2.5 py-1 text-[11px] rounded-full ${badgeClass}">
                        ${badgeText}
                    </span>
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end gap-1.5">
                        <button onclick="openEditModal(${counter.id})" class="inline-flex items-center justify-center p-2 text-[#1e50d0] hover:bg-[#1e50d0]/5 rounded-xl transition duration-150 cursor-pointer" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"></path>
                            </svg>
                        </button>
                        <button onclick="openDeleteModal(${counter.id}, '${escapeQuote(counter.name)}')" class="inline-flex items-center justify-center p-2 text-rose-600 hover:bg-rose-50 rounded-xl transition duration-150 cursor-pointer" title="Delete">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"></path>
                            </svg>
                        </button>
                    </div>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    // Modal Helpers
    function openAddModal() {
        document.getElementById("modal-title").innerText = "Add New Counter";
        document.getElementById("counter-id").value = "";
        document.getElementById("counter-form").reset();
        document.getElementById("input-status").checked = true;
        
        clearValidationErrors();
        showModal();
    }

    function openEditModal(id) {
        const counter = activeCounters.find(c => c.id === id);
        if (!counter) return;

        document.getElementById("modal-title").innerText = "Edit Counter";
        document.getElementById("counter-id").value = counter.id;
        document.getElementById("input-name").value = counter.name;
        document.getElementById("input-phone").value = counter.phone || "";
        document.getElementById("input-address").value = counter.address || "";
        document.getElementById("input-description").value = counter.description || "";
        document.getElementById("input-status").checked = !!counter.status;

        clearValidationErrors();
        showModal();
    }

    function showModal() {
        const modal = document.getElementById("counter-modal");
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
        const modal = document.getElementById("counter-modal");
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

        const id = document.getElementById("counter-id").value;
        const name = document.getElementById("input-name").value;
        const phone = document.getElementById("input-phone").value;
        const address = document.getElementById("input-address").value;
        const description = document.getElementById("input-description").value;
        const status = document.getElementById("input-status").checked;

        const data = { name, phone, address, description, status };
        const isEdit = !!id;
        const url = isEdit ? `/counters/${id}` : "/counters";
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
                // Validation error
                showValidationErrors(result.errors);
                return;
            }

            if (!response.ok) throw new Error("Could not save counter details.");

            showToast(`Counter "${name}" successfully ${isEdit ? 'updated' : 'created'}!`, "success");
            closeModal();
            fetchCounters();
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

        const inputs = document.querySelectorAll("#counter-form input, #counter-form textarea");
        inputs.forEach(input => {
            input.classList.remove("border-rose-500", "focus:border-rose-500", "focus:ring-rose-500/10");
        });
    }

    // Delete Flow
    function openDeleteModal(id, name) {
        counterToDelete = id;
        document.getElementById("delete-counter-name").innerText = name;

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
            counterToDelete = null;
        }, 300);
    }

    async function handleDeleteConfirm() {
        if (!counterToDelete) return;

        const btnDelete = document.getElementById("btn-delete");
        const originalText = btnDelete.innerText;
        btnDelete.disabled = true;
        btnDelete.innerText = "Deleting...";

        try {
            const response = await fetch(`/counters/${counterToDelete}`, {
                method: "DELETE",
                headers: {
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                }
            });

            if (!response.ok) throw new Error("Failed to delete counter.");

            showToast("Counter successfully deleted.", "success");
            closeDeleteModal();
            fetchCounters();
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

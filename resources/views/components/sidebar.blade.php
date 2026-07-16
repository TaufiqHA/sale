
<aside id="default-sidebar" class="fixed top-0 left-0 z-50 w-64 h-full transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
   <div class="h-full px-4 py-6 overflow-y-auto bg-[#1e50d0] text-white border-e border-white/10 flex flex-col justify-between">
      <div>
         <!-- Logo Section -->
         <div class="flex items-center gap-3 px-2 mb-8">
             <svg class="w-8 h-8 stroke-white fill-none" viewBox="0 0 100 100" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
                 <path d="M32 25 L43 57 L68 57 L74 37 L78 37" />
                 <circle cx="48" cy="65" r="3" />
                 <circle cx="63" cy="65" r="3" />
                 <path d="M56 31 L56 50" />
                 <path d="M50 37 L56 31 L62 37" />
             </svg>
             <span class="font-bold text-lg text-white tracking-wider uppercase">SALE POS</span>
         </div>

         <ul class="space-y-1 font-medium">
            <li>
               <a href="{{ route('administrator.dashboard') }}" class="flex items-center px-3 py-2.5 rounded-md transition-all group {{ request()->routeIs('administrator.dashboard') ? 'bg-[#1641b3] text-white font-semibold shadow-sm' : 'text-white/85 hover:bg-[#1641b3] hover:text-white font-medium' }}">
                  <svg class="w-5 h-5 transition duration-75 {{ request()->routeIs('administrator.dashboard') ? 'text-white' : 'text-white/70 group-hover:text-white' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                     <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6.025A7.5 7.5 0 1 0 17.975 14H10V6.025Z"/>
                     <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 3c-.169 0-.334.014-.5.025V11h7.975c.011-.166.025-.331.025-.5A7.5 7.5 0 0 0 13.5 3Z"/>
                  </svg>
                  <span class="ms-3">Dashboard</span>
               </a>
            </li>
            <li>
               <a href="{{ route('counters.index') }}" class="flex items-center px-3 py-2.5 rounded-md transition-all group {{ request()->routeIs('counters.*') ? 'bg-[#1641b3] text-white font-semibold shadow-sm' : 'text-white/85 hover:bg-[#1641b3] hover:text-white font-medium' }}">
                  <svg class="shrink-0 w-5 h-5 transition duration-75 {{ request()->routeIs('counters.*') ? 'text-white' : 'text-white/70 group-hover:text-white' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                     <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v14M9 5v14M4 5h16a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z"/>
                  </svg>
                  <span class="flex-1 ms-3 whitespace-nowrap">Counters</span>
               </a>
            </li>
            <li>
               <a href="{{ route('products.index') }}" class="flex items-center px-3 py-2.5 rounded-md transition-all group {{ request()->routeIs('products.*') ? 'bg-[#1641b3] text-white font-semibold shadow-sm' : 'text-white/85 hover:bg-[#1641b3] hover:text-white font-medium' }}">
                  <svg class="shrink-0 w-5 h-5 transition duration-75 {{ request()->routeIs('products.*') ? 'text-white' : 'text-white/70 group-hover:text-white' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                     <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10V6a3 3 0 0 1 3-3v0a3 3 0 0 1 3 3v4m3-2 .917 11.923A1 1 0 0 1 17.92 21H6.08a1 1 0 0 1-.997-1.077L6 8h12Z"/>
                  </svg>
                  <span class="flex-1 ms-3 whitespace-nowrap">Products</span>
               </a>
            </li>
            <li>
               <a href="{{ route('customers.index') }}" class="flex items-center px-3 py-2.5 rounded-md transition-all group {{ request()->routeIs('customers.*') ? 'bg-[#1641b3] text-white font-semibold shadow-sm' : 'text-white/85 hover:bg-[#1641b3] hover:text-white font-medium' }}">
                  <svg class="shrink-0 w-5 h-5 transition duration-75 {{ request()->routeIs('customers.*') ? 'text-white' : 'text-white/70 group-hover:text-white' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                     <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17v1a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-1a3 3 0 0 0-3-3h-4a3 3 0 0 0-3 3Zm8-9a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                  </svg>
                  <span class="flex-1 ms-3 whitespace-nowrap">Customers</span>
               </a>
            </li>
         </ul>
      </div>

      <!-- User Profile & Logout at bottom -->
      <div class="p-2 border-t border-white/10 flex flex-col gap-3 mt-auto">
          <div class="flex items-center gap-3">
              <div class="h-9 w-9 rounded-full bg-white/10 flex items-center justify-center text-white">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                  </svg>
              </div>
              <div class="truncate flex-1">
                  <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                  <p class="text-xs text-white/60 capitalize">{{ auth()->user()->role }}</p>
              </div>
          </div>
          <form action="{{ route('logout') }}" method="POST" class="w-full">
              @csrf
              <button type="submit" class="w-full flex items-center justify-center gap-2 px-3 py-2 text-sm font-semibold text-white bg-white/10 hover:bg-white/20 active:scale-[0.98] rounded-md transition duration-150 cursor-pointer border-0">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1"></path>
                  </svg>
                  Logout
              </button>
          </form>
      </div>
   </div>
</aside>

<div>
   <h1 class="container title !text-left pb-5">{{_t("Catalog")}}</h1>
   <div class="pt-6 pb-11 mb-10 md:mb-12 lg:mb-20 relative bg-base-200 after:size-12 max-lg:after:hidden after:block after:absolute after:bottom-0 after:left-1/2 after:translate-y-1/2 after:-translate-x-1/2 after:rotate-45 after:bg-base-200">
      <div class="container overflow-x-auto">
         <div class="flex justify-center gap-x-8 items-center">
            @foreach ($categories as $cat)
            <x-link href="{{$cat->route()}}" title="{{$cat->name}}"
               class="flex flex-col justify-center items-center gap-y-6 group @if($page->id == $cat->id) active @endif">
               <div class="size-24 s:size-28 lg:size-36">
                  <x-image src="{{$cat->image}}" alt="{{$cat->name}}" width="144" heigth="144"
                     class="size-full object-contain transition-all duration-200 group-hover:scale-105" />
               </div>
               <h3
                  class="text-xl s:text-2xl line-clamp-2 h-[2.666em] text-white text-center duration-300 ease-linear group-hover:text-accent @if($page->id == $cat->id) !text-accent @endif">
                  {{$cat->name}}
               </h3>
            </x-link>
            @endforeach
         </div>
      </div>
   </div>
   <div x-data="{ open: false }" class="wrapper">
      <div class="container">
         <div class="flex justify-between items-center gap-4 flex-wrap mb-8">
            <h1 class="title !text-left">
               {{$page->seo->heading ?? $page->name()}}
            </h1>
            <button @click="open = true; document.querySelector('body').classList.add('block')"
               class="btn btn-dark lg:hidden" aria-label="{{ _t('Open filter') }}">
               <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round"
                     d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
               </svg>
               {{ _t('Filter') }}
            </button>
         </div>
         <div class="grid lg:grid-cols-4 gap-y-6 gap-x-8 items-start">
            <div class="max-lg:hidden">
               <fieldset class="pt-6 pl-5 pb-7 pr-3 bg-base-200 divide-y divide-base-content"
                  aria-label="{{ _t('Filter') }}">
                  <div class="space-y-8 pb-6">
                     @foreach ($categories as $key => $category)
                     <x-link href="{{$category->route()}}" title="{{$category->name()}}" class="checkbox-input select-none" wire:click="filterCategory({{$category->id}})">
                        <input type="checkbox" id="filter_{{$key}}" name="filter_{{$key}}" wire:model="activeCategories"
                           value="{{$category->id}}"">
                        <label for=" filter_{{$key}}">
                        <div>
                           <svg class="size-3" viewBox="0 0 12 11" fill="none"
                              xmlns="http://www.w3.orwg/2000/svg">
                              <path
                                 d="M3.81934 11L3.75333 10.8866C2.74506 9.15401 0.0657143 5.47725 0.0386502 5.44032L0 5.38737L0.912838 4.47753L3.80238 6.51245C5.62172 4.13144 7.31904 2.49605 8.42619 1.54105C9.63732 0.496367 10.4257 0.0154316 10.4336 0.010823L10.4516 0H12L11.8521 0.132848C8.04811 3.54997 3.925 10.8127 3.88393 10.8857L3.81934 11Z"
                                 fill="currentColor" />
                           </svg>
                        </div>
                        <span>{{$category->name()}}</span>
                        </label>
                     </x-link>
                     @endforeach
                     <div class="pt-6">
                        <span class="block mb-4 text-base-content">Ціна, грн</span>
                        <div class="double-range">
                           <div class="text-input">
                              <input type="number" name="min" wire:model="min_price" wire:change="filter()" />
                              <div class="separator"></div>
                              <input type="number" name="max" wire:model="max_price" wire:change="filter()" />
                           </div>
                           <div class="range-slider">
                              <span class="range-fill"></span>
                           </div>
                           <div class="range-input">
                              <input type="range" wire:model="min_price" class="min"
                                 step="1" wire:change="filter()" />
                              <input type="range" class="max" min="0" max="100" wire:model="max_price" wire:change="filter()"
                                 step="1" />
                           </div>
                        </div>
                     </div>
               </fieldset>
            </div>
            <div class="lg:col-span-3 grid s:grid-cols-2 md:grid-cols-3 gap-y-7 gap-x-8">
               @foreach ($products as $product)
               <div class="p-5 bg-base-200 space-y-5 transition-all duration-200 shadow-[-1px_0px_8px_0px_#00000024] shadow-transparent hover:shadow-main hover:scale-105 flex flex-col">
                  <x-link href="{{$product->route()}}" title="{{$product->name}}" class="block w-full h-40 sm:h-48 xl:h-52">
                     <x-image src="{{$product->image}}" alt="{{$product->name}}" width="268" heigth="256" class="size-full object-contain" />
                  </x-link>
                  <div class="space-y-5">
                     <x-link href="{{$product->route()}}" title="{{$product->name}}" class="text-lg font-light uppercase text-base-content block line-clamp-2 hover:text-accent ease-linear duration-300">
                        {{$product->name()}}
                     </x-link>
                     <div class="flex justify-between items-center gap-y-5 gap-x-3 flex-wrap">
                        <div class="w-full xs:w-auto s:w-full sm:w-auto md:w-full xl:w-auto flex xs:flex-col s:flex-row sm:flex-col md:flex-row xl:flex-col justify-between items-end xs:items-start s:items-end sm:items-start md:items-end xl:items-start gap-x-1">
                           <div class="text-sm *:font-light text-base-content space-x-1">
                              <span>Ціна:</span>
                              <span>{{$product->price}} {{app('currency')->name}} </span>
                           </div>
                           <span class="text-sm font-light text-neutral-content">за 100 гр</span>
                        </div>
                        @if (module_enabled('Order'))
                        <button class="btn btn-dark w-full xs:w-auto s:w-full sm:w-auto md:w-full xl:w-auto text-sm" aria-label="{{ _t('Add to cart') }}" wire:click="addToCart({{$product->id}})">
                           <svg class="size-5 text-base-content lg:group-hover:text-accent-content transition duration-200"
                              viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path
                                 d="M13.7812 7.21683C13.4188 7.21683 13.125 7.51067 13.125 7.87308C13.125 9.32048 11.9474 10.4981 10.5 10.4981C9.0526 10.4981 7.875 9.32048 7.875 7.87308C7.875 7.51067 7.58116 7.21683 7.21875 7.21683C6.85634 7.21683 6.5625 7.51067 6.5625 7.87308C6.5625 10.0443 8.32874 11.8106 10.5 11.8106C12.6713 11.8106 14.4375 10.0443 14.4375 7.87308C14.4375 7.51067 14.1437 7.21683 13.7812 7.21683Z"
                                 fill="currentColor" />
                              <path
                                 d="M19.1995 15.8932L17.6748 6.25552C17.5229 5.29262 16.7052 4.59375 15.7301 4.59375H14.3782C14.0643 2.73451 12.4472 1.3125 10.5 1.3125C8.55274 1.3125 6.93566 2.73451 6.62176 4.59375H5.26984C4.29476 4.59375 3.47701 5.29262 3.32513 6.2552L1.8005 15.8932C1.65053 16.8427 1.92258 17.8065 2.54711 18.5375C3.17132 19.2684 4.08071 19.6875 5.04201 19.6875H15.9579C16.9192 19.6875 17.8286 19.2684 18.4528 18.5375C19.0774 17.8065 19.3494 16.8427 19.1995 15.8932ZM10.5 2.625C11.7195 2.625 12.7383 3.46482 13.0318 4.59375H7.96818C8.2617 3.46482 9.28048 2.625 10.5 2.625ZM17.455 17.6848C17.0804 18.1235 16.5347 18.375 15.9579 18.375H5.04201C4.46523 18.375 3.91953 18.1235 3.54494 17.6848C3.17004 17.2461 3.00693 16.668 3.09698 16.0983L4.6216 6.45996C4.67223 6.13921 4.94492 5.90625 5.26984 5.90625H15.7301C16.055 5.90625 16.3277 6.13921 16.3783 6.46028L17.903 16.0983C17.993 16.668 17.8299 17.2461 17.455 17.6848Z"
                                 fill="currentColor" />
                           </svg>
                           {{_t("To cart")}}
                        </button>
                        @endif
                     </div>
                  </div>
               </div>
               @endforeach
            </div>
         </div>
      </div>
      <div x-show="open" x-cloak class="w-full h-dvh absolute top-0 right-0 z-50 m:hidden">
         <div x-show="open" x-cloak class="absolute inset-0 z-0 backdrop-blur-sm bg-base-200/80"
            x-transition:enter="duration-200 ease-out" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="duration-200 ease-in delay-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>
         <fieldset @click.outside="open = false; document.querySelector('body').classList.remove('block')"
            x-show="open" x-cloak
            class="h-full w-full max-w-[85%] absolute top-0 right-0 z-10 px-5 py-12 bg-base-200 divide-y divide-base-content shadow-[-1px_0px_0px_0px] shadow-main"
            aria-label="{{ _t('Filter') }}" x-transition:enter="duration-300 ease-out delay-200"
            x-transition:enter-start="translate-x-full opacity-0" x-transition:enter-end="translate-0 opacity-100"
            x-transition:leave="duration-150 ease-in" x-transition:leave-start="translate-0 opacity-100"
            x-transition:leave-end="translate-x-full opacity-0">
            <div class="flex justify-between items-center gap-x-4 pb-8">
               <span class="text-xl font-medium text-base-content inline-flex items-center gap-x-2">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke-width="1.5" stroke="currentColor" class="size-6">
                     <path stroke-linecap="round" stroke-linejoin="round"
                        d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
                  </svg>
                  {{ _t('Filter') }}
               </span>
               <button @click="open = false; document.querySelector('body').classList.remove('block')"
                  class="text-main size-6">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke-width="1.5" stroke="currentColor">
                     <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                  </svg>
               </button>
            </div>
            <div class="pt-8 divide-y divide-base-content">
               <div class="space-y-8 pb-6">
                  @foreach ($categories as $key => $category)
                  <div class="checkbox-input select-none" wire:click="filterCategory({{$category->id}})">
                     <input type="checkbox" id="filter_sm_{{$key}}" name="filter_sm_{{$key}}" wire:model="activeCategories"
                        value="{{$category->id}}"">
                        <label for=" filter_sm_{{$key}}">
                     <div>
                        <svg class="size-3" viewBox="0 0 12 11" fill="none"
                           xmlns="http://www.w3.orwg/2000/svg">
                           <path
                              d="M3.81934 11L3.75333 10.8866C2.74506 9.15401 0.0657143 5.47725 0.0386502 5.44032L0 5.38737L0.912838 4.47753L3.80238 6.51245C5.62172 4.13144 7.31904 2.49605 8.42619 1.54105C9.63732 0.496367 10.4257 0.0154316 10.4336 0.010823L10.4516 0H12L11.8521 0.132848C8.04811 3.54997 3.925 10.8127 3.88393 10.8857L3.81934 11Z"
                              fill="currentColor" />
                        </svg>
                     </div>
                     <span>{{$category->name()}}</span>
                     </label>
                  </div>
                  @endforeach
               </div>
               <div class="pt-6">
                  <span class="block mb-4 text-base-content">Ціна, грн</span>
                  <div class="double-range">
                     <div class="text-input justify-start">
                        <input type="number" name="min" wire:model="min_price" wire:change="filter()" />
                        <div class="separator"></div>
                        <input type="number" name="max" value="70" wire:model="max_price" wire:change="filter()" />
                     </div>
                  </div>
               </div>
            </div>
         </fieldset>
      </div>
   </div>
   <div class="description container mr-4 pb-4 text-white">
      {!! $page->seo->content ?? '' !!}
   </div>
</div>

<div>
    <div class="flex justify-between items-center">
        <h2 class="py-3 font-semibold text-xl text-gray-800 leading-tight"><a
                    href="{{ route('stock-purchase.index') }}">Products</a> / <span
                    class="text-xl text-gray-500">Add</span></h2>
    </div>

    <div class="mt-6 max-w-full overflow-x-auto border-2 bg-white shadow">
        <form wire:submit.prevent="save">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 m-8">
                <div x-data="{
                    showCategories: false,
                    categories: @js($categories),
                    closeDropdown() {
                        this.showCategories = false;
                        this.search = '';
                    },
                    toggleDropdown() {
                        this.showCategories = true;
                    },
                    get filteredCategories() {
                        if (this.search === '') {
                            return this.categories;
                        }
                        return this.categories.filter((category) => {
                            return category.name.toLowerCase().includes(this.search.toLowerCase());
                        });
                    },
                    search: '',
                    categoryId: @entangle('categoryId').defer,
                    categoryName: 'Select Category'
                }">
                    <label class="block font-medium text-sm text-gray-700"
                           for="category">{{ __('Category') }}</label>
                    <div class="relative mt-2" @click.outside="closeDropdown()">
                        <button type="button" @click="toggleDropdown()"
                                class="relative w-full cursor-default bg-white py-1.5 pl-3 pr-10 text-left text-gray-900 shadow-sm border-2 border-gray-300 focus:border-gray-300 focus:outline-none focus:ring focus:ring-gray-200 focus:ring-opacity-50 sm:text-sm sm:leading-6"
                                aria-haspopup="listbox" aria-expanded="true" aria-labelledby="listbox-label">
                          <span class="flex items-center">
                                <span class="block truncate" x-text="categoryName"></span>
                          </span>
                            <span class="pointer-events-none absolute inset-y-0 right-0 ml-3 flex items-center pr-2">
                                <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"
                                     aria-hidden="true">
                                  <path fill-rule="evenodd"
                                        d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3zm-3.76 9.2a.75.75 0 011.06.04l2.7 2.908 2.7-2.908a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0l-3.25-3.5a.75.75 0 01.04-1.06z"
                                        clip-rule="evenodd"/>
                                </svg>
                            </span>
                        </button>
                        <ul class="absolute z-10 mt-1 max-h-56 w-full overflow-auto bg-white py-1 text-base shadow-lg border-2 border-gray-300 focus:outline-none sm:text-sm"
                            tabindex="-1" role="listbox" aria-labelledby="listbox-label" x-show="showCategories" x-cloak
                            aria-activedescendant="listbox-option-3">
                            <li class="text-gray-900 hover:bg-gray-100 relative cursor-default select-none py-2 px-3"
                                id="listbox-option-0" role="option">
                                <input type="search" x-model="search"
                                       class="p-2 w-full border-2 border-gray-300 focus:border-gray-300 focus:outline-none focus:ring focus:ring-gray-200 focus:ring-opacity-50">
                            </li>
                            <template x-for="category in filteredCategories" :key="category.id">
                                <li class="text-gray-900 hover:bg-gray-100 relative cursor-default select-none py-2 pl-3 pr-9"
                                    @click="categoryId=category.id; categoryName=category.name; closeDropdown()"
                                    id="listbox-option-0" role="option">
                                    <div class="flex items-center">
                                        <span class="block truncate"
                                              :class="{
                                                  'font-semibold': category.id==categoryId,
                                                  'font-normal': category.id!=categoryId,
                                              }"
                                              x-text="category.name"></span>
                                    </div>
                                    <span x-show="category.id==categoryId"
                                          class="text-gray-600 absolute inset-y-0 right-0 flex items-center pr-4">
                                          <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"
                                               aria-hidden="true">
                                                <path fill-rule="evenodd"
                                                      d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                                                      clip-rule="evenodd"/>
                                          </svg>
                                    </span>
                                </li>
                            </template>
                        </ul>
                    </div>
                    @error('categoryId')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div></div>
                <div class="md:col-span-2">
                    <label class="block font-medium text-sm text-gray-700"
                           for="description">{{ __('Description') }}</label>
                    <textarea id="description" wire:model.defer="product.description" rows="5"
                              class="block mt-1 p-2 w-full border-2 border-gray-300 focus:border-gray-300 focus:outline-none focus:ring focus:ring-gray-200 focus:ring-opacity-50 shadow-sm"
                    ></textarea>

                    @error('product.description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="col-span-2">
                    <label class="block font-medium text-sm text-gray-700"
                           class="text-base font-semibold" for="image">{{ __('Image') }}</label>
                    <input type="file" id="image" wire:model="image" x-ref="image"
                           x-data="{}" x-init="@this.on('image_cleared', () => $refs.image.value = '')"
                           class="mt-4 block w-full text-sm text-slate-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-full file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-violet-50 file:text-gray-700
                                    hover:file:bg-gray-100 focus:outline-gray-400 focus:ring-0"
                    >
                    @error('image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @if ($image)
                        <div class="mt-4">
                            <span class="text-sm">Preview:</span>
                            <img src="{{ $image->temporaryUrl() }}" class="mt-2 h-48 w-auto">
                        </div>
                    @endif
                </div>
            </div>
            <div class="flex items-center justify-end mt-4 bg-slate-100 px-4 py-3">
                <button class="ml-4 bg-gray-900 text-white px-3 py-2 text-sm font-medium" type="submit">
                    {{ __('Save') }}
                </button>
            </div>
        </form>
    </div>
</div>

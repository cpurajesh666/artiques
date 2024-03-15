<div>
    <div class="mt-4 flex justify-between items-center">
        <h2 class="py-3 font-semibold text-xl text-gray-800 leading-tight">Products</h2>
        <a href="{{ route('stock-purchase.create') }}" class="bg-gray-900 text-white px-3 py-2 text-sm font-medium">Add New</a>
    </div>

    <div class="md:mt-4">
        <div wire:loading class="w-full">
            <div class="w-full h-48 flex justify-center items-center text-slate-600">
                @include('shared.loader')
            </div>
        </div>
        <ul wire:loading.remove class="mt-2 block md:hidden space-y-4">
            @forelse($products as $product)
                <li role="listitem" class="overflow-hidden bg-white">
                    <div class="p-4 border shadow">
                        <p class="line-clamp-1 font-medium text-lg leading-tight">{{ $product->category->name }}</p>
                        <p class="mt-1 text-sm font-medium text-gray-500 line-clamp-3">{{ $product->description }}</p>
                        <div class="mt-4">
                            <img class="h-auto w-full object-contain" src="{{ asset('storage/' . $product->images[0]) }}" alt="{{ $product->category->name }} Image">
                        </div>
                        <div class="mt-4 flex items-center">
                            <a href="{{ '#' }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-800 text-white border border-transparent font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-800 hover:text-white active:bg-slate-200 focus:outline-none focus:border-slate-200 focus:ring focus:ring-slate-300 disabled:opacity-25 transition">Edit</a>
                        </div>
                    </div>
                </li>
            @empty
                <li role="listitem" class="overflow-hidden">
                    <div class="p-4 bg-white border shadow">
                        <p class="font-medium">No entries are there!</p>
                    </div>
                </li>
            @endforelse
        </ul>
        <div wire:loading.remove class="max-w-full hidden md:block border-2 overflow-x-auto bg-white shadow-md">
            <table class="w-full table-auto text-sm">
                <thead>
                <tr class="text-left border-b-2 border-slate-200 font-medium">
                    <th class="px-6 py-3">#</th>
                    <th class="px-6 py-3">Category</th>
                    <th class="px-6 py-3">Description</th>
                    <th class="px-6 py-3">Image</th>
                    <th class="px-6 py-3">Created At</th>
                    <th class="px-6 py-3 text-center">Action</th>
                </tr>
                </thead>
                <tbody>
                @forelse($products as $product)
                    <tr class="hover:bg-slate-100 focus-within:bg-slate-100 border-b border-slate-200">
                        <td class="px-6 py-2">{{ $loop->iteration + ($products->perPage() * ($products->currentPage() - 1)) }}</td>
                        <td class="px-6 py-2 min-w-[150px]"><p class="line-clamp-2">{{ $product->category->name }}</p></td>
                        <td class="px-6 py-2 max-w-[350px]"><p class="line-clamp-3">{{ $product->description }}</p></td>
                        <td class="px-6 py-2">
                            <div class="h-32 w-32">
                                <img class="w-auto h-full object-contain" src="{{ asset('storage/' . $product->images[0]) }}" alt="{{ $product->category->name }} Image">
                            </div>
                        </td>
                        <td class="px-6 py-2">{{ $product->created_at->diffForHumans() }}</td>
                        <td class="px-6 py-2">
                            <div class="flex justify-around items-center">
                                <a href="{{ route('stock-purchase.update', $product) }}"
                                   class="inline-flex items-center px-4 py-2 bg-gray-800 text-white border border-transparent font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-800 hover:text-white active:bg-slate-200 focus:outline-none focus:border-slate-200 focus:ring focus:ring-slate-300 disabled:opacity-25 transition">Edit</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-3 text-black text-opacity-50 font-medium text-center">No entries are there!</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($products->hasPages())
            <div class="px-4 py-4">{{ $products->links() }}</div>
        @endif
    </div>
</div>

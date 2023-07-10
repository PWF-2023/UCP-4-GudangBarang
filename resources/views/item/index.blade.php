<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Item') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                <div class="p-6 text-xl text-gray-900 dark:text-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <x-create-button href="{{ route('item.create') }}" />
                        </div>
                        <div>
                            @if (session('success'))
                            <p x-data="{ show: true }" x-show="show" x-transition
                                x-init="setTimeout(() => show = false, 5000)"
                                class="text-sm text-green-600 dark:text-green-400">{{ session('success') }}
                            </p>
                            @endif
                            @if (session('danger'))
                            <p x-data="{ show: true }" x-show="show" x-transition
                                x-init="setTimeout(() => show = false, 5000)"
                                class="text-sm text-red-600 dark:text-red-400">{{ session('danger') }}
                            </p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Name
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Category
                                </th>
                                <th scope="col" class="hidden px-6 py-3 md:block">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($items as $item)
                            <tr class="odd:bg-white odd:dark:bg-gray-800 even:bg-gray-50 even:dark:bg-gray-700">
                                <td scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                    <a href="{{ route('item.edit', $item) }}" class="hover:underline">{{ $item->name
                                        }}</a>
                                </td>
                                <td scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                    @if ($item->category_id)
                                    {{ $item->category->name }}
                                    @endif
                                </td>
                                <td class="hidden px-6 py-4 md:block">
                                    @if ($item->is_in == false)
                                    <span
                                        class="bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                                        In
                                    </span>
                                    @else
                                    <span
                                        class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">
                                        Out
                                    </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex space-x-3">
                                        {{-- Action Here --}}
                                        @if ($item->is_in == false)
                                        <form action="{{ route('item.in', $item) }}" method="Post">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-green-600 dark:text-green-400">
                                                In
                                            </button>
                                        </form>
                                        @else
                                        <form action="{{ route('item.out', $item) }}" method="Post">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-red-600 dark:text-red-400">
                                                Out
                                            </button>
                                        </form>
                                        @endif
                                        <form action="{{ route('item.destroy', $item) }}" method="Post">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 dark:text-red-400">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>

                            </tr>
                            @empty
                            <tr class="bg-white dark:bg-gray-800">
                                <td scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                    Empty
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($itemsOut > 1)
                <div class="p-6 text-xl text-gray-900 dark:text-gray-100">
                    <form action="{{ route('item.deleteallout') }}" method="Post">
                        @csrf
                        @method('delete')
                        <x-primary-button>
                            Delete All Out Items
                        </x-primary-button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

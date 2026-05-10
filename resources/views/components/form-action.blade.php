@props([
    'cancel',
    'target' => 'save',
])

<a href="{{ route($cancel) }}" role="button"
    class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
    Cancel
</a>

<x-submit target="{{ $target }}" />
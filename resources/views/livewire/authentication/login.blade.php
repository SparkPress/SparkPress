<div class="w-full">
    <div class="mx-auto max-w-xl flex justify-center mt-14">
        <a href="{{ route("home") }}" class="text-2xl font-bold" wire:navigate>SparkPress</a>
    </div>
    <div class="p-4 border border-gray-200 rounded-xl mx-auto mt-14 max-w-xl bg-white">
        <h2 class="text-xl font-bold pb-2">Login</h2>
        <p class="text-gray-600">Login to SparkPress</p>
        <hr class="my-4" />
        <form wire:submit.prevent="authenticate">
            <div class="mb-3">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    wire:model="email"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                />
                @if($errors->has("email"))
                    <p class="text-red-500 text-xs mt-1">{{ $errors->first("email") }}</p>
                @endif
            </div>
            <div class="mb-3">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    wire:model="password"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                />
                @if($errors->has("password"))
                    <p class="text-red-500 text-xs mt-1">{{ $errors->first("password") }}</p>
                @endif
            </div>
            <div class="mt-4">
                <button
                    type="submit"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Login
                </button>
            </div>
        </form>
    </div>
</div>

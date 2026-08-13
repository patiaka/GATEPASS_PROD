<x-guest-layout>

    <x-guest-toast />

    <form class="mb-3" method="POST" class="mb-3" action="{{ route('login') }}">
        @csrf
        <div class="flex flex-col gap-4">
            <div class="flex flex-col">
                <input type="email" name="email" id="inputEmail" placeholder="Email"
                    class="text-gray-900 bg-gray-50 rounded-lg text-sm block w-full p-2.5 border border-gray-300 focus:z-10 focus:ring-blue-500 focus:border-blue-500"
                    autocomplete="email" required autofocus>
            </div>

            <div class="flex flex-col">
                <div class="relative">
                    <input type="password" name="password" placeholder="Password" id="inputPassword"
                        class="text-gray-900 bg-gray-50 rounded-lg text-sm block w-full p-2.5 pr-11 border border-gray-300 focus:z-10 focus:ring-blue-500 focus:border-blue-500"
                        autocomplete="current-password" required>
                    <x-password-eye />
                </div>
            </div>

            <div>
                <label class="text-sm text-slate-500 flex gap-2 items-center">
                    <input id="remember-me" type="checkbox" name="remember" checked>
                    Keep me logged in
                </label>
            </div>
        </div>

        <button
            class="w-full bg-[#134169] text-white font-semibold py-2 px-4 mt-8 rounded-lg hover:bg-[#0e3354] transition duration-300"
            type="submit">
            Sign in
        </button>

        @if (Route::has('password.request'))
        <p class="text-center mt-4">
            <a href="{{ route('password.request') }}" class="text-sm text-slate-500 hover:text-blue-600">Forget Password
                ?</a>
        </p>
        @endif
    </form>
</x-guest-layout>
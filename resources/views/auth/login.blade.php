<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Bonn DIG</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Instrument Sans', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        brandBlue: '#3B82F6',
                        brandIndigo: '#6366F1',
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md">
        <!-- Logo/Brand -->
        <div class="text-center mb-8">
            <div class="mb-4">
                <img src="{{asset('logo/logo.png')}}" alt="Logo" class="w-20 h-20 object-contain mx-auto">
            </div>
            <h1 class="text-3xl font-extrabold text-gray-800">Bonn DIG</h1>
            <p class="text-gray-600 mt-2 text-sm">Silakan masuk ke akun Anda</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-3xl shadow-2xl p-8 transform transition duration-500 hover:scale-105">
            <!-- Flash Messages -->
            @if (session('error'))
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Validation Errors -->
            @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-800">
                            @foreach ($errors->all() as $error)
                            {{ $error }}<br>
                            @endforeach
                        </p>
                    </div>
                </div>
            </div>
            @endif

            @if (session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Kolom NIP -->
                <div class="mb-6">
                    <label for="nip" class="block text-sm font-medium text-gray-700 mb-2">
                        NIP
                    </label>
                    <input id="nip" name="nip" type="text" required
                        class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brandBlue focus:border-transparent sm:text-sm transition duration-300"
                        placeholder="Masukkan NIP Anda" pattern="[0-9]+" maxlength="18" inputmode="numeric"
                        onkeypress="return event.charCode >= 48 && event.charCode <= 57" value="{{ old('email') }}">
                    @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kolom Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password
                    </label>

                    <div class="relative">
                        <input type="password" id="password" name="password" required
                            class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brandBlue focus:border-transparent transition duration-300"
                            placeholder="••••••••">

                        <button type="button" onclick="togglePassword('password', this)"
                            class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 hover:text-gray-700 transition duration-200">
                            <svg id="eyeIcon" xmlns="https://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7
                       -1.274 4.057-5.065 7-9.542 7
                       -4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>

                    @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

              <!-- CAPTCHA -->
<div>
    <label class="block text-sm font-medium text-gray-700 mb-2">
        Verifikasi
    </label>

    <div class="flex items-center gap-2">
        
        <!-- GAMBAR CAPTCHA -->
        <img src="{{ captcha_src('flat') }}" id="captcha-img"
            class="h-10 rounded border border-gray-300 bg-white px-2">

        <!-- BUTTON REFRESH -->
        <button type="button" onclick="refreshCaptcha()"
            class="h-10 w-10 flex items-center justify-center bg-blue-500 text-white rounded hover:bg-blue-600 transition">
            ⟳
        </button>

        <!-- INPUT CAPTCHA -->
        <input type="text" name="captcha" required
            placeholder="Kode Captcha"
            class="flex-1 px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
    </div>

    <!-- ERROR -->
    @error('captcha')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

           

                <!-- Sign Up Link -->
                <div class="text-center mb-6">
                    <p class="text-gray-600">
                        Belum punya akun?
                        <a href="/register"
                            class="text-brandBlue hover:text-brandIndigo font-medium transition duration-200">
                            Daftar sekarang
                        </a>
                    </p>
                </div>

                <!-- Login Button -->
                <button type="submit"
                    class="w-full bg-gradient-to-r from-brandBlue to-brandIndigo text-white py-3 px-4 rounded-xl font-semibold hover:from-brandIndigo hover:to-brandBlue focus:outline-none focus:ring-4 focus:ring-brandBlue focus:ring-opacity-50 transition duration-300 transform hover:scale-105">
                    Masuk
                </button>
            </form>
        </div>
    </div>
</body>

<script>
function togglePassword(fieldId, button) {
    const input = document.getElementById(fieldId);
    const eyeIcon = button.querySelector('svg');
    
    if (input.type === 'password') {
        input.type = 'text';
        eyeIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
        `;
    } else {
        input.type = 'password';
        eyeIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        `;
    }
}

function refreshCaptcha(id = 'captcha-img') {
    const captcha = document.getElementById(id);
    captcha.src = "{{ captcha_src('flat') }}" + '?' + Date.now();
}
</script>

</html>

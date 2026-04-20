<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Bonn DIG</title>
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
            <p class="text-gray-600 mt-2 text-sm">Buat akun baru Anda</p>
        </div>

        <!-- Login Link -->
        <div class="text-center mb-6">
            <p class="text-gray-600">
                Sudah punya akun?
                <a href="/login" class="text-brandBlue hover:text-brandIndigo font-medium transition duration-200">
                    Masuk di sini
                </a>
            </p>
        </div>

        <!-- Register Card -->
        <div class="bg-white rounded-3xl shadow-2xl p-8 transform transition duration-500 hover:scale-105">
            <!-- Flash Messages -->
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

            <!-- Error Messages -->
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

            <form method="POST" action="/register" class="space-y-6">
                @csrf

                <!-- Name Field -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Lengkap
                    </label>
                    <input type="text" id="name" name="name" required autofocus value="{{ old('name') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brandBlue focus:border-transparent transition duration-300">
                    @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- NIP Field -->
                <div>
                    <label for="nip" class="block text-sm font-medium text-gray-700 mb-2">
                        NIP
                    </label>
                    <input type="text" id="nip" name="nip" required value="{{ old('nip') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brandBlue focus:border-transparent transition duration-300"
                        placeholder="Masukkan NIP Anda" pattern="[0-9]+" maxlength="18" inputmode="numeric"
                        onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                    @error('nip')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role Field (Hidden) -->
                <input type="hidden" name="role" value="pegawai">

                <!-- Divisi Field -->
                <div>
                    <label for="divisi" class="block text-sm font-medium text-gray-700 mb-2">
                        Divisi
                    </label>
                    <select id="divisi" name="divisi" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brandBlue focus:border-transparent transition duration-300">
                        <option value="">Pilih Divisi</option>
                        @foreach($divisions ?? [] as $divisi)
                        <option value="{{ $divisi->nama }}" {{ old('divisi') == $divisi->nama ? 'selected' : '' }}>{{ $divisi->nama }}</option>
                        @endforeach
                    </select>
                    @error('divisi')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Fields -->
                <div class="relative w-full">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required
                            class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brandBlue focus:border-transparent transition duration-300"
                            placeholder="••••••••">
                        <button type="button" onclick="togglePassword('password', this)"
                            class="absolute right-3 top-1/2 -translate-y-1/2 z-10 text-gray-500 hover:text-gray-700 focus:outline-none transition duration-200">
                            <svg id="eyeOpen" xmlns="https://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5
                       c4.477 0 8.268 2.943 9.542 7
                       -1.274 4.057-5.065 7-9.542 7
                       -4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="relative w-full">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi
                        Password</label>
                    <div class="relative">
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brandBlue focus:border-transparent transition duration-300"
                            placeholder="••••••••">
                        <button type="button" onclick="togglePassword('password_confirmation', this)"
                            class="absolute right-3 top-1/2 -translate-y-1/2 z-10 text-gray-500 hover:text-gray-700 focus:outline-none transition duration-200">
                            <svg id="eyeOpenConfirm" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5
                       c4.477 0 8.268 2.943 9.542 7
                       -1.274 4.057-5.065 7-9.542 7
                       -4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg id="eyeClosedConfirm" xmlns="https://www.w3.org/2000/svg" class="h-5 w-5 hidden"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19
                       c-4.478 0-8.268-2.943-9.542-7
                       a9.956 9.956 0 012.042-3.368M6.223 6.223
                       A9.956 9.956 0 0112 5
                       c4.478 0 8.268 2.943 9.542 7
                       a9.965 9.965 0 01-4.293 5.077M6.223 6.223
                       L3 3m3.223 3.223l11.554 11.554" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Terms and Conditions -->
                <div class="flex items-center">
                    <input type="checkbox" id="terms" name="terms" required
                        class="h-4 w-4 text-brandBlue focus:ring-brandBlue border-gray-300 rounded">
                    <label for="terms" class="ml-2 block text-sm text-gray-700">
                        Saya setuju dengan <a href="#" class="text-brandBlue hover:text-brandIndigo">syarat dan
                            ketentuan</a>
                    </label>
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

                <!-- Register Button -->
                <button type="submit"
                    class="w-full bg-gradient-to-r from-brandBlue to-brandIndigo text-white py-3 px-4 rounded-xl font-semibold hover:from-brandIndigo hover:to-brandBlue focus:outline-none focus:ring-4 focus:ring-brandBlue focus:ring-opacity-50 transition duration-300 transform hover:scale-105">
                    Daftar Sekarang
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
    captcha.src = "{{ captcha_src() }}" + '?' + Date.now();
}
</script>

</html>
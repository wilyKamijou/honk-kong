<x-guest-layout>
    <style>
        /* Estilos específicos para el login */
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        
        .login-box {
            width: 100%;
            max-width: 420px;
        }
        
        .dark-transparent-card {
            background-color: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(8px);
            border-radius: 12px;
            padding: 2.5rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .form-input-dark {
            background-color: rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            border-radius: 6px;
            padding: 0.75rem 1rem;
            width: 100%;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-input-dark:focus {
            border-color: rgba(255, 255, 255, 0.3);
            box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.1);
            outline: none;
        }
        
        .form-checkbox-dark {
            background-color: rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.1);
            margin-right: 0.5rem;
        }
        
        .text-white-80 { color: rgba(255, 255, 255, 0.8); }
        .text-white-60 { color: rgba(255, 255, 255, 0.6); }
        .divider { border-top: 1px solid rgba(255, 255, 255, 0.1); margin: 1.5rem 0; }
        
        /* Animaciones */
        @keyframes pulse-grow {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .animate-logo {
            animation: pulse-grow 2s infinite ease-in-out;
        }
        
        .hover-grow {
            transition: transform 0.3s ease;
        }
        
        .hover-grow:hover {
            transform: scale(1.03);
        }
    </style>

    <div class="login-container">
        <div class="login-box">
            <div class="dark-transparent-card hover-grow">
                <!-- Logo y Encabezado -->
                <div class="text-center mb-6">
                    <img src="https://i.imgur.com/3vt7l0G.png" 
                         alt="Logo Hong Kong" 
                         class="mx-auto mb-3 animate-logo"
                         style="width: 100px; height: 100px; border-radius: 50%; border: 3px solid rgba(255,255,255,0.2);">
                    
                    <h1 class="text-3xl font-bold text-white mb-1" style="text-shadow: 0 2px 4px rgba(0,0,0,0.3);">HONG KONG</h1>
                    <p class="text-white-80 uppercase text-sm mb-1 tracking-widest">CHINESE FOOD</p>
                    <p class="text-white-60 text-xs italic">"Sabores que enamoran"</p>
                </div>
                
                <!-- Formulario -->
                <x-validation-errors class="mb-4 text-red-300 text-sm" />

                @session('status')
                    <div class="mb-4 text-green-400 text-sm">
                        {{ $value }}
                    </div>
                @endsession

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email -->
                    <div class="mb-4">
                        <label for="email" class="block text-white-80 mb-2 text-sm font-medium">Correo electrónico</label>
                        <input id="email" class="form-input-dark" type="email" name="email" :value="old('email')" required autofocus />
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <label for="password" class="block text-white-80 mb-2 text-sm font-medium">Contraseña</label>
                        <input id="password" class="form-input-dark" type="password" name="password" required />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <input id="remember_me" type="checkbox" name="remember" class="form-checkbox-dark rounded" />
                            <label for="remember_me" class="ml-2 text-white-80 text-sm">Recuérdame</label>
                        </div>
                        
                        <a href="{{ route('password.request') }}" class="text-white-60 hover:text-white text-sm transition-colors">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>

                    <!-- Botón Ingresar -->
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white py-3 px-4 rounded font-bold transition-colors shadow-md">
                        INGRESAR
                    </button>
                </form>

                <!-- Enlace de registro -->
                <div class="divider"></div>
                <div class="text-center text-sm">
                    <span class="text-white-60">¿No tienes una cuenta?</span> 
                    <a href="{{ route('register') }}" class="text-white-80 hover:text-red-300 font-medium ml-1 transition-colors">
                        Regístrate aquí
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
<x-guest-layout>
    <style>
        /* Ajustes globales para HTML y Body para permitir el scroll */
        html, body {
            height: 100%; /* Asegura que html y body ocupen al menos el 100% de la altura de la vista */
            margin: 0;
            padding: 0;
            overflow-x: hidden; /* Evita scroll horizontal no deseado */
        }

        /* Reutilizando los mismos estilos del login */
        .login-container {
            display: flex;
            justify-content: center;
            /* **¡IMPORTANTE! Eliminamos align-items: center para que el contenido no se "corte"** */
            /* align-items: center; */ 
            min-height: 100vh; /* El contenedor seguirá ocupando al menos toda la altura de la vista */
            padding: 20px;
            box-sizing: border-box; /* Asegura que el padding no añada tamaño extra */
            /* **Eliminamos overflow-y: auto de aquí** */
            /* overflow-y: auto; */ 
        }
        
        .login-box {
            width: 100%;
            max-width: 420px;
            /* Añadimos un padding-bottom y un margin-top extra para asegurar espacio */
            padding-bottom: 30px; /* Puedes ajustar este valor según sea necesario */
            margin-top: 50px; /* Añade un margen superior para que no empiece pegado al borde */
            margin-bottom: 50px; /* Añade un margen inferior para asegurar espacio para el scroll */
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
        
        /* Estilos específicos para términos y condiciones */
        .terms-text a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: underline;
            transition: color 0.3s;
        }
        
        .terms-text a:hover {
            color: #f87171; /* Rojo claro */
        }
    </style>

    <div class="login-container">
        <div class="login-box">
            <div class="dark-transparent-card hover-grow">
                <div class="text-center mb-6">
                    <img src="https://i.imgur.com/3vt7l0G.png" 
                            alt="Logo Hong Kong" 
                            class="mx-auto mb-3 animate-logo"
                            style="width: 100px; height: 100px; border-radius: 50%; border: 3px solid rgba(255,255,255,0.2);">
                    
                    <h1 class="text-3xl font-bold text-white mb-1" style="text-shadow: 0 2px 4px rgba(0,0,0,0.3);">HONG KONG</h1>
                    <p class="text-white-80 uppercase text-sm mb-1 tracking-widest">CHINESE FOOD</p>
                    <p class="text-white-60 text-xs italic">"Sabores que enamoran"</p>
                </div>
                
                <x-validation-errors class="mb-4 text-red-300 text-sm" />

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="name" class="block text-white-80 mb-2 text-sm font-medium">Nombre</label>
                        <input id="name" class="form-input-dark" type="text" name="name" :value="old('name')" required autofocus />
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-white-80 mb-2 text-sm font-medium">Correo electrónico</label>
                        <input id="email" class="form-input-dark" type="email" name="email" :value="old('email')" required />
                    </div>

                    <div class="mb-4">
                        <label for="password" class="block text-white-80 mb-2 text-sm font-medium">Contraseña</label>
                        <input id="password" class="form-input-dark" type="password" name="password" required />
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="block text-white-80 mb-2 text-sm font-medium">Confirmar Contraseña</label>
                        <input id="password_confirmation" class="form-input-dark" type="password" name="password_confirmation" required />
                    </div>

                    @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                        <div class="mb-4">
                            <div class="flex items-center">
                                <input id="terms" name="terms" type="checkbox" class="form-checkbox-dark rounded" required />
                                <label for="terms" class="ml-2 text-white-60 text-sm terms-text">
                                    {!! __('Acepto los :terms_of_service y :privacy_policy', [
                                            'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'">Términos de Servicio</a>',
                                            'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'">Política de Privacidad</a>',
                                        ]) !!}
                                </label>
                            </div>
                        </div>
                    @endif

                    <div class="flex items-center justify-between mt-6">
                        <a href="{{ route('login') }}" class="text-white-60 hover:text-white text-sm transition-colors">
                            ¿Ya estás registrado?
                        </a>

                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white py-2 px-6 rounded font-bold transition-colors shadow-md">
                            REGISTRARSE
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
<x-app-layout>
    <!-- Barra superior negra -->
    <div class="bg-black py-4 px-6 shadow-md">
        <h1 class="text-2xl font-bold text-white">
            @isset($producto)
                Escribe tu reseña para {{ $producto->nombre }}
            @else
                Crear nueva reseña
            @endisset
        </h1>
    </div>

    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-white leading-tight">
            <!-- Este contenido ahora está en la barra negra, puedes dejarlo vacío o poner algo adicional -->
        </h2>
    </x-slot>

    <div class="py-8 relative z-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Contenedor principal con dos columnas -->
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Formulario de Información del Producto (Izquierda) -->
                <div class="w-full lg:w-1/2 bg-white/10 rounded-xl shadow-lg p-6 backdrop-blur-sm border border-white/20">
                    @isset($producto)
                        <div class="flex flex-col h-full">
                            <div class="flex justify-center mb-4">
                                <img src="{{ $producto->imagen_url ?? asset('images/default-product.png') }}" 
                                     alt="{{ $producto->nombre }}" 
                                     class="h-48 object-contain transition duration-300 hover:scale-105">
                            </div>
                            <h3 class="text-2xl font-semibold mb-3 text-white">{{ $producto->nombre }}</h3>
                            <p class="text-white/90 mb-6 flex-grow text-lg">{{ Str::limit($producto->descripcion, 150) }}</p>
                            <div class="mt-auto space-y-4">
                                <div class="flex items-center">
                                    <span class="font-medium mr-3 text-white text-lg">Precio:</span>
                                    <span class="text-xl text-yellow-300 font-bold">${{ number_format($producto->precio, 2) }}</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="font-medium mr-3 text-white text-lg">Disponibilidad:</span>
                                    <span class="text-green-400 font-medium text-lg">En stock</span>
                                </div>
                                @if(!isset($producto))
                                <div class="mt-5">
                                    <label for="producto_id" class="block text-lg font-medium text-white mb-3">Seleccionar otro producto</label>
                                    <select class="w-full rounded-lg border-white/30 shadow-sm focus:border-indigo-300 focus:ring-2 focus:ring-indigo-200 transition duration-150 bg-white/20 text-white text-lg p-2 placeholder-white/70">
                                        <option value="" class="text-white">Selecciona un producto</option>
                                        @foreach($productos as $prod)
                                            <option value="{{ $prod->id_producto }}" class="text-white">{{ $prod->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="p-6 text-center bg-indigo-500/10 rounded-lg">
                            <p class="text-white text-lg">Selecciona un producto para reseñar</p>
                        </div>
                    @endisset
                </div>

                <!-- Formulario de Reseña (Derecha) -->
                <div class="w-full lg:w-1/2 bg-white/10 rounded-xl shadow-lg overflow-hidden backdrop-blur-sm border border-white/20">
                    <div class="p-6">
                        @if(session('error'))
                            <div class="mb-6 p-4 bg-red-500/20 text-white rounded-lg border border-red-400/30 text-lg">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="mb-6 p-4 bg-red-500/20 text-white rounded-lg border border-red-400/30">
                                <ul class="list-disc pl-5 space-y-2 text-lg">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('reseñas.storeByUser') }}">
                            @csrf

                            @isset($producto)
                                <input type="hidden" name="producto_id" value="{{ $producto->id_producto }}">
                            @endif

                            <!-- Sistema de calificación con estrellas mejorado -->
                            <div class="mb-8">
                                <label class="block text-lg font-medium text-white mb-3">Calificación</label>
                                <div class="rating-stars flex items-center space-x-3" id="ratingContainer">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="star text-5xl cursor-pointer text-white/40 hover:text-yellow-400 transition-all duration-200 transform hover:scale-125"
                                              data-value="{{ $i }}"
                                              onmouseover="highlightStars(this)"
                                              onmouseout="resetStars()"
                                              onclick="toggleRating(this)">
                                            ★
                                        </span>
                                    @endfor
                                    <input type="hidden" name="calificacion" id="calificacion" required>
                                    <span id="ratingText" class="ml-3 text-lg text-yellow-300 font-medium">Selecciona una calificación</span>
                                </div>
                                @error('calificacion')
                                    <span class="text-red-300 text-lg mt-2 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Área de comentario withe con texto blanco -->
                            <div class="mb-8">
                                <label for="comentario" class="block text-lg font-medium text-black mb-3">Comentario</label>
                                <textarea class="w-full rounded-lg border-black/30 shadow-sm focus:border-indigo-300 focus:ring-2 focus:ring-indigo-200 transition duration-150 bg-black/20 backdrop-blur-sm text-black placeholder-white/50 text-lg p-3" 
                                          id="comentario" name="comentario" rows="6" 
                                          maxlength="500" required 
                                          placeholder="Escribe tu experiencia con este producto..."></textarea>
                                <div class="flex justify-between items-center mt-2">
                                    <small class="text-yellow-200 text-md">Máximo 500 caracteres</small>
                                    <small id="charCount" class="text-yellow-200 font-medium text-md">0/500</small>
                                </div>
                                @error('comentario')
                                    <span class="text-red-300 text-lg mt-2 block">{{ $message }}</span>
                                @enderror
                                
                                <!-- Botones de comentarios rápidos -->
                                <div class="mt-4">
                                    <p class="text-md text-yellow-200 mb-3">Comentarios rápidos:</p>
                                    <div class="flex flex-wrap gap-3">
                                        <button type="button" onclick="insertQuickComment('Me gustó mucho el producto', this)" 
                                                class="px-4 py-2 bg-white/20 text-yellow-200 rounded-full text-md hover:bg-yellow-400/30 hover:text-white transition">
                                            Me gustó mucho
                                        </button>
                                        <button type="button" onclick="insertQuickComment('Buen precio por la calidad', this)" 
                                                class="px-4 py-2 bg-white/20 text-yellow-200 rounded-full text-md hover:bg-yellow-400/30 hover:text-white transition">
                                            Buen precio
                                        </button>
                                        <button type="button" onclick="insertQuickComment('Excelente relación calidad-precio', this)" 
                                                class="px-4 py-2 bg-white/20 text-yellow-200 rounded-full text-md hover:bg-yellow-400/30 hover:text-white transition">
                                            Buena calidad
                                        </button>
                                        <button type="button" onclick="insertQuickComment('Llegó en perfecto estado', this)" 
                                                class="px-4 py-2 bg-white/20 text-yellow-200 rounded-full text-md hover:bg-yellow-400/30 hover:text-white transition">
                                            Buen estado
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Botones de acción -->
                            <div class="flex justify-end mt-8 space-x-4">
                                <a href="{{ url()->previous() }}" class="px-5 py-3 border border-red-400/50 rounded-lg shadow-sm text-lg font-medium text-white bg-red-500/60 hover:bg-red-500/80 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-400 transition duration-150">
                                    Cancelar
                                </a>
                                <button type="submit" class="px-5 py-3 border border-transparent rounded-lg shadow-sm text-lg font-medium text-white bg-indigo-500/90 hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400 transition duration-150 transform hover:scale-105">
                                    Enviar Reseña
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Sistema de estrellas interactivo mejorado
        let currentRating = 0;
        let tempRating = 0;
        const ratingTexts = {
            1: "Malo - No lo recomiendo",
            2: "Regular - Podría mejorar",
            3: "Bueno - Cumple con lo esperado",
            4: "Muy bueno - Superó mis expectativas",
            5: "Excelente - Lo recomiendo totalmente"
        };
        
        function highlightStars(star) {
            const stars = document.querySelectorAll('.star');
            const rating = parseInt(star.getAttribute('data-value'));
            tempRating = rating;
            
            stars.forEach((s, index) => {
                if (index < rating) {
                    s.classList.add('text-yellow-400', 'scale-110');
                    s.classList.remove('text-white/40');
                } else {
                    s.classList.add('text-white/40');
                    s.classList.remove('text-yellow-400', 'scale-110');
                }
            });
            
            document.getElementById('ratingText').textContent = ratingTexts[rating] || "Selecciona una calificación";
            document.getElementById('ratingText').classList.add('text-yellow-300');
        }
        
        function resetStars() {
            const stars = document.querySelectorAll('.star');
            
            stars.forEach((s, index) => {
                if (index < currentRating) {
                    s.classList.add('text-yellow-400');
                    s.classList.remove('text-white/40');
                } else {
                    s.classList.add('text-white/40');
                    s.classList.remove('text-yellow-400');
                }
            });
            
            if (currentRating === 0) {
                document.getElementById('ratingText').textContent = "Selecciona una calificación";
                document.getElementById('ratingText').classList.remove('text-yellow-300');
            } else {
                document.getElementById('ratingText').textContent = ratingTexts[currentRating];
                document.getElementById('ratingText').classList.add('text-yellow-300');
            }
        }
        
        function toggleRating(star) {
            const clickedRating = parseInt(star.getAttribute('data-value'));
            
            // Si ya está seleccionada, deseleccionar
            if (currentRating === clickedRating) {
                currentRating = 0;
            } else {
                currentRating = clickedRating;
            }
            
            document.getElementById('calificacion').value = currentRating;
            resetStars();
        }
        
        // Contador de caracteres
        document.getElementById('comentario').addEventListener('input', function() {
            const charCount = this.value.length;
            const charCountElement = document.getElementById('charCount');
            charCountElement.textContent = `${charCount}/500`;
            
            if (charCount > 450) {
                charCountElement.classList.add('text-yellow-400');
                charCountElement.classList.remove('text-yellow-200');
            } else {
                charCountElement.classList.remove('text-yellow-400');
                charCountElement.classList.add('text-yellow-200');
            }
        });
        
        // Función para comentarios rápidos
        function insertQuickComment(comment, button) {
            const textarea = document.getElementById('comentario');
            const currentText = textarea.value;
            
            textarea.value = currentText ? `${currentText} ${comment}` : comment;
            
            // Actualizar contador
            const charCount = textarea.value.length;
            document.getElementById('charCount').textContent = `${charCount}/500`;
            
            if (charCount > 450) {
                document.getElementById('charCount').classList.add('text-yellow-400');
                document.getElementById('charCount').classList.remove('text-yellow-200');
            }
            
            // Deshabilitar el botón temporalmente
            button.disabled = true;
            button.classList.add('opacity-50', 'cursor-not-allowed');
            
            // Restaurar después de 1 segundo
            setTimeout(() => {
                button.disabled = false;
                button.classList.remove('opacity-50', 'cursor-not-allowed');
            }, 1000);
            
            // Dar foco al textarea
            textarea.focus();
        }
    </script>
    @endpush
</x-app-layout>
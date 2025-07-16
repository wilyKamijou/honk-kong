@extends('home')

@section('content')
<div class="container-fluid py-4">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0 text-primary">
                    <i class="fas fa-plus-circle me-2"></i>Nuevo Producto
                </h2>
                <div>
                    <a href="/producto" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-chevron-left me-1"></i> Volver al catálogo
                    </a>
                </div>
            </div>
        </div>
        
        <div class="card-body">
            <form action="/producto/guardar" method="POST" class="needs-validation" novalidate>
                @csrf

                <div class="row g-4">
                    <!-- Columna izquierda -->
                    <div class="col-md-6">
                        <!-- Nombre del Producto -->
                        <div class="mb-3">
                            <label for="nombre" class="form-label fw-semibold">
                                <i class="fas fa-tag me-1 text-muted"></i>Nombre del Producto
                            </label>
                            <input type="text" id="nombre" name="nombre" class="form-control" 
                                   placeholder="Ej: Pizza Margarita" required>
                            <div class="invalid-feedback">
                                Por favor ingresa el nombre del producto.
                            </div>
                        </div>

                        <!-- Descripción -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label for="descripcion" class="form-label fw-semibold mb-0">
                                    <i class="fas fa-align-left me-1 text-muted"></i>Descripción
                                </label>
                                <button type="button" id="sugerirDescripcion" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-magic me-1"></i> Generar Descripción
                                </button>
                            </div>
                            <textarea id="descripcion" name="descripcion" class="form-control" 
                                      rows="3" placeholder="Describe las características del producto alimenticio" required></textarea>
                            <div class="invalid-feedback">
                                Por favor ingresa una descripción del producto.
                            </div>
                        </div>
                        
                        <!-- Precio -->
                        <div class="mb-3">
                            <label for="precio" class="form-label fw-semibold">
                                <i class="fas fa-dollar-sign me-1 text-muted"></i>Precio
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" id="precio" name="precio" class="form-control" 
                                       step="0.01" min="0" placeholder="0.00" required>
                            </div>
                            <div class="invalid-feedback">
                                Por favor ingresa un precio válido.
                            </div>
                        </div>
                    </div>

                    <!-- Columna derecha -->
                    <div class="col-md-6">
                        <!-- URL de la Imagen -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label for="imagen_url" class="form-label fw-semibold mb-0">
                                    <i class="fas fa-image me-1 text-muted"></i>URL de la Imagen
                                </label>
                                <div>
                                    <button type="button" id="buscarImagenPixabay" class="btn btn-sm btn-outline-primary me-2">
                                        <i class="fas fa-search me-1"></i> Buscar automático
                                    </button>
                                    <button type="button" id="buscarManualPixabay" class="btn btn-sm btn-outline-info me-2">
                                        <i class="fas fa-external-link-alt me-1"></i> Pixabay
                                    </button>
                                    <button type="button" id="buscarManualUnsplash" class="btn btn-sm btn-outline-info me-2">
                                        <i class="fas fa-external-link-alt me-1"></i> Unsplash
                                    </button>
                                    <button type="button" id="previsualizarImagen" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-eye me-1"></i> Previsualizar
                                    </button>
                                </div>
                            </div>
                            <input type="url" id="imagen_url" name="imagen_url" class="form-control" 
                                   placeholder="https://pixabay.com/images/id-12345/" required>
                            <small class="text-muted">Busca imágenes profesionales o ingresa una URL</small>
                            <div class="invalid-feedback">
                                Por favor ingresa una URL válida de imagen.
                            </div>
                            <div id="imagenPrevisualizacion" class="mt-3 text-center d-none">
                                <img src="" alt="Previsualización" class="img-thumbnail" style="max-height: 200px;">
                                <div class="mt-2 text-muted small">Previsualización de la imagen</div>
                                <div id="creditosImagen" class="text-muted smaller mt-1"></div>
                            </div>
                        </div>

                        <!-- Categoría -->
                        <div class="mb-3">
                            <label for="id_categoria" class="form-label fw-semibold">
                                <i class="fas fa-tags me-1 text-muted"></i>Categoría
                            </label>
                            <select id="id_categoria" name="id_categoria" class="form-select" required>
                                <option value="" disabled selected>Selecciona una categoría</option>
                                @foreach ($categorias as $categoria)
                                    <option value="{{ $categoria->id_categoria }}">{{ $categoria->nombre }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                Por favor selecciona una categoría.
                            </div>
                        </div>

                        <!-- Promoción (Opcional) -->
                        <div class="mb-3">
                            <label for="id_promocion" class="form-label fw-semibold">
                                <i class="fas fa-percentage me-1 text-muted"></i>Promoción (Opcional)
                            </label>
                            <select id="id_promocion" name="id_promocion" class="form-select">
                                <option value="" selected>Sin promoción</option>
                                @foreach ($promociones as $promocion)
                                    <option value="{{ $promocion->id_promocion }}">{{ $promocion->nombre }} ({{ $promocion->descuento }}% OFF)</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="d-flex justify-content-end mt-4">
                    <button type="reset" class="btn btn-outline-secondary me-3">
                        <i class="fas fa-undo me-1"></i> Limpiar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Guardar Producto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    .card {
        border-radius: 0.5rem;
        overflow: hidden;
    }
    
    .form-label {
        font-weight: 500;
    }
    
    .invalid-feedback {
        font-size: 0.85rem;
    }
    
    .was-validated .form-control:invalid, 
    .was-validated .form-select:invalid {
        border-color: #dc3545;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }
    
    .was-validated .form-control:valid, 
    .was-validated .form-select:valid {
        border-color: #198754;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }
    
    #imagenPrevisualizacion img {
        max-width: 100%;
        height: auto;
    }
    
    .smaller {
        font-size: 0.75rem;
    }
    
    .loading-spinner {
        display: inline-block;
        width: 1rem;
        height: 1rem;
        border: 2px solid rgba(0,0,0,.1);
        border-radius: 50%;
        border-top-color: #0d6efd;
        animation: spin 1s ease-in-out infinite;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
</style>
@endpush

@push('js')
<script>
// Validación del formulario
(function () {
  'use strict'
  var forms = document.querySelectorAll('.needs-validation')
  Array.prototype.slice.call(forms)
    .forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }
        form.classList.add('was-validated')
      }, false)
    })
})();

// Mostrar toast de notificación
function showToast(type, title, message) {
    const toast = document.createElement('div');
    toast.className = 'position-fixed bottom-0 end-0 p-3';
    toast.style.zIndex = '11';
    
    let headerClass = type === 'success' ? 'bg-success text-white' : 
                     type === 'error' ? 'bg-danger text-white' : 'bg-primary text-white';
    
    toast.innerHTML = `
        <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header ${headerClass}">
                <strong class="me-auto">${title}</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">${message}</div>
        </div>
    `;
    
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 5000);
}

// Previsualización de imagen
document.getElementById('previsualizarImagen').addEventListener('click', function() {
    const url = document.getElementById('imagen_url').value;
    const previewDiv = document.getElementById('imagenPrevisualizacion');
    const img = previewDiv.querySelector('img');
    
    if (!url) {
        previewDiv.classList.add('d-none');
        return showToast('error', 'Error', 'Por favor ingresa una URL de imagen primero.');
    }
    
    img.src = url;
    previewDiv.classList.remove('d-none');
    
    img.onload = () => previewDiv.classList.remove('d-none');
    img.onerror = () => {
        previewDiv.classList.add('d-none');
        showToast('error', 'Error', 'No se pudo cargar la imagen. Verifica la URL.');
    };
});

// Función principal para búsqueda de imágenes
async function buscarImagen(servicio) {
    const nombreProducto = document.getElementById('nombre').value.trim();
    const btn = document.getElementById('buscarImagenPixabay');
    const originalText = btn.innerHTML;
    
    if (!nombreProducto) {
        return showToast('error', 'Error', 'Ingresa un nombre de producto primero');
    }
    
    btn.innerHTML = '<span class="loading-spinner me-1"></span> Buscando...';
    btn.disabled = true;
    
    try {
        if (servicio === 'pixabay') {
            await buscarEnPixabay(nombreProducto);
        } else if (servicio === 'unsplash') {
            await buscarEnUnsplash(nombreProducto);
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('error', 'Error', error.message.includes('tiempo') ? 
            'La búsqueda tardó demasiado. Intenta con un término más específico.' : error.message);
    } finally {
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
}

// Configuración y búsqueda en Pixabay
async function buscarEnPixabay(nombreProducto) {
    const searchTerm = `${nombreProducto} ${getFoodTypeKeywords(nombreProducto)} food dish restaurant menu`;
    const pixabayApiKey = '50689633-912ccb1eea3d932b2893efca7';
    
    const params = new URLSearchParams({
        key: pixabayApiKey,
        q: searchTerm,
        image_type: 'photo',
        category: 'food',
        per_page: 20,
        orientation: 'horizontal',
        min_width: 600,
        colors: 'red,orange,yellow'
    });
    
    const response = await fetchWithTimeout(`https://pixabay.com/api/?${params}`, 5000);
    
    if (!response.ok) throw new Error('Error al buscar imágenes en Pixabay');
    
    const data = await response.json();
    
    if (data.hits?.length > 0) {
        const sortedHits = data.hits.sort((a, b) => (b.likes/b.views) - (a.likes/a.views));
        const selectedIndex = Math.min(2, Math.floor(Math.random() * 3));
        mostrarImagen(sortedHits[selectedIndex].largeImageURL, nombreProducto, 'Pixabay');
        return true;
    }
    
    // Fallback a Unsplash si Pixabay no da resultados
    return await buscarEnUnsplash(nombreProducto);
}

// Búsqueda en Unsplash
async function buscarEnUnsplash(nombreProducto) {
    const response = await fetchWithTimeout(
        `https://source.unsplash.com/600x400/?${encodeURIComponent(nombreProducto)},food`, 
        3000
    );
    
    if (!response.ok) throw new Error('No se encontraron imágenes para este producto');
    
    mostrarImagen(response.url, nombreProducto, 'Unsplash');
    showToast('info', 'Búsqueda alternativa', 'Usando imagen de respaldo');
    return true;
}

// Mostrar imagen en el formulario
function mostrarImagen(imageUrl, nombreProducto, fuente) {
    document.getElementById('imagen_url').value = imageUrl;
    
    const previewDiv = document.getElementById('imagenPrevisualizacion');
    const img = previewDiv.querySelector('img');
    
    img.onload = () => previewDiv.classList.remove('d-none');
    img.onerror = () => {
        previewDiv.classList.add('d-none');
        showToast('error', 'Error', 'No se pudo cargar la imagen encontrada');
    };
    
    img.src = imageUrl;
    previewDiv.classList.remove('d-none');
    
    document.getElementById('creditosImagen').innerHTML = 
        `Imagen de <a href="https://${fuente.toLowerCase()}.com/" target="_blank">${fuente}</a>`;
    
    showToast('success', 'Éxito', `Imagen encontrada para "${nombreProducto}"`);
}

// Fetch con timeout
function fetchWithTimeout(url, timeout) {
    return Promise.race([
        fetch(url),
        new Promise((_, reject) => setTimeout(() => reject(new Error('Tiempo de espera agotado')), timeout))
    ]);
}

// Palabras clave por tipo de comida
function getFoodTypeKeywords(foodName) {
    const keywords = {
        'pizza': 'italian cheese tomato',
        'hamburguesa': 'burger beef bun',
        'ensalada': 'salad fresh vegetables',
        'pasta': 'italian noodles',
        'sushi': 'japanese rice fish',
        'taco': 'mexican',
        'helado': 'ice cream dessert',
        'pastel': 'cake dessert sweet',
        'asado': 'barbecue meat grill',
        'sopa': 'soup bowl hot'
    };
    
    const lowerName = foodName.toLowerCase();
    for (const [key, value] of Object.entries(keywords)) {
        if (lowerName.includes(key)) return value;
    }
    return '';
}

// Event listeners para búsqueda automática y manual
document.getElementById('buscarImagenPixabay').addEventListener('click', () => buscarImagen('pixabay'));
document.getElementById('buscarManualPixabay').addEventListener('click', () => {
    const nombre = document.getElementById('nombre').value.trim();
    nombre ? window.open(`https://pixabay.com/images/search/${encodeURIComponent(nombre)}/`, '_blank')
           : showToast('error', 'Error', 'Ingresa un nombre de producto primero');
});
document.getElementById('buscarManualUnsplash').addEventListener('click', () => {
    const nombre = document.getElementById('nombre').value.trim();
    nombre ? window.open(`https://unsplash.com/s/photos/${encodeURIComponent(nombre)}-food`, '_blank')
           : showToast('error', 'Error', 'Ingresa un nombre de producto primero');
});

// Generador de descripción
document.getElementById('sugerirDescripcion').addEventListener('click', async function() {
    const nombreProducto = document.getElementById('nombre').value;
    const descripcionField = document.getElementById('descripcion');
    const btn = this;
    
    if (!nombreProducto) {
        return showToast('error', 'Error', 'Ingresa el nombre del producto primero.');
    }
    
    const originalText = btn.innerHTML;
    btn.innerHTML = '<span class="loading-spinner me-1"></span> Generando...';
    btn.disabled = true;
    
    try {
        await new Promise(resolve => setTimeout(resolve, 800));
        
        const tiposComida = {
            'pizza': 'deliciosa pizza horneada al horno de leña',
            'hamburguesa': 'jugosa hamburguesa con ingredientes frescos',
            'ensalada': 'fresca ensalada con ingredientes de temporada',
            'pasta': 'exquisita pasta preparada al dente',
            'sushi': 'auténtico sushi preparado por expertos',
            'taco': 'auténtico taco con sabores tradicionales',
            'helado': 'cremoso helado artesanal',
            'pastel': 'exquisito pastel hecho con ingredientes premium',
            'asado': 'suculento asado al carbón',
            'sopa': 'reconfortante sopa casera'
        };
        
        const caracteristicas = [
            'preparado con ingredientes frescos y de la más alta calidad',
            'elaborado artesanalmente para garantizar autenticidad',
            'con sabores que deleitarán tu paladar',
            'perfectamente balanceado para una experiencia gastronómica única',
            'inspirado en recetas tradicionales con un toque moderno',
            'presentado cuidadosamente para realzar su apetitoso aspecto'
        ];
        
        const beneficios = [
            'Ideal para compartir en familia',
            'Perfecto para ocasiones especiales',
            'Una opción saludable y deliciosa',
            'Recomendado por nuestros chefs',
            'El favorito de nuestros clientes',
            'Una explosión de sabores en cada bocado'
        ];
        
        const tipoComida = Object.entries(tiposComida).find(([key]) => 
            nombreProducto.toLowerCase().includes(key))?.[1] || 'platillo';
        
        const descripcion = `Nuestra ${tipoComida}, ${
            caracteristicas[Math.floor(Math.random() * caracteristicas.length)]
        }. ${
            beneficios[Math.floor(Math.random() * beneficios.length)]
        }.`;
        
        descripcionField.value = descripcion;
        showToast('success', 'Descripción generada', 'Se ha creado una descripción para tu producto.');
    } catch (error) {
        console.error('Error:', error);
        showToast('error', 'Error', 'No se pudo generar la descripción.');
    } finally {
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
});
</script>
@endpush
@extends('home')

@section('contenido')
<div class="container mx-auto px-4 py-10">
    <div class="max-w-5xl mx-auto bg-white rounded-lg shadow-lg p-8 border border-gray-200">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Generar informe de datos</h2>

        <form method="GET" action="{{ route('panel.reportes.pdf') }}" target="_blank" id="formReportes">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">

                {{-- Tipo de Informe --}}
                <div>
                    <label for="tabla" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Informe</label>
                    <select name="tabla" id="tabla"
                        class="w-full border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Seleccionar</option>
                        <option value="pedidos" {{ request('tabla') == 'pedidos' ? 'selected' : '' }}>Pedidos</option>
                        <option value="detalle_pedido" {{ request('tabla') == 'detalle_pedido' ? 'selected' : '' }}>Detalle de Pedido</option>
                        <option value="producto" {{ request('tabla') == 'producto' ? 'selected' : '' }}>Producto</option>
                        <option value="user" {{ request('tabla') == 'user' ? 'selected' : '' }}>Usuarios</option>
                        <option value="promociones" {{ request('tabla') == 'promociones' ? 'selected' : '' }}>Promociones</option>
                    </select>
                </div>

                {{-- Categoría --}}
                <div>
                    <label for="categoria_id" class="block text-sm font-medium text-gray-700 mb-1">Categoría (opcional)</label>
                    <select name="categoria_id" id="categoria_id"
                        class="w-full border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-green-500 focus:border-green-500">
                        <option value="">Todas las categorías</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id_categoria }}" {{ request('categoria_id') == $categoria->id_categoria ? 'selected' : '' }}>
                                {{ $categoria->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Desde --}}
                <div>
                    <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 mb-1">Desde</label>
                    <input type="date" name="fecha_inicio" id="fecha_inicio"
                        class="w-full border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-yellow-500 focus:border-yellow-500"
                        value="{{ request('fecha_inicio') }}">
                </div>

                {{-- Hasta --}}
                <div>
                    <label for="fecha_fin" class="block text-sm font-medium text-gray-700 mb-1">Hasta</label>
                    <input type="date" name="fecha_fin" id="fecha_fin"
                        class="w-full border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-pink-500 focus:border-pink-500"
                        value="{{ request('fecha_fin') }}">
                </div>
            </div>

            {{-- Botón --}}
           <div class="mt-6 flex justify-center">
             <button type="submit"
                    class="bg-emerald-700 text-white font-semibold px-6 py-2.5 rounded-lg shadow-md transition-colors duration-300 hover:bg-emerald-600 active:bg-emerald-800">
                Generar Informe
             </button>
          </div>

        </form>

        {{-- Resumen del Filtro --}}
        @if(request('tabla'))
        <div class="mt-8 border border-gray-200 rounded-md p-4 bg-gray-50">
            <h3 class="text-base font-semibold text-gray-700 mb-2">Resumen del filtro:</h3>
            <ul class="text-sm text-gray-600 space-y-1">
                <li><strong>Tabla:</strong> {{ request('tabla') }}</li>
                <li><strong>Categoría:</strong> {{ request('categoria_id') ? $categorias->where('id_categoria', request('categoria_id'))->first()?->nombre : 'Todas' }}</li>
                <li><strong>Desde:</strong> {{ request('fecha_inicio') ?? 'Sin fecha' }}</li>
                <li><strong>Hasta:</strong> {{ request('fecha_fin') ?? 'Sin fecha' }}</li>
            </ul>
        </div>
        @endif
    </div>
</div>




@endsection

# 🎨 Componente Action Button - HCI

## 📋 Descripción

Componente Blade reutilizable para botones de acción en tablas, siguiendo los principios de HCI (Human-Computer Interaction), especialmente la **Ley de Fitts** para garantizar áreas táctiles adecuadas y accesibilidad.

## ✅ Características

- ✅ **Ley de Fitts**: Botones de mínimo 40px (w-10) para facilitar el clic
- ✅ **Consistencia Visual**: Colores y estilos uniformes en toda la aplicación
- ✅ **Accesibilidad**: Tooltips y atributos alt para iconos
- ✅ **Responsive**: Funciona correctamente en móvil y desktop
- ✅ **Reutilizable**: Un solo componente para todos los tipos de acciones

## 🎯 Variantes Disponibles

| Variante | Color | Uso |
|----------|-------|-----|
| `edit` | Azul (`#84b6f4`) | Editar registros |
| `delete` | Rojo (`#e57373`) | Eliminar registros |
| `view` | Azul oscuro (`#4d82bc`) | Ver detalles |
| `download` | Azul marino (`#005187`) | Descargar archivos/PDFs |
| `success` | Verde (`#66bb6a`) | Acciones positivas |
| `warning` | Naranja (`#ffa726`) | Advertencias |
| `info` | Azul claro (`#42a5f5`) | Información |

## 📖 Uso Básico

### 1. Botón de Editar (Link)

```blade
<x-action-button 
    variant="edit" 
    type="link" 
    :href="route('periods.edit', $period)" 
    tooltip="Editar período" />
```

### 2. Botón de Eliminar (Form)

```blade
<x-action-button 
    variant="delete" 
    :formAction="route('periods.destroy', $period)" 
    formMethod="DELETE" 
    class="form-eliminar"
    tooltip="Eliminar período" />
```

### 3. Botón de Ver (Link)

```blade
<x-action-button 
    variant="view" 
    type="link" 
    :href="route('clases.show', $clase)" 
    icon="ver.svg"
    tooltip="Ver clase" />
```

### 4. Botón de Descargar (Link o Submit)

```blade
{{-- Como link --}}
<x-action-button 
    variant="download" 
    type="link" 
    :href="route('reports.download', $report)" 
    tooltip="Descargar PDF" />

{{-- Como submit en formulario --}}
<form method="GET" action="{{ route('items.export') }}">
    @csrf
    <button type="submit"
        class="inline-flex items-center justify-center w-10 px-3 py-2 bg-[#005187] hover:bg-[#4d82bc] text-white rounded-lg text-xs font-medium transition"
        title="Descargar Excel">
        <img src="{{ asset('icons/download.svg') }}" alt="Descargar" class="w-5 h-5">
    </button>
</form>
```

### 4. Botón Normal (Button)

```blade
<x-action-button 
    variant="info" 
    type="button" 
    @click="openModal()" 
    tooltip="Más información" />
```

## 🛠️ Parámetros

| Parámetro | Tipo | Default | Descripción |
|-----------|------|---------|-------------|
| `variant` | string | `'edit'` | Variante de color (edit, delete, view, success, warning, info) |
| `type` | string | `'button'` | Tipo: button, link, submit |
| `href` | string | `null` | URL para tipo link |
| `icon` | string | `null` | Nombre del archivo de icono (auto si null) |
| `tooltip` | string | `''` | Texto del tooltip al hover |
| `formAction` | string | `null` | URL del formulario (para delete) |
| `formMethod` | string | `'POST'` | Método HTTP del formulario |

## 💡 Iconos por Defecto

Si no especificas el parámetro `icon`, el componente usa estos por defecto:

- `edit` → `edit.svg`
- `delete` → `trashw.svg`
- `view` → `ver.svg`
- `download` → `download.svg`
- `success` → `check.svg`

Puedes sobrescribir especificando manualmente:

```blade
<x-action-button 
    variant="edit" 
    icon="editw.svg"  {{-- Icono blanco alternativo --}}
    ... />
```

## 📦 Ejemplo Completo en Tabla

```blade
<table class="min-w-full">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($items as $item)
            <tr>
                <td>{{ $item->nombre }}</td>
                <td>
                    <div class="flex gap-2">
                        {{-- Ver --}}
                        <x-action-button 
                            variant="view" 
                            type="link" 
                            :href="route('items.show', $item)" 
                            tooltip="Ver detalles" />

                        {{-- Editar --}}
                        <x-action-button 
                            variant="edit" 
                            type="link" 
                            :href="route('items.edit', $item)" 
                            tooltip="Editar" />

                        {{-- Eliminar --}}
                        <x-action-button 
                            variant="delete" 
                            :formAction="route('items.destroy', $item)" 
                            formMethod="DELETE" 
                            class="form-eliminar"
                            tooltip="Eliminar" />
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
```

## 🎨 Personalización

Puedes agregar clases adicionales usando el atributo `class`:

```blade
<x-action-button 
    variant="edit" 
    class="my-custom-class form-eliminar"
    ... />
```

## 🔧 Con Alpine.js

```blade
<div x-data="{ items: @js($items) }">
    <template x-for="item in items">
        <div>
            <a :href="`/items/${item.id}/edit`" 
               class="inline-flex items-center justify-center w-10 px-3 py-2 bg-[#84b6f4] hover:bg-[#84b6f4]/80 text-white rounded-lg text-xs font-medium transition"
               title="Editar">
                <img src="{{ asset('icons/edit.svg') }}" alt="Editar" class="w-4 h-4">
            </a>
        </div>
    </template>
</div>
```

## 📍 Ubicación del Componente

```
resources/views/components/action-button.blade.php
```

## 🎯 Principios HCI Aplicados

### Ley de Fitts
- **Tamaño mínimo**: 40px x 40px (w-10 + padding)
- **Área clickable**: Amplia y fácil de seleccionar
- **Distancia**: Botones relacionados están agrupados

### Ley de Jakob
- **Patrones familiares**: Iconos reconocibles (editar, eliminar, ver)
- **Colores estándar**: Azul para editar, rojo para eliminar
- **Comportamiento esperado**: Links para navegación, forms para acciones destructivas

### Ley de Prägnanz
- **Diseño simple**: Un solo botón, un solo icono
- **Visual limpio**: Sin texto, solo iconos con tooltips
- **Consistencia**: Mismo tamaño y estilo en toda la app

## 🚀 Vistas Actualizadas

Las siguientes vistas ya están usando este componente:

- ✅ `resources/views/periods/index.blade.php`
- ✅ `resources/views/rooms/index.blade.php` (parcial, con Alpine.js)
- ✅ `resources/views/courses/index.blade.php`
- ✅ `resources/views/magisters/index.blade.php`
- ✅ `resources/views/clases/index.blade.php`
- ✅ `resources/views/usuarios/index.blade.php` (estilo aplicado)
- ✅ `resources/views/bitacoras/index.blade.php`
- ✅ `resources/views/bitacoras/show.blade.php`
- ✅ `resources/views/incidencias/index.blade.php`
- ✅ `resources/views/incidencias/estadisticas.blade.php`
- ✅ `resources/views/daily-reports/index.blade.php`
- ✅ `resources/views/informes/index.blade.php`
- ✅ `resources/views/public/informes.blade.php`

## 📝 Notas Importantes

1. **Para formularios de eliminación**: Siempre incluye la clase `form-eliminar` para que funcione con el sistema de confirmación de SweetAlert2.

2. **Tooltips**: Siempre proporciona un tooltip descriptivo para accesibilidad.

3. **Iconos**: Los iconos deben estar en `public/icons/` y ser SVG.

4. **Alpine.js**: Para tablas dinámicas con Alpine.js, usa el HTML directo en lugar del componente Blade.

## 🎨 Colores de la Paleta

```css
/* Editar - Azul claro */
bg-[#84b6f4] hover:bg-[#84b6f4]/80

/* Eliminar - Rojo */
bg-[#e57373] hover:bg-[#f28b82]

/* Ver - Azul oscuro */
bg-[#4d82bc] hover:bg-[#005187]

/* Success - Verde */
bg-[#66bb6a] hover:bg-[#4caf50]

/* Warning - Naranja */
bg-[#ffa726] hover:bg-[#ff9800]

/* Info - Azul claro */
bg-[#42a5f5] hover:bg-[#2196f3]

/* Download - Azul marino */
bg-[#005187] hover:bg-[#4d82bc]
```

## ✅ Checklist de Implementación

Al agregar botones de acción a una nueva vista:

- [ ] Usar componente `<x-action-button>` o el HTML estándar
- [ ] Especificar `variant` apropiada
- [ ] Incluir `tooltip` descriptivo
- [ ] Para eliminar: agregar clase `form-eliminar`
- [ ] Para eliminar: usar `formAction` y `formMethod="DELETE"`
- [ ] Agrupar botones en un contenedor flex con `gap-2`
- [ ] Verificar funcionamiento en móvil y desktop

---

**Creado**: Octubre 2025  
**Principios HCI**: Ley de Fitts, Ley de Jakob, Ley de Prägnanz  
**Versión**: 1.0


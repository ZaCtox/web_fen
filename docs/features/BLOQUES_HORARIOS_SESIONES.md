# 📅 Sistema de Bloques Horarios para Sesiones

## 🎯 Descripción

Sistema flexible y escalable para gestionar horarios de sesiones de clase con soporte para **coffee breaks**, **lunch breaks** y múltiples bloques de clase en un mismo día.

---

## ✨ Características

- ✅ **Flexible**: Soporta cualquier cantidad de bloques
- ✅ **Escalable**: No está limitado a días específicos
- ✅ **Tipos de bloques**: Clase, Coffee Break, Lunch Break
- ✅ **Plantilla predefinida**: Template rápido para sábados
- ✅ **Interfaz intuitiva**: Editor visual con Alpine.js
- ✅ **Compatible**: Funciona con el sistema tradicional de horarios

---

## 🗄️ Estructura de Datos

### Modelo: `ClaseSesion`

**Campo nuevo**: `bloques_horarios` (JSON)

```json
[
  {
    "tipo": "clase",
    "inicio": "09:00",
    "fin": "10:30",
    "nombre": ""
  },
  {
    "tipo": "coffee_break",
    "inicio": "10:30",
    "fin": "11:00",
    "nombre": ""
  },
  {
    "tipo": "clase",
    "inicio": "11:00",
    "fin": "13:30",
    "nombre": ""
  },
  {
    "tipo": "lunch_break",
    "inicio": "13:30",
    "fin": "14:30",
    "nombre": ""
  },
  {
    "tipo": "clase",
    "inicio": "14:30",
    "fin": "15:30",
    "nombre": ""
  },
  {
    "tipo": "clase",
    "inicio": "15:30",
    "fin": "16:30",
    "nombre": ""
  }
]
```

### Tipos de bloques disponibles

| Tipo | Emoji | Descripción |
|------|-------|-------------|
| `clase` | 📚 | Bloque de clase regular |
| `coffee_break` | ☕ | Descanso para café |
| `lunch_break` | 🍽️ | Descanso para almuerzo |

---

## 💻 Uso en el Frontend

### 1. Crear una sesión con bloques horarios

1. Ir a la vista de una clase
2. Click en "Nueva Sesión"
3. Marcar el checkbox **"⏰ Usar bloques horarios (con breaks)"**
4. Opciones:
   - **Opción A**: Click en "🎯 Template Sábado" para cargar bloques predefinidos
   - **Opción B**: Agregar bloques manualmente con los botones:
     - 📚 + Clase
     - ☕ + Coffee Break
     - 🍽️ + Lunch Break
5. Ajustar horarios de cada bloque
6. Guardar la sesión

### 2. Visualización

Las sesiones con bloques se muestran así:

```
Sábado, 4 de Noviembre de 2025

📚 09:00 - 10:30
☕ 10:30 - 11:00 Coffee Break
📚 11:00 - 13:30
🍽️ 13:30 - 14:30 Lunch Break
📚 14:30 - 15:30
📚 15:30 - 16:30
```

---

## 🔧 Uso en el Backend

### Métodos del modelo ClaseSesion

```php
// Verificar si tiene bloques
$sesion->tiene_bloques; // true/false

// Obtener solo bloques de clase (sin breaks)
$sesion->bloques_clase; // Array

// Obtener solo breaks
$sesion->bloques_breaks; // Array

// Obtener duración total de clase en minutos (sin breaks)
$sesion->duracion_clase_minutos; // int
```

### Crear sesión con bloques programáticamente

```php
ClaseSesion::create([
    'clase_id' => $clase->id,
    'fecha' => '2025-11-04',
    'estado' => 'pendiente',
    'bloques_horarios' => [
        ['tipo' => 'clase', 'inicio' => '09:00', 'fin' => '10:30'],
        ['tipo' => 'coffee_break', 'inicio' => '10:30', 'fin' => '11:00'],
        ['tipo' => 'clase', 'inicio' => '11:00', 'fin' => '13:30'],
        ['tipo' => 'lunch_break', 'inicio' => '13:30', 'fin' => '14:30'],
        ['tipo' => 'clase', 'inicio' => '14:30', 'fin' => '15:30'],
        ['tipo' => 'clase', 'inicio' => '15:30', 'fin' => '16:30'],
    ]
]);
```

---

## 📊 API

### Validación en el controlador

```php
$validated = $request->validate([
    'bloques_horarios' => 'nullable|json',
    // ... otros campos
]);
```

El modelo automáticamente convierte JSON ↔ Array gracias a:

```php
protected $casts = [
    'bloques_horarios' => 'array',
];
```

---

## 🎨 Template Predefinido

El botón "🎯 Template Sábado" carga automáticamente:

- **09:00 - 10:30** Clase
- **10:30 - 11:00** Coffee Break
- **11:00 - 13:30** Clase  
- **13:30 - 14:30** Lunch Break
- **14:30 - 15:30** Clase
- **15:30 - 16:30** Clase

**Total**: 6 horas de clase + 1:30h de breaks = 7:30h sesión

---

## 🎨 Visualización en Calendario

Los bloques se muestran en el calendario con **colores diferentes**:

- **📚 Clases** → Color del magíster (ej: verde, azul, rojo según programa)
- **☕ Coffee Break** → Naranja (#f59e0b)
- **🍽️ Lunch Break** → Rojo (#ef4444)

Esto permite ver de un vistazo:
- Cuándo hay clase
- Cuándo son los descansos
- El flujo completo del día

---

## 🔄 Compatibilidad

El sistema es **100% compatible** con sesiones antiguas:

- ✅ Sesiones sin bloques siguen funcionando
- ✅ Se pueden usar `hora_inicio` y `hora_fin` tradicionales
- ✅ Los bloques son **opcionales**
- ✅ No rompe funcionalidad existente

---

## 🚀 Casos de Uso

### Caso 1: Sábados con breaks
```json
{
  "bloques_horarios": [
    {"tipo": "clase", "inicio": "09:00", "fin": "10:30"},
    {"tipo": "coffee_break", "inicio": "10:30", "fin": "11:00"},
    {"tipo": "clase", "inicio": "11:00", "fin": "13:30"}
  ]
}
```

### Caso 2: Clase nocturna sin breaks
```json
{
  "bloques_horarios": [
    {"tipo": "clase", "inicio": "19:00", "fin": "22:00"}
  ]
}
```

### Caso 3: Workshop con múltiples actividades
```json
{
  "bloques_horarios": [
    {"tipo": "clase", "inicio": "09:00", "fin": "11:00", "nombre": "Teoría"},
    {"tipo": "coffee_break", "inicio": "11:00", "fin": "11:15"},
    {"tipo": "clase", "inicio": "11:15", "fin": "13:00", "nombre": "Práctica"}
  ]
}
```

---

## 📝 Notas Técnicas

### Migración
```bash
php artisan migrate
```

Archivo: `2025_10_16_164121_add_bloques_horarios_to_clase_sesiones_table.php`

### Archivos Modificados

1. **Migración**: `database/migrations/2025_10_16_164121_add_bloques_horarios_to_clase_sesiones_table.php`
2. **Modelo**: `app/Models/ClaseSesion.php`
3. **Controlador**: `app/Http/Controllers/ClaseSesionController.php`
4. **Vista**: `resources/views/clases/show.blade.php`
5. **Modal**: `resources/views/clases/partials/sesion-modal.blade.php`

---

## 🎯 Extensibilidad Futura

El sistema está preparado para agregar:

- ✅ Más tipos de bloques (examen, actividad, presentación, etc.)
- ✅ Campo `ubicacion` por bloque
- ✅ Campo `profesor` por bloque
- ✅ Colores personalizados por tipo
- ✅ Notificaciones antes de cada bloque

---

## 📞 Soporte

Para más información sobre este sistema, contactar al equipo de desarrollo.

**Creado**: Octubre 2025  
**Versión**: 1.0


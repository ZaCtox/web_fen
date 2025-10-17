# ⚡ Guía Rápida: Bloques Horarios

## 🎯 ¿Qué es?

Un sistema flexible para definir horarios de clase con **coffee breaks** y **lunch breaks**.

---

## 📋 Cómo Usar (Interfaz Web)

### 1️⃣ Crear Sesión con Bloques

```
Ir a Clases → [Tu Clase] → "Nueva Sesión"
│
├─ Fecha: [Seleccionar fecha]
│
├─ ✅ Marcar: "⏰ Usar bloques horarios (con breaks)"
│
├─ Click en: "🎯 Template Sábado" (opcional)
│   └─ Esto carga automáticamente:
│       • 09:00-10:30 Clase
│       • 10:30-11:00 Coffee Break
│       • 11:00-13:30 Clase
│       • 13:30-14:30 Lunch Break
│       • 14:30-15:30 Clase
│       • 15:30-16:30 Clase
│
├─ Ajustar horarios según necesites
│
└─ Guardar
```

### 2️⃣ Agregar Bloques Manualmente

```
Botones disponibles:
├─ 📚 + Clase ........... Bloque de clase
├─ ☕ + Coffee Break .... Descanso café (10-30 min)
└─ 🍽️ + Lunch Break .... Descanso almuerzo (30-60 min)

Para cada bloque:
├─ Tipo: [Seleccionar]
├─ Inicio: [HH:MM]
├─ Fin: [HH:MM]
└─ ❌ [Eliminar]
```

---

## 👀 Vista de Sesión

### Con bloques horarios:
```
📅 Sábado, 4 de Noviembre de 2025

📚 09:00 - 10:30
☕ 10:30 - 11:00 Coffee Break
📚 11:00 - 13:30
🍽️ 13:30 - 14:30 Lunch Break
📚 14:30 - 15:30
📚 15:30 - 16:30

⏳ Pendiente
```

### Sin bloques (modo tradicional):
```
📅 Viernes, 3 de Noviembre de 2025

⏰ 19:00 - 22:00 [Presencial]

⏳ Pendiente
```

---

## 💡 Ejemplos Prácticos

### Ejemplo 1: Sábado Completo
```
09:00 - 10:30  📚 Clase (Teoría)
10:30 - 11:00  ☕ Coffee Break
11:00 - 13:30  📚 Clase (Práctica)
13:30 - 14:30  🍽️ Lunch Break
14:30 - 16:30  📚 Clase (Taller)
```

### Ejemplo 2: Clase Nocturna
```
19:00 - 22:00  📚 Clase
```

### Ejemplo 3: Workshop de Día
```
09:00 - 12:00  📚 Clase (Mañana)
12:00 - 13:00  🍽️ Lunch Break
13:00 - 17:00  📚 Clase (Tarde)
```

---

## ⚙️ Características

| Característica | Descripción |
|----------------|-------------|
| 🔄 **Flexible** | Cualquier cantidad de bloques |
| 🎯 **Templates** | Plantillas predefinidas |
| ⏰ **Horarios libres** | Sin restricciones de hora |
| 📅 **Cualquier día** | No limitado a sábados |
| 🔙 **Compatible** | Funciona con sistema antiguo |

---

## ❓ Preguntas Frecuentes

**P: ¿Es obligatorio usar bloques?**  
R: No, es opcional. Puedes seguir usando horarios simples.

**P: ¿Puedo editar bloques después?**  
R: Sí, editando la sesión.

**P: ¿Funciona para viernes también?**  
R: Sí, funciona para cualquier día.

**P: ¿Puedo tener más de 2 breaks?**  
R: Sí, puedes agregar los que necesites.

**P: ¿Qué pasa con sesiones antiguas?**  
R: Se mantienen sin cambios, totalmente compatible.

---

## 🚨 Importante

- ✅ Los horarios de bloques NO deben traslaparse
- ✅ Se recomienda ordenar bloques cronológicamente
- ✅ El template es solo una ayuda, puedes modificarlo completamente

---

## 📚 Documentación Completa

Ver: `docs/features/BLOQUES_HORARIOS_SESIONES.md`


# 📱 Prompt para Crear App Android con Kotlin - Web FEN

## 🎯 PROMPT PARA CURSOR

```
Necesito crear una aplicación Android nativa con Kotlin para consumir mi API REST de Laravel.

## ⭐ INSTRUCCIÓN PRINCIPAL

**IMPORTANTE: Implementa SOLO LA FASE 1 (Login + Vistas Públicas)**

Por favor, crea una app Android con las siguientes características:

### 🎯 Objetivo de la Fase 1:
Crear una aplicación que permita:
1. **Login de usuarios** (con persistencia de sesión)
2. **Ver información pública** sin necesidad de login:
   - Calendario de eventos académicos
   - Lista de salas disponibles
   - Personal de la facultad (staff)
   - Programas de magíster
3. **Navegación básica** con Bottom Navigation (3 tabs)
4. **Perfil** que muestre el usuario logueado o botón de login

**NO implementes todavía:**
- ❌ Dashboard con estadísticas
- ❌ Crear/editar reportes diarios
- ❌ Gestión de incidencias
- ❌ Funcionalidades de administrador

Una vez que esta Fase 1 funcione correctamente, implementaremos el resto en fases posteriores.

## 📋 INFORMACIÓN DE LA API

**Base URL:** https://webfen-production-e638.up.railway.app/api

**Autenticación:** Laravel Sanctum (Bearer Token)

**Formato de respuesta estándar:**
```json
{
    "success": true,
    "data": { ... },
    "message": "Mensaje descriptivo"
}
```

## 🔐 ENDPOINTS DE AUTENTICACIÓN

1. **POST /api/register**
   - Body: { name, email, password, password_confirmation, rol }
   - Response: { success, message, user, token }

2. **POST /api/login**
   - Body: { email, password }
   - Response: { success, message, user, token }

3. **GET /api/user** (requiere auth)
   - Headers: { Authorization: Bearer {token} }
   - Response: { success, data: { id, name, email, rol, created_at, updated_at } }

4. **POST /api/logout** (requiere auth)
   - Response: { success, message }

## 📊 ENDPOINTS PRINCIPALES

### Daily Reports (Reportes Diarios)
- GET /api/daily-reports - Listar reportes (paginado)
- POST /api/daily-reports - Crear reporte
- GET /api/daily-reports/{id} - Ver reporte
- PUT /api/daily-reports/{id} - Actualizar reporte
- DELETE /api/daily-reports/{id} - Eliminar reporte
- GET /api/daily-reports/{id}/download-pdf - Descargar PDF
- GET /api/daily-reports-statistics - Estadísticas
- GET /api/daily-reports-resources - Recursos para formularios

**Campos del reporte:**
- title (string, requerido)
- date (date, requerido)
- entries (array de objetos):
  - tipo (string: "Tarea", "Incidencia", "Novedad")
  - tipo_registro (string: "normal", "importante", "urgente")
  - descripcion (text)
  - hora (string, requerido)
  - escala (integer 1-10, requerido)
  - programa (string, requerido)
  - area (string, requerido)
  - tarea (text)
  - imagen (file, opcional)

### Staff (Personal)
- GET /api/staff?search={query}&cargo={cargo} - Listar personal
- POST /api/staff - Crear personal
- GET /api/staff/{id} - Ver personal
- PUT /api/staff/{id} - Actualizar
- DELETE /api/staff/{id} - Eliminar

**Campos:** nombre, cargo, telefono, email

### Magisters (Programas)
- GET /api/magisters - Listar programas
- POST /api/magisters - Crear
- GET /api/magisters/{id} - Ver
- PUT /api/magisters/{id} - Actualizar
- DELETE /api/magisters/{id} - Eliminar

**Campos:** nombre, color, encargado, telefono, correo

### Courses (Cursos)
- GET /api/courses?magister_id={id} - Listar cursos
- POST /api/courses - Crear
- GET /api/courses/{id} - Ver
- PUT /api/courses/{id} - Actualizar
- DELETE /api/courses/{id} - Eliminar

**Campos:** nombre, magister_id, period_id

### Rooms (Salas)
- GET /api/rooms?search={query}&has_projector={bool} - Listar salas
- POST /api/rooms - Crear
- GET /api/rooms/{id} - Ver
- PUT /api/rooms/{id} - Actualizar
- DELETE /api/rooms/{id} - Eliminar
- GET /api/rooms-public - Vista pública

**Campos:** name, location, capacity, has_projector, has_computer, has_air_conditioning, etc.

### Incidents (Incidencias)
- GET /api/incidents?room_id={id}&estado={estado} - Listar
- POST /api/incidents - Crear
- GET /api/incidents/{id} - Ver
- PUT /api/incidents/{id} - Actualizar
- DELETE /api/incidents/{id} - Eliminar
- GET /api/incidents-statistics - Estadísticas

**Campos:** titulo, descripcion, room_id, imagen, nro_ticket, estado (pendiente/resuelta/no_resuelta)

### Emergencies (Emergencias)
- GET /api/emergencies - Listar
- POST /api/emergencies - Crear
- GET /api/emergencies/{id} - Ver
- PUT /api/emergencies/{id} - Actualizar
- DELETE /api/emergencies/{id} - Eliminar
- POST /api/emergencies/{id}/deactivate - Desactivar
- GET /api/emergencies/active - Emergencia activa

**Campos:** title, message, active (boolean), expires_at

### Events (Eventos - Calendario)
- GET /api/events?magister_id={id}&room_id={id}&start={date}&end={date} - Listar
- POST /api/events - Crear
- GET /api/events/{id} - Ver
- PUT /api/events/{id} - Actualizar
- DELETE /api/events/{id} - Eliminar
- GET /api/events/calendario - Para calendario móvil
- GET /api/events/public - Eventos públicos (sin auth)

**Campos:** title, description, magister_id, start_time, end_time, room_id, type

### Periods (Períodos Académicos)
- GET /api/periods?anio={year}&anio_ingreso={year} - Listar
- POST /api/periods - Crear
- GET /api/periods/{id} - Ver
- PUT /api/periods/{id} - Actualizar
- DELETE /api/periods/{id} - Eliminar

**Campos:** anio, numero, fecha_inicio, fecha_fin, anio_ingreso

### Admin Dashboard
- GET /api/admin/dashboard - Estadísticas completas del sistema
  - Incluye: usuarios, staff, salas, cursos, incidencias, emergencias, reportes diarios
  - Estadísticas de severidad (1-10)
  - Estadísticas por programa y área
  - Actividad reciente

### Search (Búsqueda Global)
- GET /api/search?q={query} - Búsqueda global en toda la plataforma

## 🏗️ ARQUITECTURA REQUERIDA

Quiero que implementes:

1. **Arquitectura MVVM (Model-View-ViewModel)**
   - Repository pattern para la capa de datos
   - UseCase/Interactor para la lógica de negocio

2. **Tecnologías:**
   - Kotlin + Coroutines + Flow
   - Jetpack Compose para UI
   - Retrofit + OkHttp para networking
   - Hilt para inyección de dependencias
   - Room para caché local
   - Coil para carga de imágenes
   - DataStore para preferencias (guardar token)

3. **Estructura de carpetas:**
   ```
   app/
   ├── data/
   │   ├── local/
   │   │   ├── dao/
   │   │   └── database/
   │   ├── remote/
   │   │   ├── api/
   │   │   └── dto/
   │   └── repository/
   ├── domain/
   │   ├── model/
   │   ├── repository/
   │   └── usecase/
   ├── presentation/
   │   ├── auth/
   │   ├── dashboard/
   │   ├── daily_reports/
   │   ├── incidents/
   │   ├── calendar/
   │   ├── staff/
   │   └── common/
   │       └── components/
   └── di/
   ```

4. **Modelos principales:**
   - User (id, name, email, rol)
   - DailyReport (id, title, date, entries, user)
   - ReportEntry (tipo, tipo_registro, descripcion, hora, escala, programa, area, tarea, imagen)
   - Staff (nombre, cargo, telefono, email)
   - Incident (titulo, descripcion, room, imagen, nro_ticket, estado)
   - Room (name, location, capacity, equipamiento)
   - Event (title, description, start_time, end_time, room, magister)
   - Emergency (title, message, active, expires_at)

5. **Features principales:**
   - Login/Registro con persistencia de sesión
   - Dashboard con estadísticas
   - Crear/Editar reportes diarios con:
     * Indicador de severidad visual (1-10 con colores)
     * Selector de programa (magíster)
     * Selector de área
     * Campo de horario
     * Opción de subir imagen
   - Lista de incidencias con filtros
   - Calendario de eventos con FullCalendar-like view
   - Gestión de emergencias activas
   - Perfil de usuario
   - Pull-to-refresh en listas
   - Paginación infinita
   - Manejo de errores y estados de carga
   - Modo offline básico (caché)

6. **UI/UX:**
   - Material Design 3
   - Tema claro/oscuro
   - Bottom Navigation con 5 tabs: Dashboard, Reportes, Calendario, Incidencias, Perfil
   - Floating Action Button para crear nuevo reporte/incidencia
   - Cards para listas
   - Shimmer effect para loading states
   - Snackbars para mensajes de éxito/error

7. **Seguridad:**
   - Guardar token en DataStore encriptado
   - Interceptor para agregar Bearer token automáticamente
   - Refresh automático al abrir la app si hay token válido
   - Logout automático si token inválido (401)

8. **Particularidades del indicador de severidad:**
   - Escala visual de 1 a 10
   - Colores específicos:
     * 1-2: Verde (#4DBCC6, #3C9EAA) - Normal
     * 3-4: Amarillo oscuro (#8B8232, #B4A53C) - Leve
     * 5-6: Naranja (#FFCC00, #FF9900) - Moderado
     * 7-8: Naranja rojizo (#FF6600, #FF3300) - Fuerte
     * 9-10: Rojo (#FF0000, #CC0000) - Crítico
   - Debe ser clickeable y mostrar selección visualmente

9. **Extras:**
   - Descargar PDF de reportes
   - Compartir reportes
   - Notificaciones para emergencias activas
   - Búsqueda global con sugerencias

## 🎨 DISEÑO Y ESTILO VISUAL

### Paleta de Colores Principal
```kotlin
// Color Scheme - basado en la web
object AppColors {
    // Primarios
    val Primary = Color(0xFF0F172A)        // Azul oscuro (fondo principal)
    val PrimaryLight = Color(0xFF1E293B)   // Azul oscuro claro
    val Secondary = Color(0xFF3B82F6)      // Azul brillante (botones, enlaces)
    val Accent = Color(0xFF10B981)         // Verde (éxito)
    
    // Superficie y fondo
    val Background = Color(0xFFF8FAFC)     // Gris muy claro
    val Surface = Color(0xFFFFFFFF)        // Blanco
    val SurfaceDark = Color(0xFF0F172A)    // Fondo oscuro
    
    // Texto
    val TextPrimary = Color(0xFF1E293B)    // Texto principal
    val TextSecondary = Color(0xFF64748B)  // Texto secundario
    val TextWhite = Color(0xFFFFFFFF)      // Texto en fondos oscuros
    
    // Estados
    val Success = Color(0xFF10B981)        // Verde
    val Warning = Color(0xFFF59E0B)        // Amarillo/Naranja
    val Error = Color(0xFFEF4444)          // Rojo
    val Info = Color(0xFF3B82F6)           // Azul
    
    // Bordes y divisores
    val Border = Color(0xFFE2E8F0)
    val Divider = Color(0xFFCBD5E1)
}
```

### Colores del Indicador de Severidad (1-10)
```kotlin
object SeverityColors {
    // Normal (1-2) - Verde azulado
    val Level1 = Color(0xFF4DBCC6)
    val Level2 = Color(0xFF3C9EAA)
    
    // Leve (3-4) - Amarillo oscuro
    val Level3 = Color(0xFF8B8232)
    val Level4 = Color(0xFFB4A53C)
    
    // Moderado (5-6) - Naranja
    val Level5 = Color(0xFFFFCC00)
    val Level6 = Color(0xFFFF9900)
    
    // Fuerte (7-8) - Naranja rojizo
    val Level7 = Color(0xFFFF6600)
    val Level8 = Color(0xFFFF3300)
    
    // Crítico (9-10) - Rojo
    val Level9 = Color(0xFFFF0000)
    val Level10 = Color(0xFFCC0000)
    
    fun getColor(level: Int): Color {
        return when (level) {
            1 -> Level1
            2 -> Level2
            3 -> Level3
            4 -> Level4
            5 -> Level5
            6 -> Level6
            7 -> Level7
            8 -> Level8
            9 -> Level9
            10 -> Level10
            else -> Level5
        }
    }
    
    fun getLabel(level: Int): String {
        return when (level) {
            in 1..2 -> "Normal"
            in 3..4 -> "Leve"
            in 5..6 -> "Moderado"
            in 7..8 -> "Fuerte"
            in 9..10 -> "Crítico"
            else -> "Moderado"
        }
    }
}
```

### Tipografía
```kotlin
// Usa la fuente Inter (similar a la web)
val Typography = Typography(
    displayLarge = TextStyle(
        fontFamily = FontFamily.Default,
        fontWeight = FontWeight.Bold,
        fontSize = 32.sp,
        lineHeight = 40.sp
    ),
    headlineLarge = TextStyle(
        fontFamily = FontFamily.Default,
        fontWeight = FontWeight.SemiBold,
        fontSize = 24.sp,
        lineHeight = 32.sp
    ),
    titleLarge = TextStyle(
        fontFamily = FontFamily.Default,
        fontWeight = FontWeight.SemiBold,
        fontSize = 20.sp,
        lineHeight = 28.sp
    ),
    bodyLarge = TextStyle(
        fontFamily = FontFamily.Default,
        fontWeight = FontWeight.Normal,
        fontSize = 16.sp,
        lineHeight = 24.sp
    ),
    bodyMedium = TextStyle(
        fontFamily = FontFamily.Default,
        fontWeight = FontWeight.Normal,
        fontSize = 14.sp,
        lineHeight = 20.sp
    ),
    labelLarge = TextStyle(
        fontFamily = FontFamily.Default,
        fontWeight = FontWeight.Medium,
        fontSize = 14.sp,
        lineHeight = 20.sp
    )
)
```

### Componentes de UI - Estilo

**1. Cards (Tarjetas)**
```kotlin
// Similar a las tarjetas de la web
Card(
    modifier = Modifier.fillMaxWidth(),
    colors = CardDefaults.cardColors(
        containerColor = Color.White
    ),
    elevation = CardDefaults.cardElevation(
        defaultElevation = 2.dp
    ),
    shape = RoundedCornerShape(12.dp)
) {
    // Contenido
}
```

**2. Botones Principales**
```kotlin
// Botón azul brillante, similar a la web
Button(
    onClick = { },
    colors = ButtonDefaults.buttonColors(
        containerColor = Color(0xFF3B82F6),
        contentColor = Color.White
    ),
    shape = RoundedCornerShape(8.dp),
    modifier = Modifier
        .fillMaxWidth()
        .height(48.dp)
) {
    Text(
        text = "Guardar",
        fontWeight = FontWeight.SemiBold
    )
}
```

**3. Input Fields (Campos de texto)**
```kotlin
OutlinedTextField(
    value = value,
    onValueChange = { },
    modifier = Modifier.fillMaxWidth(),
    colors = OutlinedTextFieldDefaults.colors(
        focusedBorderColor = Color(0xFF3B82F6),
        unfocusedBorderColor = Color(0xFFE2E8F0),
        focusedLabelColor = Color(0xFF3B82F6),
        unfocusedLabelColor = Color(0xFF64748B)
    ),
    shape = RoundedCornerShape(8.dp)
)
```

**4. Indicador de Severidad (componente clave)**
```kotlin
@Composable
fun SeverityIndicator(
    selectedLevel: Int?,
    onLevelSelected: (Int) -> Unit,
    modifier: Modifier = Modifier
) {
    Column(
        modifier = modifier
            .fillMaxWidth()
            .background(
                brush = Brush.horizontalGradient(
                    colors = listOf(
                        Color(0xFFF0FDFA), // Teal-50
                        Color(0xFFFEF2F2)  // Red-50
                    )
                ),
                shape = RoundedCornerShape(12.dp)
            )
            .border(1.dp, Color(0xFFE2E8F0), RoundedCornerShape(12.dp))
            .padding(16.dp)
    ) {
        Text(
            text = "Indicador de Severidad *",
            style = MaterialTheme.typography.titleSmall,
            fontWeight = FontWeight.SemiBold,
            color = Color(0xFF374151)
        )
        
        Spacer(modifier = Modifier.height(16.dp))
        
        // Iconos arriba (cada uno para 2 números)
        Row(
            modifier = Modifier.fillMaxWidth(),
            horizontalArrangement = Arrangement.SpaceBetween
        ) {
            listOf("normal", "leve", "moderado", "fuerte", "critico").forEach { icon ->
                Icon(
                    painter = painterResource(id = getIconResource(icon)),
                    contentDescription = icon,
                    modifier = Modifier.size(32.dp),
                    tint = Color.Unspecified
                )
            }
        }
        
        Spacer(modifier = Modifier.height(12.dp))
        
        // Números (1-10) con colores
        Row(
            modifier = Modifier
                .fillMaxWidth()
                .background(Color.White, RoundedCornernerShape(8.dp))
                .padding(8.dp),
            horizontalArrangement = Arrangement.spacedBy(4.dp)
        ) {
            (1..10).forEach { level ->
                Box(
                    modifier = Modifier
                        .weight(1f)
                        .height(48.dp)
                        .background(
                            color = SeverityColors.getColor(level),
                            shape = RoundedCornerShape(4.dp)
                        )
                        .border(
                            width = if (selectedLevel == level) 2.dp else 0.dp,
                            color = if (selectedLevel == level) Color(0xFF3B82F6) else Color.Transparent,
                            shape = RoundedCornerShape(4.dp)
                        )
                        .clickable { onLevelSelected(level) },
                    contentAlignment = Alignment.Center
                ) {
                    Text(
                        text = level.toString(),
                        color = Color.White,
                        fontSize = 18.sp,
                        fontWeight = FontWeight.Bold
                    )
                }
            }
        }
        
        Spacer(modifier = Modifier.height(12.dp))
        
        // Labels abajo
        Row(
            modifier = Modifier.fillMaxWidth(),
            horizontalArrangement = Arrangement.SpaceBetween
        ) {
            listOf("Normal", "Leve", "Moderado", "Fuerte", "Crítico").forEach { label ->
                Text(
                    text = label,
                    style = MaterialTheme.typography.bodySmall,
                    fontWeight = FontWeight.Medium,
                    color = Color(0xFF374151)
                )
            }
        }
        
        // Mostrar selección actual
        selectedLevel?.let { level ->
            Spacer(modifier = Modifier.height(16.dp))
            Row(
                modifier = Modifier
                    .fillMaxWidth()
                    .background(Color(0xFFEFF6FF), RoundedCornerShape(8.dp))
                    .border(1.dp, Color(0xFFBFDBFE), RoundedCornerShape(8.dp))
                    .padding(12.dp),
                horizontalArrangement = Arrangement.SpaceBetween,
                verticalAlignment = Alignment.CenterVertically
            ) {
                Row(
                    horizontalArrangement = Arrangement.spacedBy(12.dp),
                    verticalAlignment = Alignment.CenterVertically
                ) {
                    Box(
                        modifier = Modifier
                            .size(32.dp)
                            .background(
                                SeverityColors.getColor(level),
                                CircleShape
                            ),
                        contentAlignment = Alignment.Center
                    ) {
                        Text(
                            text = level.toString(),
                            color = Color.White,
                            fontWeight = FontWeight.Bold,
                            fontSize = 16.sp
                        )
                    }
                    Column {
                        Text(
                            text = "Seleccionado: $level",
                            style = MaterialTheme.typography.bodyMedium,
                            fontWeight = FontWeight.SemiBold,
                            color = Color(0xFF1E40AF)
                        )
                        Text(
                            text = SeverityColors.getLabel(level),
                            style = MaterialTheme.typography.bodySmall,
                            color = Color(0xFF3B82F6)
                        )
                    }
                }
                TextButton(onClick = { onLevelSelected(0) }) {
                    Text("✕ Limpiar", color = Color(0xFFEF4444))
                }
            }
        }
    }
}
```

**5. Bottom Navigation**
```kotlin
NavigationBar(
    containerColor = Color.White,
    tonalElevation = 8.dp
) {
    items.forEachIndexed { index, item ->
        NavigationBarItem(
            icon = { Icon(item.icon, contentDescription = item.title) },
            label = { Text(item.title) },
            selected = selectedIndex == index,
            onClick = { onItemSelected(index) },
            colors = NavigationBarItemDefaults.colors(
                selectedIconColor = Color(0xFF3B82F6),
                selectedTextColor = Color(0xFF3B82F6),
                indicatorColor = Color(0xFFEFF6FF),
                unselectedIconColor = Color(0xFF64748B),
                unselectedTextColor = Color(0xFF64748B)
            )
        )
    }
}
```

### Estructura de Pantallas - Referencia Visual

**Dashboard:**
- Header con nombre de usuario y foto de perfil
- Cards con estadísticas (similar a las cards de la web)
- Gráficos de barras para severidad
- Lista de actividad reciente

**Reportes Diarios:**
- FAB para crear nuevo reporte
- Lista con pull-to-refresh
- Cada item muestra: título, fecha, usuario, badge de severidad promedio
- Al hacer clic: pantalla de detalle con todas las entradas

**Formulario de Reporte:**
- AppBar con título y botón guardar
- Campos: Título, Fecha
- Botón "Agregar Entrada"
- Cada entrada en un Card expandible con:
  * Tipo (selector)
  * Tipo de registro (chips: normal, importante, urgente)
  * Descripción (multiline)
  * Horario (time picker)
  * **Indicador de severidad** (componente especial)
  * Programa (dropdown con magisters)
  * Área (dropdown o text field)
  * Tarea (opcional, multiline)
  * Foto (opcional, selector de imagen)

**Calendario:**
- Vista mensual con eventos
- Filtros arriba: Programa, Sala
- Al hacer clic en día: lista de eventos
- Colores por programa (usar el color del magíster)

### Iconos y Recursos

**Usa estos iconos de Material Icons:**
- Dashboard: `Icons.Default.Dashboard`
- Reportes: `Icons.Default.Assignment`
- Calendario: `Icons.Default.CalendarToday`
- Incidencias: `Icons.Default.Warning`
- Perfil: `Icons.Default.Person`
- Agregar: `Icons.Default.Add`
- Editar: `Icons.Default.Edit`
- Eliminar: `Icons.Default.Delete`
- Descargar: `Icons.Default.Download`
- Foto: `Icons.Default.Photo`
- Buscar: `Icons.Default.Search`

**Para los iconos de severidad, incluye estos SVG en `res/drawable`:**
- `ic_severity_normal.xml` (emoji 😊 o check circle verde)
- `ic_severity_leve.xml` (emoji 🙂 o info amarillo)
- `ic_severity_moderado.xml` (emoji 😐 o warning naranja)
- `ic_severity_fuerte.xml` (emoji 😟 o error naranja-rojo)
- `ic_severity_critico.xml` (emoji 😡 o dangerous rojo)

### Animaciones y Transiciones

```kotlin
// Transiciones suaves entre pantallas
val slideInTransition = slideInHorizontally(
    initialOffsetX = { it },
    animationSpec = tween(300)
)

val slideOutTransition = slideOutHorizontally(
    targetOffsetX = { -it },
    animationSpec = tween(300)
)

// Shimmer effect para loading
@Composable
fun ShimmerEffect() {
    val infiniteTransition = rememberInfiniteTransition()
    val alpha = infiniteTransition.animateFloat(
        initialValue = 0.2f,
        targetValue = 0.8f,
        animationSpec = infiniteRepeatable(
            animation = tween(1000),
            repeatMode = RepeatMode.Reverse
        )
    )
    
    Box(
        modifier = Modifier
            .fillMaxWidth()
            .height(100.dp)
            .background(
                Color.Gray.copy(alpha = alpha.value),
                RoundedCornerShape(8.dp)
            )
    )
}
```

### Ejemplo de Diseño - Importa este estilo visual

Por favor, **crea la app con el mismo look and feel de la aplicación web**:
- Fondos claros (#F8FAFC)
- Cards blancas con sombras sutiles
- Botones azules (#3B82F6) con esquinas redondeadas
- Textos en gris oscuro (#1E293B)
- Iconos Material Design
- Espaciado generoso (16dp, 24dp)
- Bordes redondeados (8dp para campos, 12dp para cards)
- **El indicador de severidad EXACTAMENTE como está en la web**: 10 bloques de colores en fila, números blancos grandes, selección con borde azul

## 📝 NOTAS IMPORTANTES

- La API usa Laravel Sanctum, el token se obtiene en login/register
- Todas las rutas protegidas requieren: `Authorization: Bearer {token}`
- El formato de fecha es ISO 8601: "YYYY-MM-DD"
- Las imágenes se suben con multipart/form-data
- La paginación usa `?page=1&per_page=15`
- Los filtros se pasan como query params
- **IMPORTANTE**: El indicador de severidad debe verse EXACTAMENTE como en la web, con los mismos colores y comportamiento

## 🎯 PRIORIDADES Y PLAN DE DESARROLLO

### ⭐ FASE 1: FUNDAMENTOS (HACER PRIMERO)

**Implementa SOLO estas funcionalidades primero:**

1. **Sistema de Autenticación** 🔐
   - Pantalla de Login
   - Manejo de token (DataStore)
   - Interceptor para agregar Bearer token
   - Logout

2. **Vistas Públicas (Sin Autenticación)** 🌐
   - Calendario de eventos públicos
   - Lista de salas públicas
   - Lista de personal (staff)
   - Lista de programas de magíster
   - Lista de novedades/noticias

3. **Navegación Básica** 🧭
   - Bottom Navigation con 3 tabs iniciales:
     * Inicio (vistas públicas)
     * Calendario (público)
     * Perfil (login/logout)

**NO implementes todavía:**
- ❌ Dashboard con estadísticas
- ❌ Crear reportes diarios
- ❌ Gestión de incidencias
- ❌ Funcionalidades de administrador

---

### 📋 FASE 2 Y 3 (Después de completar Fase 1)

**Fase 2:** Dashboard y Reportes Diarios
**Fase 3:** Incidencias y funcionalidades admin

---

## 🔑 ENDPOINTS PARA LA FASE 1

### Autenticación (IMPLEMENTAR PRIMERO):
```
POST /api/login
POST /api/register (opcional, solo si quieres auto-registro)
GET  /api/user (requiere auth)
POST /api/logout (requiere auth)
```

### Vistas Públicas (SIN AUTENTICACIÓN):
```
GET /api/public/events              - Eventos del calendario
GET /api/public/rooms               - Salas disponibles
GET /api/public/staff               - Personal de la facultad
GET /api/public/magisters           - Programas de magíster
GET /api/public/magisters-with-course-count  - Magisters con conteo
GET /api/public/courses             - Cursos
GET /api/public/courses/magister/{id}  - Cursos por magíster
GET /api/emergencies/active         - Emergencia activa (si existe)
```

---

## 📝 INSTRUCCIONES INICIALES

Por favor, implementa **SOLO LA FASE 1**:

1. **Estructura del proyecto:**
   - Arquitectura MVVM con Repository Pattern
   - Retrofit + Hilt + Jetpack Compose
   - DataStore para token

2. **Pantallas a crear (Fase 1):**
   
   **A) Pantallas de Autenticación:**
   - `LoginScreen` - Login con email/password
     * Input de email
     * Input de password (con show/hide)
     * Botón "Iniciar Sesión"
     * Mensaje de error si falla
     * Loading state
   
   **B) Pantallas Públicas (SIN AUTENTICACIÓN REQUERIDA):**
   - `PublicHomeScreen` - Pantalla inicial con secciones:
     * Banner de emergencia activa (si existe)
     * Grid con accesos rápidos (Calendario, Salas, Personal, Programas)
     * Lista de novedades recientes
   
   - `PublicCalendarScreen` - Calendario de eventos públicos
     * Vista de calendario mensual
     * Filtros: Programa (magister), Sala
     * Lista de eventos por día al hacer clic
     * Colores por programa
   
   - `PublicRoomsScreen` - Lista de salas disponibles
     * Lista con cards de salas
     * Información: nombre, ubicación, capacidad
     * Iconos de equipamiento (proyector, computador, aire acondicionado)
     * Búsqueda por nombre
   
   - `PublicStaffScreen` - Lista de personal de la facultad
     * Cards con foto, nombre, cargo
     * Email y teléfono
     * Filtro por cargo
     * Búsqueda por nombre
   
   - `PublicMagistersScreen` - Programas de magíster
     * Lista de programas con colores
     * Contador de cursos por programa
     * Al hacer clic: ver cursos del programa
   
   **C) Pantalla de Perfil:**
   - `ProfileScreen` - Perfil básico
     * Si NO está logueado: Mostrar botón "Iniciar Sesión"
     * Si SÍ está logueado: Mostrar datos del usuario + botón "Cerrar Sesión"

3. **Componentes clave:**
   - `AuthRepository` - Login, logout, persistencia de token
   - `PublicRepository` - Endpoints públicos
   - Interceptor para agregar Bearer token automáticamente
   - Manejo de estados (Loading, Success, Error)

4. **NO implementes todavía:**
   - Dashboard con estadísticas
   - Crear/editar reportes diarios
   - Indicador de severidad
   - Gestión de incidencias
   - Funcionalidades de admin

**Una vez que la Fase 1 esté funcionando, continuaremos con el dashboard y reportes.**

---

Por favor, crea toda la estructura del proyecto, los modelos, repositorios, ViewModels, y las pantallas de la FASE 1 con Jetpack Compose. Incluye manejo de errores, estados de carga, y validación de formularios.
```

---

## 📦 DEPENDENCIAS SUGERIDAS (build.gradle.kts)

```kotlin
// Retrofit
implementation("com.squareup.retrofit2:retrofit:2.9.0")
implementation("com.squareup.retrofit2:converter-gson:2.9.0")
implementation("com.squareup.okhttp3:okhttp:4.11.0")
implementation("com.squareup.okhttp3:logging-interceptor:4.11.0")

// Hilt
implementation("com.google.dagger:hilt-android:2.48")
kapt("com.google.dagger:hilt-compiler:2.48")
implementation("androidx.hilt:hilt-navigation-compose:1.1.0")

// Room
implementation("androidx.room:room-runtime:2.6.0")
implementation("androidx.room:room-ktx:2.6.0")
kapt("androidx.room:room-compiler:2.6.0")

// DataStore
implementation("androidx.datastore:datastore-preferences:1.0.0")

// Compose
implementation("androidx.compose.ui:ui:1.5.4")
implementation("androidx.compose.material3:material3:1.1.2")
implementation("androidx.navigation:navigation-compose:2.7.5")

// Coil (imágenes)
implementation("io.coil-kt:coil-compose:2.5.0")

// Coroutines
implementation("org.jetbrains.kotlinx:kotlinx-coroutines-android:1.7.3")
```

---

## 🎨 EJEMPLO DE RESPUESTA DE LA API

```json
// GET /api/daily-reports
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "title": "Reporte 15 Oct 2025",
        "date": "2025-10-15",
        "user": {
          "id": 1,
          "name": "María González",
          "rol": "administrativo"
        },
        "entries": [
          {
            "id": 1,
            "tipo": "Tarea",
            "tipo_registro": "normal",
            "descripcion": "Coordinación de salas",
            "hora": "09:00",
            "escala": 3,
            "programa": "Magíster en Finanzas",
            "area": "Coordinación Académica",
            "tarea": "Revisar disponibilidad de salas",
            "imagen": "https://cloudinary.com/...",
            "nivel_severidad": "Leve",
            "color_escala": "#8B8232"
          }
        ],
        "created_at": "2025-10-15T10:30:00.000000Z"
      }
    ],
    "total": 25,
    "per_page": 15,
    "last_page": 2
  },
  "message": "Reportes diarios obtenidos exitosamente"
}
```

---

## 📄 ARCHIVO COMPLETO DE RUTAS API (routes/api.php)

Para tu referencia completa, aquí está el contenido exacto del archivo de rutas de la API:

```php
<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClaseController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\DailyReportController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\EmergencyController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\IncidentController;
use App\Http\Controllers\Api\MagisterController;
use App\Http\Controllers\Api\PeriodController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\StaffController;
use App\Models\Period;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;

// 🌐 PREFIJO API + NOMBRE DE RUTAS
Route::name('api.')->group(function () {

    // 🔹 RUTAS PÚBLICAS (sin auth)
    Route::get('/trimestre-siguiente', function (Request $request) {
        $fecha = Carbon::parse($request->query('fecha'));
        $siguiente = Period::whereDate('fecha_inicio', '>', $fecha)
            ->orderBy('fecha_inicio')
            ->first();

        return response()->json([
            'fecha_inicio' => $siguiente?->fecha_inicio?->toDateString(),
            'anio' => $siguiente?->anio,
            'numero' => $siguiente?->numero,
            'error' => $siguiente ? null : 'No se encontró trimestre siguiente.',
        ]);
    })->name('trimestre-siguiente');

    Route::get('/trimestre-anterior', function (Request $request) {
        $fecha = Carbon::parse($request->query('fecha'));
        $anterior = Period::whereDate('fecha_fin', '<', $fecha)
            ->orderByDesc('fecha_fin')
            ->first();

        return response()->json([
            'fecha_inicio' => $anterior?->fecha_inicio?->toDateString(),
            'anio' => $anterior?->anio,
            'numero' => $anterior?->numero,
            'error' => $anterior ? null : 'No se encontró trimestre anterior.',
        ]);
    })->name('trimestre-anterior');

    Route::get('/periodo-por-fecha', function (Request $request) {
        $fecha = Carbon::parse($request->query('fecha'));
        $anioIngreso = $request->query('anio_ingreso');
        
        $query = Period::where('fecha_inicio', '<=', $fecha)
            ->where('fecha_fin', '>=', $fecha);
        
        if ($anioIngreso) {
            $query->where('anio_ingreso', $anioIngreso);
        }
        
        $periodo = $query->first();

        return response()->json(['periodo' => $periodo]);
    })->name('periodo-por-fecha');

    Route::get('/periodo-fecha-inicio', function (Request $request) {
        $anio = $request->query('anio');
        $trimestre = $request->query('trimestre');
        $anioIngreso = $request->query('anio_ingreso');

        $periodo = Period::where('anio', $anio)
            ->where('numero', $trimestre)
            ->when($anioIngreso, fn($q) => $q->where('anio_ingreso', $anioIngreso))
            ->first();

        return response()->json([
            'fecha_inicio' => $periodo?->fecha_inicio?->toDateString(),
            'periodo' => $periodo
        ]);
    })->name('periodo-fecha-inicio');

    Route::get('/trimestres-todos', function () {
        return Period::orderBy('fecha_inicio')->get(['fecha_inicio']);
    })->name('trimestres-todos');

    // ===== RUTAS PÚBLICAS (SIN AUTENTICACIÓN) =====
    Route::prefix('public')->group(function () {
        Route::get('magisters', [MagisterController::class, 'publicIndex']);
        Route::get('magisters-with-course-count', [MagisterController::class, 'publicMagistersWithCourseCount']);
        Route::get('events', [EventController::class, 'publicIndex']);
        Route::get('staff', [StaffController::class, 'publicIndex']);
        Route::get('rooms', [RoomController::class, 'publicIndex']);
        Route::get('courses', [CourseController::class, 'publicIndex']);
        Route::get('courses/magister/{magisterId}', [CourseController::class, 'publicCoursesByMagister']);
        Route::get('courses/magister/{magisterId}/paginated', [CourseController::class, 'publicCoursesByMagisterPaginated']);
    });

    // 🔐 AUTENTICACIÓN
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    // 🔹 RUTAS PROTEGIDAS CON SANCTUM
    Route::middleware(['auth:sanctum'])->group(function () {

        // Usuario actual
        Route::get('/user', [AuthController::class, 'user'])->name('user');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        // 🔍 BÚSQUEDA GLOBAL
        Route::get('/search', [SearchController::class, 'search'])->name('search');

        Route::get('/profile', [AuthController::class, 'user'])->name('user.profile');

        // ADMIN
        Route::middleware('role.api:admin')->group(function () {
            Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
            
            // Gestión de usuarios (solo administradores)
            Route::apiResource('users', UserController::class)->names([
                'index' => 'users.index',
                'store' => 'users.store',
                'show' => 'users.show',
                'update' => 'users.update',
                'destroy' => 'users.destroy',
            ]);
            Route::get('users-statistics', [UserController::class, 'statistics'])->name('users.statistics');
        });

        // USUARIO

        // 🔹 RUTAS ESPECÍFICAS DE PERÍODOS (ANTES DEL APIRESOURCE)
        Route::put('/periods/update-to-next-year', [PeriodController::class, 'actualizarAlProximoAnio'])->name('periods.updateToNextYear');
        Route::post('/periods/trimestre-siguiente', [PeriodController::class, 'trimestreSiguiente'])->name('periods.trimestreSiguiente');
        Route::post('/periods/trimestre-anterior', [PeriodController::class, 'trimestreAnterior'])->name('periods.trimestreAnterior');
        Route::get('/periods/periodo-por-fecha/{fecha}', [PeriodController::class, 'periodoPorFecha'])->name('periods.periodoPorFecha');

        Route::get('clases/simple', [ClaseController::class, 'simple']);
        Route::get('clases/debug', [ClaseController::class, 'debug']);

        // Obtener magísteres con cursos agrupados
        // Rutas adicionales para cursos
        Route::get('courses/magisters-only', [CourseController::class, 'magistersOnly']);
        Route::get('courses/magisters', [CourseController::class, 'magistersWithCourses'])
            ->name('courses.magisters');
        Route::get('courses/magisters-list', [CourseController::class, 'magistersOnly']);
        Route::get('courses/magisters/{id}/courses', [CourseController::class, 'magisterCourses']);

        // 🔹 RECURSOS API CON NOMBRES ÚNICOS
        Route::apiResource('staff', StaffController::class)->names([
            'index' => 'staff.index',
            'store' => 'staff.store',
            'show' => 'staff.show',
            'update' => 'staff.update',
            'destroy' => 'staff.destroy',
        ]);

        Route::apiResource('rooms', RoomController::class)->names([
            'index' => 'rooms.index',
            'store' => 'rooms.store',
            'show' => 'rooms.show',
            'update' => 'rooms.update',
            'destroy' => 'rooms.destroy',
        ]);

        // ⚠️ ESTA RUTA DEBE IR DESPUÉS DE LAS RUTAS ESPECÍFICAS
        Route::apiResource('periods', PeriodController::class)->names([
            'index' => 'periods.index',
            'store' => 'periods.store',
            'show' => 'periods.show',
            'update' => 'periods.update',
            'destroy' => 'periods.destroy',
        ]);

        Route::apiResource('magisters', MagisterController::class)->names([
            'index' => 'magisters.index',
            'store' => 'magisters.store',
            'show' => 'magisters.show',
            'update' => 'magisters.update',
            'destroy' => 'magisters.destroy',
        ]);

        Route::apiResource('incidents', IncidentController::class)->names([
            'index' => 'incidents.index',
            'store' => 'incidents.store',
            'show' => 'incidents.show',
            'update' => 'incidents.update',
            'destroy' => 'incidents.destroy',
        ]);
        
        // Rutas adicionales para Incidents
        Route::get('incidents-statistics', [IncidentController::class, 'estadisticas'])->name('incidents.statistics');

        Route::apiResource('courses', CourseController::class)->names([
            'index' => 'courses.index',
            'store' => 'courses.store',
            'show' => 'courses.show',
            'update' => 'courses.update',
            'destroy' => 'courses.destroy',
        ]);

        // Daily Reports API
        Route::apiResource('daily-reports', DailyReportController::class)->names([
            'index' => 'daily-reports.index',
            'store' => 'daily-reports.store',
            'show' => 'daily-reports.show',
            'update' => 'daily-reports.update',
            'destroy' => 'daily-reports.destroy',
        ]);
        
        // Rutas adicionales para Daily Reports
        Route::get('daily-reports/{dailyReport}/download-pdf', [DailyReportController::class, 'downloadPdf'])->name('daily-reports.download-pdf');
        Route::get('daily-reports-statistics', [DailyReportController::class, 'statistics'])->name('daily-reports.statistics');
        Route::get('daily-reports-resources', [DailyReportController::class, 'resources'])->name('daily-reports.resources');

        Route::apiResource('clases', ClaseController::class)->names([
            'index' => 'clases.index',
            'store' => 'clases.store',
            'show' => 'clases.show',
            'update' => 'clases.update',
            'destroy' => 'clases.destroy',
        ]);

        // 🔹 EVENTOS
        Route::get('/events', [EventController::class, 'index'])->name('events.index');
        Route::post('/events', [EventController::class, 'store'])->name('events.store');
        Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
        Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
        Route::get('/calendario', [EventController::class, 'calendario'])->name('calendario.mobile');
        
        // 🔹 EMERGENCIAS
        Route::get('/emergencies', [EmergencyController::class, 'index'])->name('emergencies.index');
        Route::post('/emergencies', [EmergencyController::class, 'store'])->name('emergencies.store');
        Route::put('/emergencies/{id}', [EmergencyController::class, 'update'])->name('emergencies.update');
        Route::delete('/emergencies/{id}', [EmergencyController::class, 'destroy'])->name('emergencies.destroy');
        Route::patch('/emergencies/{id}/deactivate', [EmergencyController::class, 'deactivate'])->name('emergencies.deactivate');
        Route::get('/emergencies/active', [EmergencyController::class, 'active'])->name('emergencies.active');
    });

});
```

**NOTA IMPORTANTE:** En la Fase 1, solo necesitas implementar:
- Las rutas públicas (`/api/public/*`)
- Las rutas de autenticación (`/api/login`, `/api/user`, `/api/logout`)
- La ruta de emergencia activa (`/api/emergencies/active`)

Las demás rutas (daily-reports, incidents, admin, etc.) las implementarás en las Fases 2 y 3.

---

## 📋 EJEMPLOS DE RESPUESTAS - ENDPOINTS PÚBLICOS (FASE 1)

### 1. GET /api/public/magisters
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "nombre": "Magíster en Finanzas",
      "color": "#3B82F6",
      "encargado": "Dr. Juan Pérez",
      "telefono": "+56912345678",
      "correo": "juan.perez@fen.cl"
    },
    {
      "id": 2,
      "nombre": "Magíster en Economía",
      "color": "#10B981",
      "encargado": "Dra. María González",
      "telefono": "+56987654321",
      "correo": "maria.gonzalez@fen.cl"
    }
  ],
  "message": "Magisters obtenidos exitosamente"
}
```

### 2. GET /api/public/staff
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "nombre": "Ana López",
      "cargo": "Asistente de Postgrado",
      "telefono": "+56912345678",
      "email": "ana.lopez@fen.cl",
      "foto": "https://cloudinary.com/..."
    }
  ],
  "message": "Personal obtenido exitosamente"
}
```

### 3. GET /api/public/rooms
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Sala A101",
      "location": "Edificio Principal, Piso 1",
      "capacity": 40,
      "has_projector": true,
      "has_computer": true,
      "has_air_conditioning": true,
      "has_audio_system": false,
      "has_whiteboard": true
    }
  ],
  "message": "Salas obtenidas exitosamente"
}
```

### 4. GET /api/public/events?start=2025-10-01&end=2025-10-31
```json
{
  "status": "success",
  "data": [
    {
      "id": "event-1",
      "title": "Clase de Finanzas Corporativas",
      "description": "Magíster: Magíster en Finanzas",
      "start": "2025-10-15 09:00:00",
      "end": "2025-10-15 13:00:00",
      "room": {
        "id": 1,
        "name": "Sala A101"
      },
      "magister": {
        "id": 1,
        "name": "Magíster en Finanzas"
      },
      "backgroundColor": "#3B82F6",
      "borderColor": "#3B82F6",
      "type": "clase",
      "modality": "presencial"
    }
  ],
  "meta": {
    "total": 25,
    "max_events": 50,
    "range_start": "2025-10-01",
    "range_end": "2025-10-31",
    "public_view": true
  }
}
```

### 5. GET /api/emergencies/active
```json
{
  "status": "success",
  "data": {
    "id": 1,
    "title": "Suspensión de clases",
    "message": "Por motivo de actividad especial, las clases están suspendidas hoy.",
    "active": true,
    "expires_at": "2025-10-16 18:00:00"
  }
}
```

### 6. POST /api/login
**Request:**
```json
{
  "email": "usuario@fen.cl",
  "password": "password123"
}
```

**Response (Success):**
```json
{
  "message": "Login exitoso",
  "user": {
    "id": 1,
    "name": "Ana López",
    "email": "ana.lopez@fen.cl",
    "rol": "asistente_postgrado"
  },
  "token": "1|abcdefghijklmnopqrstuvwxyz123456"
}
```

**Response (Error):**
```json
{
  "message": "Credenciales inválidas"
}
```
Status: 401

### 7. GET /api/user (con Bearer token)
**Headers:**
```
Authorization: Bearer 1|abcdefghijklmnopqrstuvwxyz123456
```

**Response:**
```json
{
  "status": "success",
  "data": {
    "id": 1,
    "name": "Ana López",
    "email": "ana.lopez@fen.cl",
    "rol": "asistente_postgrado",
    "created_at": "15/10/2025",
    "updated_at": "15/10/2025 10:30"
  }
}
```

---

¡Con este prompt actualizado, Cursor tendrá toda la información necesaria para crear tu app de Android de forma progresiva! 🚀

### 🎯 VENTAJAS DE ESTE ENFOQUE POR FASES:

1. **Desarrollo Incremental** ✅
   - Empiezas con lo básico que funciona
   - Pruebas más tempranas
   - Menos abrumador

2. **Testing más fácil** ✅
   - Puedes testear login y vistas públicas primero
   - Detectas errores antes
   - Iteraciones más rápidas

3. **Mejor para aprender** ✅
   - Entiendes cada parte
   - No te pierdes en código
   - Builds más rápidos

4. **Feedback temprano** ✅
   - Puedes mostrar avances rápido
   - Ajustas según necesites
   - Más motivante ver progreso



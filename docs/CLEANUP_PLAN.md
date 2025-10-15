# 🧹 Plan de Limpieza y Organización - Web FEN

## 📁 ESTRUCTURA PROPUESTA

```
Web_FEN/
├── docs/
│   ├── api/                    # Documentación de API
│   │   ├── API_CONTROLLERS_ANALYSIS.md
│   │   ├── API_ROUTES_COMPLETE.md
│   │   ├── API_IMPROVEMENTS_SUMMARY.md
│   │   └── PROMPT_ANDROID_KOTLIN_APP.md
│   ├── features/               # Documentación de features
│   │   ├── IMPLEMENTACION_HCI_COMPLETA.md
│   │   ├── GESTION_NOVEDADES_COMPLETO.md
│   │   ├── IMPLEMENTACION_NOVEDADES_PUBLICAS.md
│   │   ├── IMPLEMENTACION_TIPO_REGISTROS.md
│   │   ├── MEJORAS_ESTADISTICAS_INCIDENCIAS.md
│   │   └── MEJORAS_HCI_PERFIL.md
│   └── legacy/                 # Documentación antigua/histórica
│       ├── ACTUALIZACION_BOTONES_DOWNLOAD_HCI.md
│       ├── ACTUALIZACION_SEEDERS_Y_CONTROLADORES.md
│       ├── ANALISIS_HCI_STAFF_COMPLETO.md
│       ├── ANALISIS_HCI_STAFF.md
│       ├── ANALISIS_Y_CORRECCION_PERIODS.md
│       ├── BOTONES_DOWNLOAD_AGREGADOS.md
│       ├── BOTONES_TAMAÑO_CONSISTENTE_FINAL.md
│       ├── COMPONENTE_ACTION_BUTTON.md
│       ├── CORRECCION_ERROR_INCIDENCIAS_FILTRADAS.md
│       ├── ELIMINACION_MALLAS_CURRICULARES.md
│       ├── ESTANDARIZACION_ICONOS_BOTONES.md
│       ├── MIGRACION_ANIO_INGRESO.md
│       ├── RESUMEN_BOTONES_HCI.md
│       ├── RESUMEN_FINAL_ELIMINACION_MALLAS.md
│       └── RESUMEN_SESION.md
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
├── resources/
├── routes/
├── storage/
├── tests/
├── README.md
└── README_TESTS.md
```

## ✅ ARCHIVOS A MOVER

### Documentación de API → `docs/api/`
- [x] API_CONTROLLERS_ANALYSIS.md
- [x] API_ROUTES_COMPLETE.md
- [x] API_IMPROVEMENTS_SUMMARY.md
- [x] PROMPT_ANDROID_KOTLIN_APP.md

### Documentación de Features → `docs/features/`
- [x] IMPLEMENTACION_HCI_COMPLETA.md
- [x] GESTION_NOVEDADES_COMPLETO.md
- [x] IMPLEMENTACION_NOVEDADES_PUBLICAS.md
- [x] IMPLEMENTACION_TIPO_REGISTROS.md
- [x] MEJORAS_ESTADISTICAS_INCIDENCIAS.md
- [x] MEJORAS_HCI_PERFIL.md

### Documentación Legacy → `docs/legacy/`
- [x] ACTUALIZACION_BOTONES_DOWNLOAD_HCI.md
- [x] ACTUALIZACION_SEEDERS_Y_CONTROLADORES.md
- [x] ANALISIS_HCI_STAFF_COMPLETO.md
- [x] ANALISIS_HCI_STAFF.md
- [x] ANALISIS_Y_CORRECCION_PERIODS.md
- [x] BOTONES_DOWNLOAD_AGREGADOS.md
- [x] BOTONES_TAMAÑO_CONSISTENTE_FINAL.md
- [x] COMPONENTE_ACTION_BUTTON.md
- [x] CORRECCION_ERROR_INCIDENCIAS_FILTRADAS.md
- [x] ELIMINACION_MALLAS_CURRICULARES.md
- [x] ESTANDARIZACION_ICONOS_BOTONES.md
- [x] MIGRACION_ANIO_INGRESO.md
- [x] RESUMEN_BOTONES_HCI.md
- [x] RESUMEN_FINAL_ELIMINACION_MALLAS.md
- [x] RESUMEN_SESION.md

## 🗑️ ARCHIVOS A ELIMINAR

### Archivos de Testing/Debug Temporales
- [ ] check_api.php (archivo temporal de pruebas)
- [ ] check_sesiones.php (archivo temporal de pruebas)
- [ ] tester.php (archivo temporal de pruebas)

### Archivos Cache en Bootstrap (temporales - regenerables)
- [ ] bootstrap/cache/*.tmp (todos los archivos .tmp)

## ⚠️ MODELOS/CONTROLADORES A REVISAR

### Modelos que parecen no usarse:
1. **Bitacora.php** - ¿Se usa? Parece que fue reemplazado por DailyReport
2. **Informe.php** - ¿Se usa? No veo controlador asociado
3. **Notification.php** - ¿Se usa? No veo funcionalidad de notificaciones
4. **IncidentLog.php** - ¿Se usa? No veo dónde se implementa

### Controladores a verificar:
Revisar en `app/Http/Controllers/` cuáles realmente se están usando

## 📋 PRÓXIMOS PASOS

1. Mover archivos de documentación a sus carpetas correspondientes
2. Eliminar archivos temporales de prueba
3. Limpiar archivos .tmp del cache
4. Verificar modelos y controladores no utilizados
5. Actualizar .gitignore si es necesario
6. Crear un README.md principal mejorado

## 🎯 BENEFICIOS

- ✅ Proyecto más organizado y limpio
- ✅ Más fácil de navegar
- ✅ Documentación mejor estructurada
- ✅ Menos archivos en la raíz del proyecto
- ✅ Mejor para trabajo en equipo
- ✅ Más profesional


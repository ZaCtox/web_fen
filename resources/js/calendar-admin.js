document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');

    // Metas/refs
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
    const storeUrl = document.querySelector('meta[name="store-url"]')?.content || '';
    const showBase = document.querySelector('meta[name="clases-show-base"]')?.content || '/clases';

    const magisterFilter = document.getElementById('magister-filter');
    const roomFilter = document.getElementById('room-filter');
    const cohorteFilter = document.getElementById('cohorte-filter');
    const anioFilter = document.getElementById('anio-filter');
    const trimestreFilter = document.getElementById('trimestre-filter');

    // Helpers
    const fmtTime = (dt) =>
        new Date(dt).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: false });

    const formatForInput = (date) => {
        const local = new Date(date);
        const offset = local.getTimezoneOffset() * 60000;
        return new Date(local - offset).toISOString().slice(0, 16);
    };

    const modalityBadge = (m) => {
        const map = {
            presencial: 'bg-green-100 text-green-800',
            online: 'bg-indigo-100 text-indigo-800',
            hibrida: 'bg-yellow-100 text-yellow-800',
        };
        const cls = map[m] || 'bg-gray-100 text-gray-800';
        return `<span class="inline-flex items-center rounded px-2 py-0.5 text-xs font-medium ${cls}">${m || '—'}</span>`;
    };

    const getClaseIdFromEventId = (id) => {
        const m = String(id).match(/^clase-(\d+)-/);
        return m ? m[1] : null;
    };

    // Render de detalles de evento
    const renderEventDetails = (ev) => {
        const isClase = ev.extendedProps.type === 'clase';
        const claseId = isClase ? getClaseIdFromEventId(ev.id) : null;
        const magister = ev.extendedProps.magister?.name || '—';
        const modalidad = ev.extendedProps.modality || '—';
        const profesor = ev.extendedProps.profesor || ev.extendedProps.teacher || '—';
        const sala = ev.extendedProps.room?.name || 'Sin sala';
        const start = fmtTime(ev.start);
        const end = fmtTime(ev.end);
        const zoom = ev.extendedProps.url_zoom || null;
        const grabacion = ev.extendedProps.url_grabacion || null;
        const desc = (ev.extendedProps.type === 'manual') ? (ev.extendedProps.description || '') : '';

        const zoomBtn = zoom
            ? `<a href="${zoom}" target="_blank" rel="noopener"
           class="inline-flex items-center rounded px-2 py-1 text-xs bg-blue-100 hover:bg-blue-200 text-blue-700">🔗 Zoom</a>` : '';

        const lupaBtn = (isClase && claseId)
            ? `<a href="${showBase}/${claseId}" target="_blank" rel="noopener"
           class="inline-flex items-center rounded px-2 py-1 text-xs bg-gray-100 hover:bg-gray-200 text-gray-700"
           title="Ver detalle de la clase">
           <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
             <path d="M10 4a6 6 0 104.472 10.028l4.75 4.75 1.414-1.414-4.75-4.75A6 6 0 0010 4zm0 2a4 4 0 110 8 4 4 0 010-8z"/>
           </svg>
        </a>` : '';

        const descBlock = desc
            ? `<div class="mt-2 text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">${desc}</div>` : '';

        const grabacionBlock = grabacion
            ? `<div class="mt-3 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                <p class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  <span class="text-2xl">🎥</span>
                  Grabación disponible
                </p>
                <a href="${grabacion}" target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg shadow transition-all duration-200 text-sm">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M8 5v14l11-7z"/>
                  </svg>
                  Ver Grabación en YouTube
                </a>
              </div>` : '';

        return `
      <div class="flex items-start justify-between gap-3">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">${ev.title}</h2>
        <div class="flex items-center gap-2">${zoomBtn}${lupaBtn}</div>
      </div>
      ${descBlock}
      ${grabacionBlock}
      <div class="mt-3 space-y-1 text-sm">
        <div><span class="font-medium">Programa:</span> ${magister}</div>
        <div><span class="font-medium">Modalidad:</span> ${modalityBadge(modalidad)}</div>
        <div><span class="font-medium">Profesor:</span> ${profesor}</div>
        <div><span class="font-medium">Inicio:</span> ${start}</div>
        <div><span class="font-medium">Fin:</span> ${end}</div>
        <div><span class="font-medium">Sala:</span> ${sala}</div>
      </div>
    `;
    };

    // FullCalendar
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: window.innerWidth < 768 ? 'listWeek' : 'timeGridWeek',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek'   // 👈 siempre mostrar todos
        },
        locale: 'es',
        buttonText: {
            today: 'Hoy',
            month: 'Mes',
            week: 'Semana',
            day: 'Día',
            list: 'Lista',
        },
        firstDay: 1,
        slotMinTime: '08:30:00',
        slotMaxTime: '21:00:00',
        slotDuration: '01:10:00',
        slotLabelFormat: {
            hour: 'numeric',
            minute: '2-digit',
            hour12: false
        },
        slotLabelInterval: '01:10:00',
        allDaySlot: false,
        expandRows: true,
        editable: false,
        selectable: true,   // 👈 habilitar selección
        select: onSelect,   // 👈 función de selección
        eventClick: onEventClick,
        events: {
            url: calendarEl.dataset.url,
            extraParams: () => ({
                magister_id: magisterFilter.value || '',
                room_id: roomFilter.value || '',
                cohorte: cohorteFilter.value || '',
                anio: anioFilter.value || '',
                trimestre: trimestreFilter.value || ''
            }),
            failure: (err) => console.error('Error cargando eventos:', err)
        },
        eventDidMount: setTooltip,

        viewDidMount: function (info) {
            const className = 'week-view-active';
            const body = document.body;

            if (info.view.type === 'timeGridWeek') {
                body.classList.add(className);
            } else {
                body.classList.remove(className);
            }

            if (info.view.type.startsWith('list')) {
                calendar.setOption('height', 'auto');
                calendar.setOption('contentHeight', 'auto');
            } else {
                calendar.setOption('height', window.innerHeight - 150);
                calendar.setOption('contentHeight', null);
            }
        },
        windowResize: function () {
            if (window.innerWidth < 768) {
                calendar.changeView('listWeek'); // móvil
            } else {
                calendar.changeView('timeGridWeek'); // desktop
            }
        },
        datesSet: (info) => {
            const start = new Date(info.start);
            const diaSemana = start.getDay();
            const offset = (6 - diaSemana + 7) % 7;
            const sabado = new Date(start);
            sabado.setDate(start.getDate() + offset);
            actualizarTextoTrimestre(sabado);
            
        }
    });

    calendar.render();


    // Aviso si no hay magíster seleccionado
    if (!magisterFilter?.value) {
        Swal.fire({
            title: 'Filtra por magíster',
            text: 'Para una mejor visualización, selecciona un magíster desde el filtro superior.',
            icon: 'info',
            confirmButtonText: 'Entendido',
            timer: 6000,
            timerProgressBar: true
        });
    }

    // Cerrar con botón "Cancelar"
    const cancelBtn = document.getElementById('cancel');
    if (cancelBtn) {
        cancelBtn.addEventListener('click', () => {
            calendar.unselect();
            window.closeModal();
        });
    }

    // Cerrar haciendo clic fuera (overlay)
    ['modal', 'eventModal'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('click', (e) => {
                if (e.target === el) {
                    calendar.unselect();
                    window.closeModal();
                }
            });
        }
    });

    // Cerrar con Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            calendar.unselect();
            window.closeModal();
        }
    });


    // Filtros → recargar
    [magisterFilter, roomFilter, cohorteFilter].filter(Boolean).forEach(select => {
        select.addEventListener('change', () => {
            // Validar cohorte antes de recargar
            if (select === cohorteFilter) {
                validarciclo(select.value);
            }
            calendar.refetchEvents();
        });
    });

    // Función para validar cohorte seleccionada
    function validarCohorte(cohorteSeleccionada) {
        const statusElement = document.getElementById('cohorte-status');
        const selectElement = document.getElementById('cohorte-filter');
        
        if (!cicloSeleccionado) {
            // cohorte no seleccionada - error
            selectElement.classList.add('border-red-500');
            selectElement.classList.remove('border-[#84b6f4]');
            
            statusElement.innerHTML = `
                <span class="inline-flex items-center gap-1 px-2 py-1 bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200 rounded text-xs">
                    ❌ Selecciona
                </span>
            `;
            return false;
        }
        
        // Validación exitosa
        selectElement.classList.remove('border-red-500');
        selectElement.classList.add('border-[#84b6f4]');
        
        // Determinar si es cohorte actual o pasada
        const ciclos = Array.from(document.querySelectorAll('#cohorte-filter option')).map(opt => opt.value);
        const esActual = cicloSeleccionado === ciclos[0];
        
        if (esActual) {
            statusElement.innerHTML = `
            `;
        } else {
            statusElement.innerHTML = `
                <span class="inline-flex items-center gap-1 px-2 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-200 rounded text-xs">
                    ⚠️ Pasado
                </span>
            `;
        }
        
        return true;
    }

    // Validar cohorte al cargar la página
    if (cohorteFilter) {
        validarciclo(cohorteFilter.value);
    }

    // Filtro de año → actualizar trimestres disponibles
    if (anioFilter) {
        anioFilter.addEventListener('change', () => {
            actualizarTrimestresDisponibles();
            // Si ambos filtros tienen valor, navegar al trimestre
            if (anioFilter.value && trimestreFilter.value) {
                navegarAlTrimestre(parseInt(anioFilter.value), parseInt(trimestreFilter.value));
            }
            calendar.refetchEvents();
        });
    }

    // Filtro de trimestre → navegar al trimestre
    if (trimestreFilter) {
        trimestreFilter.addEventListener('change', () => {
            // Si ambos filtros tienen valor, navegar al trimestre
            if (anioFilter.value && trimestreFilter.value) {
                navegarAlTrimestre(parseInt(anioFilter.value), parseInt(trimestreFilter.value));
            }
            calendar.refetchEvents();
        });
    }

    // Botón Limpiar Filtros (no limpia cohorte)
    const clearFiltersBtn = document.getElementById('clear-filters');
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', () => {
            // Limpiar solo magister, sala, año y trimestre (NO cohorte)
            if (magisterFilter) magisterFilter.value = '';
            if (roomFilter) roomFilter.value = '';
            if (anioFilter) anioFilter.value = '';
            if (trimestreFilter) {
                trimestreFilter.innerHTML = '<option value="">Todos</option>';
                for (let i = 1; i <= 6; i++) {
                    const option = document.createElement('option');
                    option.value = i;
                    option.textContent = `Trimestre ${i}`;
                    trimestreFilter.appendChild(option);
                }
            }
            // Refrescar el calendario
            calendar.refetchEvents();
        });
    }

    function actualizarTrimestresDisponibles() {
        if (!trimestreFilter || !anioFilter) return;
        
        const anioSeleccionado = parseInt(anioFilter.value);
        const trimestreActual = trimestreFilter.value;
        
        // Limpiar opciones actuales
        trimestreFilter.innerHTML = '<option value="">Todos</option>';
        
        // Agregar trimestres según el año
        if (anioSeleccionado === 1) {
            // Año 1: Trimestres 1, 2, 3
            for (let i = 1; i <= 3; i++) {
                const option = document.createElement('option');
                option.value = i;
                option.textContent = `Trimestre ${i}`;
                if (i.toString() === trimestreActual) {
                    option.selected = true;
                }
                trimestreFilter.appendChild(option);
            }
        } else if (anioSeleccionado === 2) {
            // Año 2: Trimestres 4, 5, 6
            for (let i = 4; i <= 6; i++) {
                const option = document.createElement('option');
                option.value = i;
                option.textContent = `Trimestre ${i}`;
                if (i.toString() === trimestreActual) {
                    option.selected = true;
                }
                trimestreFilter.appendChild(option);
            }
        } else {
            // Si no hay año seleccionado, mostrar todos los trimestres
            for (let i = 1; i <= 6; i++) {
                const option = document.createElement('option');
                option.value = i;
                option.textContent = `Trimestre ${i}`;
                trimestreFilter.appendChild(option);
            }
        }
    }

    async function navegarAlTrimestre(anio, trimestre) {
        try {
            // Obtener la fecha de inicio del trimestre
            const res = await fetch(`/api/periodo-fecha-inicio?anio=${anio}&trimestre=${trimestre}&cohorte=${cohorteFilter.value}`);
            const data = await res.json();
            
            if (data.fecha_inicio) {
                const fechaInicio = new Date(data.fecha_inicio);
                calendar.changeView('timeGridWeek', fechaInicio);
                await actualizarTextoTrimestre(fechaInicio);
            }
        } catch (error) {
            console.error("Error navegando al trimestre:", error);
            Swal.fire({ icon: 'error', title: 'Error de navegación', text: 'No se pudo navegar al trimestre seleccionado.' });
        }
    }

    async function actualizarTextoTrimestre(fecha) {
        // Si hay filtros de año y trimestre seleccionados, usar esos valores directamente
        if (anioFilter && trimestreFilter && anioFilter.value && trimestreFilter.value) {
            const romanos = { 1: 'I', 2: 'II', 3: 'III', 4: 'IV', 5: 'V', 6: 'VI' };
            const texto = `Trimestre ${romanos[trimestreFilter.value] || trimestreFilter.value} del año ${anioFilter.value}`;
            document.getElementById('current-period-text').textContent = texto;
            return;
        }

        // Si no hay filtros específicos, buscar por fecha y cohorte
        const fechaISO = fecha.toISOString().split('T')[0];
        const cohorte = cohorteFilter ? cohorteFilter.value : '';
        
        try {
            const url = `/api/periodo-por-fecha?fecha=${fechaISO}${cohorte ? `&cohorte=${cohorte}` : ''}`;
            const res = await fetch(url);
            const data = await res.json();

            const romanos = { 1: 'I', 2: 'II', 3: 'III', 4: 'IV', 5: 'V', 6: 'VI' };
            const texto = data.periodo
                ? `Trimestre ${romanos[data.periodo.numero] || data.periodo.numero} del año ${data.periodo.anio}`
                : 'Fuera de períodos académicos';

            document.getElementById('current-period-text').textContent = texto;
        } catch (err) {
            console.error('Error cargando período:', err);
            document.getElementById('current-period-text').textContent = 'Error';
        }
    }

    // Crear (select en calendario)
    function onSelect(info) {
        resetForm();

        const magSel = document.getElementById('magister_id');
        const roomSel = document.getElementById('room_id');
        if (magSel && magisterFilter?.value) magSel.value = magisterFilter.value;
        if (roomSel && roomFilter?.value) roomSel.value = roomFilter.value;

        document.getElementById('modal-header').textContent = 'Crear Evento';
        document.getElementById('start_time').value = formatForInput(info.start);
        document.getElementById('end_time').value = formatForInput(info.end);

        document.getElementById('modal').classList.remove('hidden');
        calendar.unselect();
    }



    // Ver (click en evento)
    function onEventClick(info) {
        const body = document.getElementById('event-modal-body');
        body.innerHTML = renderEventDetails(info.event);

        const deleteBtn = document.getElementById('delete-btn');
        const editBtn = document.getElementById('edit-btn');

        if (info.event.extendedProps.type === 'manual') {
            deleteBtn.classList.remove('hidden');
            deleteBtn.setAttribute('data-id', info.event.id.replace('event-', ''));
            editBtn.classList.remove('hidden');
            editBtn.onclick = () => openEditModal(info.event);
        } else {
            deleteBtn.classList.add('hidden');
            editBtn.classList.add('hidden');
        }

        document.getElementById('eventModal').classList.remove('hidden');
    }

    // Editar (abre modal de formulario con datos)
    function openEditModal(event) {
        resetForm();

        const formatForInput = (date) => {
            const local = new Date(date);
            const offset = local.getTimezoneOffset() * 60000;
            return new Date(local - offset).toISOString().slice(0, 16);
        };

        document.getElementById('event_id').value = event.id.replace('event-', '');
        document.getElementById('modal-title-input').value = event.title;
        document.getElementById('modal-description-input').value = event.extendedProps.description || '';
        document.getElementById('magister_id').value = event.extendedProps.magister?.id ?? '';
        document.getElementById('room_id').value = event.extendedProps.room?.id || '';
        document.getElementById('start_time').value = formatForInput(event.start);
        document.getElementById('end_time').value = formatForInput(event.end);
        document.getElementById('modal-header').textContent = 'Editar Evento';

        document.getElementById('eventModal').classList.add('hidden');
        document.getElementById('modal').classList.remove('hidden');
    }

    // Guardar
    document.getElementById('event-form').addEventListener('submit', saveEvent);
    function saveEvent(e) {
        e.preventDefault();

        const eventId = (document.getElementById('event_id').value || '').trim();
        const data = {
            title: document.getElementById('modal-title-input').value,
            description: document.getElementById('modal-description-input').value,
            magister_id: document.getElementById('magister_id').value || null,
            start_time: document.getElementById('start_time').value,
            end_time: document.getElementById('end_time').value,
            room_id: document.getElementById('room_id').value || null,
            type: 'manual'
        };

        const endpoint = eventId ? `/events/${eventId}` : storeUrl;
        const method = eventId ? 'PUT' : 'POST';

        if (!endpoint) {
            console.error('Falta meta[name="store-url"] o endpoint vacío');
            Swal.fire({ icon: 'error', title: 'Configuración faltante', text: 'No se encontró la URL para guardar eventos.' });
            return;
        }

        fetch(endpoint, {
            method,
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
            body: JSON.stringify(data)
        })
            .then(async (res) => {
                if (!res.ok) {
                    let errJson = null;
                    try { errJson = await res.json(); } catch { }
                    throw errJson || { message: 'Error al guardar' };
                }
                return res.json();
            })
            .then(() => {
                calendar.unselect();
                calendar.refetchEvents();
                window.closeModal();
                Swal.fire({ icon: 'success', title: 'Evento guardado', timer: 1500, showConfirmButton: false });
            })
            .catch((err) => {
                console.error('Save error:', err);
                Swal.fire({ icon: 'error', title: 'Error al guardar el evento', text: (err && err.message) ? err.message : 'Revisa los datos ingresados' });
            });
    }

    // Eliminar
    document.getElementById('delete-btn').addEventListener('click', deleteEvent);
    function deleteEvent() {
        const id = this.getAttribute('data-id');
        if (!confirm("¿Estás seguro de eliminar este evento?")) return;

        fetch(`/events/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrf } })
            .then(res => {
                if (res.ok) {
                    calendar.unselect();
                    calendar.refetchEvents();
                    window.closeModal();
                    Swal.fire({ icon: 'success', title: 'Evento Eliminado', timer: 1500, showConfirmButton: false });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error al eliminar el evento', text: 'Intenta nuevamente' });
                }
            });
    }

    // Tooltip y estilos para eventos pasados y manuales
    function setTooltip(info) {
        const ext = info.event.extendedProps || {};
        const programa =
            ext.programa ||
            (typeof ext.magister === 'string' ? ext.magister : (ext.magister?.name || 'Sin programa'));
        const teacher = ext.profesor || ext.teacher || 'Sin encargado';
        const sala = ext.room?.name || 'Sin sala';
        const start = fmtTime(info.event.start);
        const end = fmtTime(info.event.end);
        
        // Agregar indicador de evento manual al tooltip
        const tipoEvento = ext.type === 'manual' ? '🚩 Evento Manual' : 'Clase';
        const tooltip = `${info.event.title}\n📌 ${tipoEvento}\n👨‍🏫 ${teacher}\n🏛️ ${programa}\n🏫 ${sala}\n🕒 ${start} - ${end}`;
        info.el.setAttribute('title', tooltip.trim());

        // Marcar eventos manuales con clase especial
        if (ext.type === 'manual') {
            info.el.classList.add('evento-manual');
        }

        // Marcar eventos pasados con clase especial
        const ahora = new Date();
        const eventoFin = info.event.end || info.event.start;
        if (eventoFin < ahora) {
            info.el.classList.add('evento-pasado');
        }
    }

    // Cerrar modales global
    window.closeModal = function () {
        document.getElementById('modal').classList.add('hidden');
        document.getElementById('eventModal').classList.add('hidden');
        resetForm();
    };

    function resetForm() {
        document.getElementById('event_id').value = '';
        document.getElementById('event-form').reset();
        document.getElementById('start_time').value = '';
        document.getElementById('end_time').value = '';
    }
});







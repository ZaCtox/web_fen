document.addEventListener('DOMContentLoaded', function () {
  const calendarEl = document.getElementById('calendar');
  const magisterFilter = document.getElementById('magister-filter');
  const roomFilter = document.getElementById('room-filter');
  const anioIngresoFilter = document.getElementById('anio-ingreso-filter');
  const anioFilter = document.getElementById('anio-filter');
  const trimestreFilter = document.getElementById('trimestre-filter');

  // Helpers UI
  const fmtTime = (dt) =>
    new Date(dt).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: false });

  const modalityBadge = (m) => {
    const map = {
      presencial: 'bg-green-100 text-green-800',
      online: 'bg-indigo-100 text-indigo-800',
      hibrida: 'bg-yellow-100 text-yellow-800',
    };
    const cls = map[m] || 'bg-gray-100 text-gray-800';
    const label = m || '—';
    return `<span class="inline-flex items-center rounded px-2 py-0.5 text-xs font-medium ${cls}">${label}</span>`;
  };

  // Lupa: base para /clases/{id}
  const showBase = document.querySelector('meta[name="clases-show-base"]')?.content || null;
  // ids de clases: "clase-<ID>-sesion-<ID>" o "bloque-<sesionID>-<index>" o "clase-<ID>-YYYYMMDD" (legacy)
  const getClaseIdFromEventId = (id) => {
    // Nuevo formato: "clase-123-sesion-456"
    const m1 = String(id).match(/^clase-(\d+)-/);
    if (m1) return m1[1];
    
    // Formato de bloques: "bloque-456-0" - necesitamos buscar la clase desde la sesión
    // Por ahora retornamos null y confiamos en que el evento tenga clase_id en extendedProps
    return null;
  };
  
  const getClaseIdFromEvent = (event) => {
    // Intentar desde el ID del evento
    const fromId = getClaseIdFromEventId(event.id);
    if (fromId) return fromId;
    
    // Si no, usar clase_id de extendedProps
    return event.extendedProps?.clase_id || null;
  };

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
    slotMinTime: '08:00:00',
    slotMaxTime: '22:00:00',
    slotDuration: '00:30:00',
    slotLabelFormat: {
      hour: 'numeric',
      minute: '2-digit',
      hour12: false
    },
    slotLabelInterval: '01:00:00',
    allDaySlot: false,
    expandRows: true,
    editable: false,
    selectable: false,
    eventClick: onEventClick,
        events: {
          url: calendarEl.dataset.url,
          extraParams: () => ({
            magister_id: magisterFilter.value || '',
            room_id: roomFilter.value || '',
            anio_ingreso: anioIngresoFilter.value || '',
            anio: anioFilter.value || '',
            trimestre: trimestreFilter.value || ''
          }),
          failure: (err) => console.error('Error cargando eventos:', err)
        },
    eventDidMount: setTooltip,

    viewDidMount: function (info) {
      const className = 'week-view-active';
      const body = document.body; // puedes cambiar a otro contenedor si prefieres

      if (info.view.type === 'timeGridWeek') {
        body.classList.add(className);
      } else {
        body.classList.remove(className);
      }

      if (info.view.type.startsWith('list')) {
        calendar.setOption('height', 'auto');
        calendar.setOption('contentHeight', 'auto');
      } else {
        calendar.setOption('height', window.innerHeight + 200);
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


  if (!magisterFilter.value) {
    Swal.fire({
      title: 'Filtra por magíster',
      text: 'Para una mejor visualización, selecciona un magíster desde el filtro superior.',
      icon: 'info',
      confirmButtonText: 'Entendido',
      timer: 6000,
      timerProgressBar: true
    });
  }


  // Filtros → recargar
  [magisterFilter, roomFilter, anioIngresoFilter].forEach(select => {
    select.addEventListener('change', () => {
      calendar.refetchEvents();
    });
  });

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
  // Botón Limpiar Filtros (no limpia año de ingreso)
  const clearBtn = document.getElementById('clear-filters');
  if (clearBtn) {
    clearBtn.addEventListener('click', () => {
      // Limpiar solo magister, sala, año y trimestre (NO año de ingreso)
      magisterFilter.value = '';
      roomFilter.value = '';
      anioFilter.value = '';
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
      const res = await fetch(`/api/periodo-fecha-inicio?anio=${anio}&trimestre=${trimestre}&anio_ingreso=${anioIngresoFilter.value}`);
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

    // Si no hay filtros específicos, buscar por fecha y año de ingreso
    const fechaISO = fecha.toISOString().split('T')[0];
    const anioIngreso = anioIngresoFilter ? anioIngresoFilter.value : '';
    
    try {
      const url = `/api/periodo-por-fecha?fecha=${fechaISO}${anioIngreso ? `&anio_ingreso=${anioIngreso}` : ''}`;
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

  // Modal público
  function onEventClick(info) {
    const ext = info.event.extendedProps || {};
    const isBreak = ext.type === 'break';

    // Título siempre
    document.getElementById('modal-title').textContent = info.event.title;

    // Si es un BREAK, mostrar información simplificada
    if (isBreak) {
      const duracion = Math.round((info.event.end - info.event.start) / 60000); // minutos
      
      // Descripción del break
      const descEl = document.getElementById('modal-description');
      if (descEl) descEl.textContent = ext.description || '';
      
      // Ocultar campos de clase (programa, modalidad, profesor)
      const programEl = document.getElementById('modal-program');
      if (programEl && programEl.parentElement) programEl.parentElement.style.display = 'none';
      
      const magisterEl = document.getElementById('modal-magister-view');
      if (magisterEl && magisterEl.parentElement) magisterEl.parentElement.style.display = 'none';
      
      const modEl = document.getElementById('modal-modality');
      if (modEl && modEl.parentElement) modEl.parentElement.style.display = 'none';
      
      const profEl = document.getElementById('modal-teacher');
      if (profEl && profEl.parentElement) profEl.parentElement.style.display = 'none';
      
      // Mostrar solo: hora inicio, hora fin, sala
      document.getElementById('modal-start').textContent = fmtTime(info.event.start);
      document.getElementById('modal-end').textContent = fmtTime(info.event.end);
      document.getElementById('modal-room').textContent = ext.room?.name || '—';
      
      // Ocultar grabación y lupa para breaks
      const grabacionContainer = document.getElementById('modal-grabacion-container');
      if (grabacionContainer) grabacionContainer.classList.add('hidden');
      
      const viewLink = document.getElementById('view-class-link');
      if (viewLink) viewLink.classList.add('hidden');
      
    } else {
      // Si es una CLASE o EVENTO MANUAL, mostrar toda la información normal
      
      // MOSTRAR todos los campos que ocultamos para breaks
      const programEl = document.getElementById('modal-program');
      if (programEl && programEl.parentElement) programEl.parentElement.style.display = '';
      
      const magisterEl = document.getElementById('modal-magister-view');
      if (magisterEl && magisterEl.parentElement) magisterEl.parentElement.style.display = '';
      
      const modEl = document.getElementById('modal-modality');
      if (modEl && modEl.parentElement) modEl.parentElement.style.display = '';
      
      const profEl = document.getElementById('modal-teacher');
      if (profEl && profEl.parentElement) profEl.parentElement.style.display = '';

      // Descripción (si la usas)
      const descEl = document.getElementById('modal-description');
      if (descEl) descEl.textContent = ext.description || '';

      // Programa = nombre del magíster (puede venir como string u objeto)
      const programaName =
        ext.programa ||
        (typeof ext.magister === 'string' ? ext.magister : (ext.magister?.name || 'No especificado'));

      // Rellenar "Programa"
      if (programEl) programEl.textContent = `Magíster en ${programaName}`;

      // Si aún existe el span "modal-magister-view", también le ponemos el programa
      if (magisterEl) magisterEl.textContent = `Magíster en ${programaName}`;

      // Modalidad (badge)
      if (modEl) modEl.innerHTML = modalityBadge(ext.modality);

      // Encargado
      if (profEl) profEl.textContent = ext.profesor || '—';

      // Horas solo HH:mm
      document.getElementById('modal-start').textContent = fmtTime(info.event.start);
      document.getElementById('modal-end').textContent = fmtTime(info.event.end);

      // Sala
      document.getElementById('modal-room').textContent = ext.room?.name || '—';

      // Grabación de YouTube - MOSTRAR si existe
      const grabacionContainer = document.getElementById('modal-grabacion-container');
      const grabacionLink = document.getElementById('modal-grabacion-link');
      if (grabacionContainer && grabacionLink) {
        if (ext.url_grabacion) {
          grabacionLink.href = ext.url_grabacion;
          grabacionContainer.classList.remove('hidden');
        } else {
          grabacionContainer.classList.add('hidden');
          grabacionLink.removeAttribute('href');
        }
      }

      // Lupa: solo para clases - MOSTRAR
      const viewLink = document.getElementById('view-class-link');
      if (viewLink) {
        // Usar la función helper que obtiene clase_id del ID o de extendedProps
        const claseId = getClaseIdFromEvent(info.event);
        if (showBase && claseId && (ext.type === 'clase')) {
          viewLink.href = `${showBase}/${claseId}`;
          viewLink.classList.remove('hidden');
        } else {
          viewLink.classList.add('hidden');
          viewLink.removeAttribute('href');
        }
      }
    }

    document.getElementById('eventModal').classList.remove('hidden');
  }

  function setTooltip(info) {
    // Para el tooltip usamos "Programa" también
    const ext = info.event.extendedProps || {};
    const programa =
      ext.programa ||
      (typeof ext.magister === 'string' ? ext.magister : (ext.magister?.name || 'Sin programa'));
    const teacher = ext.profesor || 'Sin encargado';
    const sala = ext.room?.name || 'Sin sala';
    const start = fmtTime(info.event.start);
    const end = fmtTime(info.event.end);
    
    // Agregar indicador de evento manual al tooltip
    const tipoEvento = ext.type === 'manual' ? '🚩 Evento Especial' : 'Clase';
    const tooltip = `${info.event.title}\n📌 ${tipoEvento}\n👨‍🏫 ${teacher}\n🏛️ Magíster en ${programa}\n🏫 ${sala}\n🕒 ${start} - ${end}`;
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

  // Cerrar modal
  window.closeModal = function () {
    document.getElementById('eventModal').classList.add('hidden');
  };
  // Cerrar al hacer click en overlay
  document.getElementById('eventModal').addEventListener('click', (e) => {
    if (e.target.id === 'eventModal') window.closeModal();
  });
  // Cerrar con Escape
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') window.closeModal();
  });
});









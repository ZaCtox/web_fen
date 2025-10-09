document.addEventListener('DOMContentLoaded', function () {
  const calendarEl = document.getElementById('calendar');
  const magisterFilter = document.getElementById('magister-filter');
  const roomFilter = document.getElementById('room-filter');

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
  // ids de clases: "clase-<ID>-YYYYMMDD"
  const getClaseIdFromEventId = (id) => {
    const m = String(id).match(/^clase-(\d+)-/);
    return m ? m[1] : null;
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
    slotMinTime: '08:30:00',
    slotMaxTime: '21:00:00',
    slotDuration: '01:10:00',
    allDaySlot: false,
    expandRows: true,
    editable: false,
    selectable: false,
    eventClick: onEventClick,
    events: {
      url: calendarEl.dataset.url,
      extraParams: () => ({
        magister_id: magisterFilter.value || '',
        room_id: roomFilter.value || ''
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

  // Navegación por trimestres
  document.getElementById('btnAnterior').addEventListener('click', () => irATrimestre('anterior'));
  document.getElementById('btnSiguiente').addEventListener('click', () => irATrimestre('siguiente'));

  async function irATrimestre(direccion) {
    const fechaActual = calendar.getDate().toISOString().split('T')[0];
    try {
      const res = await fetch(`/api/trimestre-${direccion}?fecha=${fechaActual}`);
      if (res.status === 404) {
        const fallbackRes = await fetch(`/api/trimestres-todos`);
        const todos = await fallbackRes.json();
        if (!todos.length) {
          Swal.fire({ icon: 'warning', title: 'Sin trimestres registrados', text: 'No hay ningún período académico cargado en el sistema.' });
          return;
        }
        const fechas = todos.map(p => new Date(p.fecha_inicio));
        const fechaRef = new Date(fechaActual);
        let cercana = null;
        if (direccion === 'anterior') {
          cercana = fechas.filter(f => f < fechaRef).sort((a, b) => b - a)[0];
        } else {
          cercana = fechas.filter(f => f > fechaRef).sort((a, b) => a - b)[0];
        }
        if (cercana) {
          calendar.gotoDate(cercana.toISOString().split('T')[0]);
        } else {
          Swal.fire({ icon: 'info', title: 'Trimestre no disponible', text: direccion === 'anterior' ? 'Ya estás en el trimestre más antiguo registrado.' : 'Ya estás en el trimestre más reciente registrado.' });
        }
        return;
      }
      const data = await res.json();
      if (data.fecha_inicio) calendar.gotoDate(data.fecha_inicio);
    } catch (error) {
      console.error('Error consultando trimestre:', error);
      Swal.fire({ icon: 'error', title: 'Error de conexión', text: 'No se pudo consultar los trimestres. Intenta nuevamente.' });
    }
  }

  // Filtros → recargar
  [magisterFilter, roomFilter].forEach(select => {
    select.addEventListener('change', () => calendar.refetchEvents());
  });
  // Botón para limpiar filtros
  const clearBtn = document.getElementById('clear-filters');
  if (clearBtn) {
    clearBtn.addEventListener('click', () => {
      magisterFilter.value = '';
      roomFilter.value = '';
      calendar.refetchEvents();
    });
  }


  async function actualizarTextoTrimestre(fecha) {
    const fechaISO = fecha.toISOString().split('T')[0];
    try {
      const res = await fetch(`/api/periodo-por-fecha?fecha=${fechaISO}`);
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

    // Título
    document.getElementById('modal-title').textContent = info.event.title;

    // Descripción (si la usas)
    const descEl = document.getElementById('modal-description');
    if (descEl) descEl.textContent = ext.description || '';

    // Programa = nombre del magíster (puede venir como string u objeto)
    const programaName =
      ext.programa ||
      (typeof ext.magister === 'string' ? ext.magister : (ext.magister?.name || 'No especificado'));

    // Rellenar "Programa"
    const programEl = document.getElementById('modal-program');
    if (programEl) programEl.textContent = programaName;

    // Si aún existe el span "modal-magister-view", también le ponemos el programa
    const magisterEl = document.getElementById('modal-magister-view');
    if (magisterEl) magisterEl.textContent = programaName;

    // Modalidad (badge)
    const modEl = document.getElementById('modal-modality');
    if (modEl) modEl.innerHTML = modalityBadge(ext.modality);

    // Profesor
    const profEl = document.getElementById('modal-teacher');
    if (profEl) profEl.textContent = ext.profesor || ext.teacher || '—';

    // Horas solo HH:mm
    document.getElementById('modal-start').textContent = fmtTime(info.event.start);
    document.getElementById('modal-end').textContent = fmtTime(info.event.end);

    // Sala
    document.getElementById('modal-room').textContent = ext.room?.name || '—';

    // Grabación de YouTube
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

    // Lupa: solo para clases
    const viewLink = document.getElementById('view-class-link');
    if (viewLink) {
      const claseId = getClaseIdFromEventId(info.event.id);
      if (showBase && claseId && (ext.type === 'clase')) {
        viewLink.href = `${showBase}/${claseId}`;
        viewLink.classList.remove('hidden');
      } else {
        viewLink.classList.add('hidden');
        viewLink.removeAttribute('href');
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
    const teacher = ext.profesor || ext.teacher || 'Sin encargado';
    const sala = ext.room?.name || 'Sin sala';
    const start = fmtTime(info.event.start);
    const end = fmtTime(info.event.end);
    
    // Agregar indicador de evento manual al tooltip
    const tipoEvento = ext.type === 'manual' ? '🚩 Evento Especial' : 'Clase';
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

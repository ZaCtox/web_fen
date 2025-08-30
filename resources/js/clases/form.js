// resources/js/clases/form-clase.js
(() => {
    'use strict';

    const qs = (s, r = document) => r.querySelector(s);
    const qsa = (s, r = document) => Array.from(r.querySelectorAll(s));

    function addMinutes(hhmm, mins) {
        const [H, M] = (hhmm || '00:00').split(':').map(Number);
        const d = new Date(2000, 1, 1, H, M, 0);
        d.setMinutes(d.getMinutes() + mins);
        const pad = n => (n < 10 ? '0' + n : '' + n);
        return `${pad(d.getHours())}:${pad(d.getMinutes())}`;
    }

    function toParams(obj) {
        return Object.entries(obj)
            .filter(([_, v]) => v !== undefined && v !== null && v !== '')
            .map(([k, v]) => `${encodeURIComponent(k)}=${encodeURIComponent(v)}`)
            .join('&');
    }

    // -----------------------------
    // 1) Magíster → Asignatura → Período
    // -----------------------------
    function parseAgrupados() {
        const magSel = qs('#magister');
        if (!magSel) return {};
        try {
            return JSON.parse(magSel.dataset.agrupados || '{}');
        } catch (_) {
            return {};
        }
    }

    function wireMagisterCoursePeriod() {
        const magSel = qs('#magister');
        const courseSel = qs('#course_id');
        const periodInput = qs('#period_id');
        const periodoInfo = qs('#periodo_info');
        const trimestre = qs('#trimestre');
        const anio = qs('#anio');

        if (!magSel || !courseSel) return;

        const agrupados = parseAgrupados();

        magSel.addEventListener('change', () => {
            const mag = magSel.value;
            courseSel.innerHTML = '<option value="">-- Asignatura --</option>';
            if (periodInput) periodInput.value = '';
            if (periodoInfo) periodoInfo.innerHTML = '<option>Selecciona un curso para ver el período</option>';
            if (trimestre) trimestre.value = '';
            if (anio) anio.value = '';

            if (agrupados[mag]) {
                agrupados[mag].forEach(c => {
                    const opt = document.createElement('option');
                    opt.value = c.id;
                    opt.textContent = c.nombre;
                    opt.dataset.period_id = c.period_id;
                    opt.dataset.periodo = c.periodo; // nombre_completo
                    opt.dataset.period_numero = c.numero ?? '';
                    opt.dataset.period_anio = c.anio ?? '';
                    courseSel.appendChild(opt);
                });
            }
        });

        courseSel.addEventListener('change', () => {
            const selected = courseSel.options[courseSel.selectedIndex];
            const periodId = selected?.dataset?.period_id ?? '';
            const periodo = selected?.dataset?.periodo ?? '';
            const numero = selected?.dataset?.period_numero || '';
            const _anio = selected?.dataset?.period_anio || '';

            if (periodInput) periodInput.value = periodId;
            if (periodoInfo) periodoInfo.innerHTML = `<option>${periodo ? '📘 ' + periodo : 'Sin periodo asignado'}</option>`;
            if (trimestre) trimestre.value = numero;
            if (anio) anio.value = _anio;
        });

        // Hidratación inicial (EDIT)
        (function hydrateInitial() {
            const sel = courseSel.options[courseSel.selectedIndex];
            if (!sel) return;
            const numero = sel.dataset.period_numero || '';
            const _anio = sel.dataset.period_anio || '';
            if (trimestre) trimestre.value = numero;
            if (anio) anio.value = _anio;
        })();
    }

    // -----------------------------
    // 2) Modalidad online => deshabilitar sala
    // -----------------------------
    function wireModalityRoomDisable() {
        const modality = qs('#modality');
        const roomSel = qs('#room_id');
        if (!modality || !roomSel) return;

        const apply = () => {
            // Sala: limpia y deshabilita si es online
            if (modality.value === 'online') {
                roomSel.value = '';
                roomSel.disabled = true;
            } else {
                roomSel.disabled = false;
            }

            // Horarios: limpia UI cuando pasa a online
            const slotsWrap = qs('#slots-wrap');
            const slotsDiv = qs('#slots');
            if (modality.value === 'online') {
                slotsDiv && (slotsDiv.innerHTML = '');
                slotsWrap && slotsWrap.classList.add('hidden');
            }
            // No hace falta mostrar/ocultar aquí: Alpine lo hace con x-show
        };

        apply();
        modality.addEventListener('change', apply);
    }


    // -----------------------------
    // 3) Alpine data: disponibilidad sala (Create/Edit)
    //    Usa URL desde el form: data-url-disponibilidad="..."
    // -----------------------------
    window.disponibilidadSala = function () {
        const form = qs('#form-clase');
        const DISP_URL = form?.dataset.urlDisponibilidad || '';

        return {
            checking: false,
            available: null,
            conflicts: [],
            timer: null,

            payload(extra = {}) {
                return {
                    room_id: qs('#room_id')?.value || '',
                    period_id: qs('#period_id')?.value || '',
                    dia: qs('select[name="dia"]')?.value || '',
                    hora_inicio: qs('input[name="hora_inicio"]')?.value || '',
                    hora_fin: qs('input[name="hora_fin"]')?.value || '',
                    modality: qs('#modality')?.value || '',
                    ...extra
                };
            },

            debounceCheck(extra = {}) {
                clearTimeout(this.timer);
                this.timer = setTimeout(() => this.check(extra), 400);
            },

            async check(extra = {}) {
                const p = this.payload(extra);

                // online o faltan datos -> libre
                if (p.modality === 'online' || !p.period_id || !p.dia || !p.hora_inicio || !p.hora_fin) {
                    this.available = true;
                    this.conflicts = [];
                    return;
                }

                // sin sala -> libre
                if (!p.room_id) {
                    this.available = true;
                    this.conflicts = [];
                    return;
                }

                this.checking = true;
                this.available = null;
                this.conflicts = [];

                const params = toParams(p);
                try {
                    const res = await fetch(`${DISP_URL}?${params}`, { headers: { 'Accept': 'application/json' } });
                    const json = await res.json();
                    this.available = !!json.available;
                    this.conflicts = json.conflicts || [];
                } catch (e) {
                    // no bloqueamos si falla
                    this.available = true;
                    this.conflicts = [];
                    console.error('Error disponibilidad:', e);
                } finally {
                    this.checking = false;
                    // opcional: deshabilitar submit si hay conflicto
                    qs('button[type="submit"]')?.toggleAttribute('disabled', this.available === false);
                }
            },

            initCreate() {
                const listen = (sel, evt = 'change') => {
                    const el = qs(sel);
                    if (el) el.addEventListener(evt, () => this.debounceCheck());
                };

                listen('#room_id');
                listen('#period_id');
                listen('select[name="dia"]');
                listen('input[name="hora_inicio"]', 'input');
                listen('input[name="hora_fin"]', 'input');
                listen('#modality');

                const course = qs('#course_id');
                course?.addEventListener('change', () => this.debounceCheck());

                this.debounceCheck();
            },
        };
    };

    window.disponibilidadSalaEdit = function (id) {
        const base = window.disponibilidadSala();
        const origPayload = base.payload.bind(base);
        base.payload = function (extra = {}) {
            return origPayload({ exclude_id: id, ...extra });
        };
        base.initEdit = function () {
            this.initCreate();
        };
        return base;
    };

    // -----------------------------
    // 4) Botón "Ver horarios disponibles"
    //    Usa:
    //      - #btn-horarios (data-url-horarios, data-exclude-id?)
    //      - #block_hours, #buffer_mins (opcionales)
    //      - #slots-wrap, #slots
    // -----------------------------
    function updateHelpText() {
        const help = qs('#help-huecos');
        if (!help) return;
        const hrs = parseInt(qs('#block_hours')?.value || '1', 10);
        const buf = parseInt(qs('#buffer_mins')?.value || '10', 10);
        help.textContent = `Busca huecos libres de ${hrs} ${hrs === 1 ? 'hora' : 'horas'} con ${buf} minutos de holgura antes y después.`;
    }

    function wireHorarios() {
        const btn = qs('#btn-horarios');
        if (!btn) return;

        const slotsWrap = qs('#slots-wrap');
        const slotsDiv = qs('#slots');

        const periodInp = qs('#period_id');
        const roomSel = qs('#room_id');
        const diaSel = qs('select[name="dia"]');
        const modality = qs('#modality');

        const blockHours = qs('#block_hours');   // opcional
        const bufferInp = qs('#buffer_mins');   // opcional

        const HOR_URL = btn.dataset.urlHorarios || '';
        const excludeId = btn.dataset.excludeId ? parseInt(btn.dataset.excludeId, 10) : null;

        // texto de ayuda dinámico
        qsa('#block_hours, #buffer_mins').forEach(el => el.addEventListener('change', updateHelpText));
        qsa('#buffer_mins').forEach(el => el.addEventListener('input', updateHelpText));
        updateHelpText();

        btn.addEventListener('click', async () => {
            const period_id = periodInp?.value;
            const room_id = roomSel?.value;
            const dia = diaSel?.value;
            const mod = modality?.value;

            if (!period_id) { alert('Primero selecciona una asignatura para obtener el período.'); return; }
            if (mod !== 'online' && !room_id) { alert('Selecciona una sala (o cambia a modalidad online).'); return; }
            if (!dia) { alert('Selecciona el día.'); return; }

            const durMin = Math.max(30, (parseInt(blockHours?.value || '1', 10) * 60));
            const buffer = Math.max(0, Math.min(60, parseInt(bufferInp?.value || '10', 10)));

            const params = {
                period_id, room_id, dia,
                modality: mod,
                exclude_id: excludeId ?? '',
                desde: '08:00',
                hasta: '22:00',
                min_block: durMin,
                buffer: buffer
            };

            try {
                const url = `${HOR_URL}?${toParams(params)}`;
                const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                const json = await res.json();

                slotsDiv.innerHTML = '';
                slotsWrap.classList.remove('hidden');

                if (json.slots && json.slots.length) {
                    json.slots.forEach(s => {
                        // Proponer alternativas cada 15 min dentro del slot
                        let cursor = s.start;
                        let added = 0;

                        while (addMinutes(cursor, durMin) <= s.end) {
                            const start = cursor;                     // ⬅️ CAPTURA LOCAL
                            const end = addMinutes(start, durMin);  // ⬅️ USA start, no cursor

                            const chip = document.createElement('button');
                            chip.type = 'button';
                            chip.className = 'px-3 py-1 text-xs rounded bg-green-100 text-green-800 hover:bg-green-200';
                            chip.textContent = `${start}–${end}`;
                            chip.addEventListener('click', () => {
                                const hIni = qs('input[name="hora_inicio"]');
                                const hFin = qs('input[name="hora_fin"]');
                                if (hIni) {
                                    hIni.value = start;
                                    hIni.dispatchEvent(new Event('input', { bubbles: true })); // para tu debounceCheck
                                }
                                if (hFin) {
                                    hFin.value = end;
                                    hFin.dispatchEvent(new Event('input', { bubbles: true }));
                                }
                            });
                            slotsDiv.appendChild(chip);

                            cursor = addMinutes(cursor, 15);
                            added++;
                        }

                        if (!added) {
                            const start = addMinutes(s.end, -durMin); // ⬅️ CAPTURA LOCAL
                            if (start >= s.start) {
                                const end = s.end;
                                const chip = document.createElement('button');
                                chip.type = 'button';
                                chip.className = 'px-3 py-1 text-xs rounded bg-green-100 text-green-800 hover:bg-green-200';
                                chip.textContent = `${start}–${end}`;
                                chip.addEventListener('click', () => {
                                    const hIni = qs('input[name="hora_inicio"]');
                                    const hFin = qs('input[name="hora_fin"]');
                                    if (hIni) {
                                        hIni.value = start;
                                        hIni.dispatchEvent(new Event('input', { bubbles: true }));
                                    }
                                    if (hFin) {
                                        hFin.value = end;
                                        hFin.dispatchEvent(new Event('input', { bubbles: true }));
                                    }
                                });
                                slotsDiv.appendChild(chip);
                            }
                        }
                    });

                    if (!slotsDiv.children.length) {
                        slotsDiv.innerHTML = '<span class="text-sm text-red-600">No hay huecos del tamaño solicitado en los slots disponibles.</span>';
                    }
                } else {
                    slotsDiv.innerHTML = '<span class="text-sm text-red-600">No hay huecos libres con las condiciones actuales.</span>';
                }
            } catch (e) {
                console.error(e);
                alert('No se pudo obtener la disponibilidad.');
            }
        });
    }

    // -----------------------------
    // 5) Bootstrap de todo
    // -----------------------------
    function boot() {
        wireMagisterCoursePeriod();
        wireModalityRoomDisable();
        wireHorarios();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot);
    } else {
        boot();
    }
})();

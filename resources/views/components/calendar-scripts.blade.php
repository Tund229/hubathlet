{{-- Scripts FullCalendar + Tooltip --}}
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.10/locales/fr.global.min.js'></script>

<script>
    // Tooltip element
    let tooltipEl = null;
    
    function createTooltip() {
        if (tooltipEl) return;
        
        tooltipEl = document.createElement('div');
        tooltipEl.className = 'event-tooltip';
        tooltipEl.innerHTML = `
            <div class="event-tooltip-header">
                <div class="text-xs font-bold opacity-80 uppercase tracking-wide" id="tooltip-type"></div>
                <div class="text-lg font-black mt-1" id="tooltip-title"></div>
            </div>
            <div class="event-tooltip-body">
                <div class="event-tooltip-row">
                    <div class="event-tooltip-icon bg-blue-100">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <div class="event-tooltip-label">Date</div>
                        <div class="event-tooltip-value" id="tooltip-date"></div>
                    </div>
                </div>
                <div class="event-tooltip-row">
                    <div class="event-tooltip-icon bg-emerald-100">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="event-tooltip-label">Horaire</div>
                        <div class="event-tooltip-value" id="tooltip-time"></div>
                    </div>
                </div>
                <div class="event-tooltip-row" id="tooltip-location-row" style="display: none;">
                    <div class="event-tooltip-icon bg-violet-100">
                        <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="event-tooltip-label">Lieu</div>
                        <div class="event-tooltip-value" id="tooltip-location"></div>
                    </div>
                </div>
                <div class="event-tooltip-row" id="tooltip-participants-row" style="display: none;">
                    <div class="event-tooltip-icon bg-amber-100">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="event-tooltip-label">Participants</div>
                        <div class="event-tooltip-value" id="tooltip-participants"></div>
                    </div>
                </div>
                <div class="event-tooltip-row" id="tooltip-status-row" style="display: none;">
                    <div class="event-tooltip-icon bg-slate-100">
                        <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="event-tooltip-label">Statut</div>
                        <div class="event-tooltip-value" id="tooltip-status"></div>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(tooltipEl);
    }
    
    function showTooltip(event, jsEvent) {
        if (!tooltipEl) createTooltip();
        
        const props = event.extendedProps;
        const color = event.backgroundColor || '#10b981';
        
        // Header
        tooltipEl.querySelector('.event-tooltip-header').style.backgroundColor = color;
        tooltipEl.querySelector('#tooltip-type').textContent = props.typeLabel || 'Séance';
        tooltipEl.querySelector('#tooltip-title').textContent = event.title;
        
        // Date
        const date = event.start;
        const dateOptions = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
        tooltipEl.querySelector('#tooltip-date').textContent = date.toLocaleDateString('fr-FR', dateOptions);
        
        // Time
        tooltipEl.querySelector('#tooltip-time').textContent = props.time || 
            `${date.toLocaleTimeString('fr-FR', {hour: '2-digit', minute: '2-digit'})}`;
        
        // Location
        const locationRow = tooltipEl.querySelector('#tooltip-location-row');
        if (props.location) {
            locationRow.style.display = 'flex';
            tooltipEl.querySelector('#tooltip-location').textContent = props.location;
        } else {
            locationRow.style.display = 'none';
        }
        
        // Participants
        const participantsRow = tooltipEl.querySelector('#tooltip-participants-row');
        if (props.participantsCount !== undefined) {
            participantsRow.style.display = 'flex';
            tooltipEl.querySelector('#tooltip-participants').textContent = 
                props.participantsCount + ' participant' + (props.participantsCount > 1 ? 's' : '');
        } else {
            participantsRow.style.display = 'none';
        }
        
        // Status
        const statusRow = tooltipEl.querySelector('#tooltip-status-row');
        const statusLabels = {
            'scheduled': 'Programmé',
            'ongoing': 'En cours',
            'completed': 'Terminé',
            'cancelled': 'Annulé'
        };
        if (props.status && statusLabels[props.status]) {
            statusRow.style.display = 'flex';
            tooltipEl.querySelector('#tooltip-status').textContent = statusLabels[props.status];
        } else {
            statusRow.style.display = 'none';
        }
        
        // Position
        const rect = jsEvent.target.getBoundingClientRect();
        const tooltipWidth = 300;
        const tooltipHeight = tooltipEl.offsetHeight || 250;
        
        let left = rect.left + rect.width / 2 - tooltipWidth / 2;
        let top = rect.bottom + 8;
        
        // Adjust if tooltip goes off screen
        if (left < 10) left = 10;
        if (left + tooltipWidth > window.innerWidth - 10) left = window.innerWidth - tooltipWidth - 10;
        if (top + tooltipHeight > window.innerHeight - 10) {
            top = rect.top - tooltipHeight - 8;
        }
        
        tooltipEl.style.left = left + 'px';
        tooltipEl.style.top = top + 'px';
        tooltipEl.classList.add('visible');
    }
    
    function hideTooltip() {
        if (tooltipEl) {
            tooltipEl.classList.remove('visible');
        }
    }
    
    function initCalendarWithTooltips(calendarEl, events, options = {}) {
        if (!calendarEl) return null;
        
        const defaultOptions = {
            initialView: window.innerWidth < 768 ? 'listWeek' : 'dayGridMonth',
            locale: 'fr',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listWeek'
            },
            buttonText: {
                today: "Aujourd'hui",
                month: 'Mois',
                week: 'Semaine',
                list: 'Liste'
            },
            events: events,
            eventClick: function(info) {
                if (info.event.url) {
                    window.location.href = info.event.url;
                }
            },
            eventMouseEnter: function(info) {
                showTooltip(info.event, info.jsEvent);
            },
            eventMouseLeave: function(info) {
                hideTooltip();
            },
            eventContent: function(arg) {
                // Custom event rendering with time
                const time = arg.event.start.toLocaleTimeString('fr-FR', {hour: '2-digit', minute: '2-digit'});
                const title = arg.event.title;
                
                return {
                    html: `
                        <div class="fc-event-main-frame" style="padding: 2px 4px;">
                            <div class="fc-event-time" style="font-size: 0.7rem; opacity: 0.9;">${time}</div>
                            <div class="fc-event-title" style="font-size: 0.75rem; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${title}</div>
                        </div>
                    `
                };
            },
            height: 'auto',
            navLinks: true,
            editable: false,
            dayMaxEvents: 3,
            moreLinkText: function(num) {
                return '+' + num + ' autres';
            },
            noEventsText: 'Aucun événement à afficher',
            eventTimeFormat: {
                hour: '2-digit',
                minute: '2-digit',
                meridiem: false
            }
        };
        
        const calendar = new FullCalendar.Calendar(calendarEl, { ...defaultOptions, ...options });
        calendar.render();
        
        return calendar;
    }
</script>



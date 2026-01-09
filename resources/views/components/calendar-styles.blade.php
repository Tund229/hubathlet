{{-- Styles FullCalendar + Tooltip --}}
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />
<style>
    /* FullCalendar Custom Styles */
    .fc {
        --fc-border-color: #e2e8f0;
        --fc-today-bg-color: #f0fdf4;
        --fc-page-bg-color: transparent;
        font-family: 'Inter', sans-serif;
    }
    .fc .fc-toolbar-title {
        font-size: 1.25rem;
        font-weight: 800;
        color: #0f172a;
    }
    .fc .fc-button {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #475569;
        font-weight: 600;
        padding: 0.5rem 1rem;
        border-radius: 0.75rem;
        text-transform: capitalize;
    }
    .fc .fc-button:hover {
        background-color: #f1f5f9;
        border-color: #cbd5e1;
        color: #1e293b;
    }
    .fc .fc-button-primary:not(:disabled).fc-button-active,
    .fc .fc-button-primary:not(:disabled):active {
        background-color: #10b981;
        border-color: #10b981;
        color: white;
    }
    .fc .fc-daygrid-day-number {
        font-weight: 600;
        color: #64748b;
        padding: 8px;
    }
    .fc .fc-daygrid-day.fc-day-today .fc-daygrid-day-number {
        background-color: #10b981;
        color: white;
        border-radius: 9999px;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .fc-event {
        border-radius: 0.5rem !important;
        border: none !important;
        padding: 4px 8px !important;
        font-weight: 600 !important;
        font-size: 0.75rem !important;
        cursor: pointer;
        transition: transform 0.15s, box-shadow 0.15s;
        overflow: hidden;
    }
    .fc-event:hover {
        transform: scale(1.02);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 100 !important;
    }
    .fc .fc-daygrid-event-dot {
        display: none;
    }
    .fc-h-event .fc-event-main {
        color: white;
    }
    .fc .fc-col-header-cell-cushion {
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        font-size: 0.75rem;
        padding: 12px 0;
    }
    .fc .fc-scrollgrid {
        border-radius: 1rem;
        overflow: hidden;
    }
    .fc-theme-standard td, .fc-theme-standard th {
        border-color: #f1f5f9;
    }
    .fc .fc-timegrid-slot-label-cushion {
        font-size: 0.75rem;
        color: #94a3b8;
    }
    .fc-direction-ltr .fc-timegrid-slot-label-frame {
        text-align: right;
    }
    
    /* Custom event content */
    .fc-event-time {
        font-weight: 700 !important;
        opacity: 0.9;
    }
    .fc-event-title {
        font-weight: 600 !important;
    }
    
    /* Tooltip Styles */
    .event-tooltip {
        position: fixed;
        z-index: 9999;
        background: white;
        border-radius: 1rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15), 0 0 0 1px rgba(0, 0, 0, 0.05);
        padding: 0;
        min-width: 280px;
        max-width: 320px;
        pointer-events: none;
        opacity: 0;
        transform: translateY(8px);
        transition: opacity 0.2s ease, transform 0.2s ease;
        overflow: hidden;
    }
    .event-tooltip.visible {
        opacity: 1;
        transform: translateY(0);
    }
    .event-tooltip-header {
        padding: 1rem;
        color: white;
    }
    .event-tooltip-body {
        padding: 1rem;
        background: white;
    }
    .event-tooltip-row {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.5rem 0;
    }
    .event-tooltip-row:not(:last-child) {
        border-bottom: 1px solid #f1f5f9;
    }
    .event-tooltip-icon {
        width: 32px;
        height: 32px;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .event-tooltip-label {
        font-size: 0.75rem;
        color: #64748b;
        font-weight: 500;
    }
    .event-tooltip-value {
        font-size: 0.875rem;
        color: #0f172a;
        font-weight: 600;
    }
    
    /* Mobile responsive */
    @media (max-width: 640px) {
        .fc .fc-toolbar {
            flex-direction: column;
            gap: 0.75rem;
        }
        .fc .fc-toolbar-title {
            font-size: 1.1rem;
        }
        .event-tooltip {
            display: none !important;
        }
    }
</style>



<x-filament::page>
    <div id="calendar"></div>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

   <script>
document.addEventListener('DOMContentLoaded', function () {

    const calendarEl = document.getElementById('calendar');

    const calendar = new FullCalendar.Calendar(calendarEl, {

        initialView: 'dayGridMonth',
        height: 'auto',
        events: @json($events),

        dateClick: function(info) {
            @this.call('openCreateModal', info.dateStr);
        },

        eventClick: function(info) {
            @this.call('openEditModal', info.event.id);
        }

    });

    calendar.render();
});
</script>
</x-filament::page>

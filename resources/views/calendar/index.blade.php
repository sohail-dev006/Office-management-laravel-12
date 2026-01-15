<x-app-layout>
<div class="container-fluid">
    <div class="row">
        @include('layouts.sidebar')

        <div class="col-md-9 col-lg-10 p-4">

            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="text-white mb-0">Company Calendar</h3>

                {{-- Only admin can add events --}}
                @role('admin')
                <button class="btn btn-success" id="addEventBtn">+ Add Event / Holiday</button>
                @endrole
            </div>

            {{-- Filter Buttons --}}
            <div class="btn-group mb-3">
                <button class="btn btn-outline-light" onclick="setFilter('all')">All</button>
                <button class="btn btn-outline-light" onclick="setFilter('holiday')">Holidays</button>
                <button class="btn btn-outline-light" onclick="setFilter('event')">Events</button>
            </div>

            {{-- Calendar --}}
            <div id="calendar" class="text-white"></div>
        </div>
    </div>
</div>

{{-- Modal only for admin --}}
@role('admin')
<div class="modal fade" id="eventModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="eventForm" class="modal-content">
            @csrf
            <input type="hidden" id="event_id">

            <div class="modal-header">
                <h5 class="modal-title">Event / Holiday</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <input class="form-control mb-2" name="title" placeholder="Title" required>
                <input class="form-control mb-2" type="date" name="start" required>
                <input class="form-control mb-2" type="date" name="end">
                <div class="form-check">
                    <input type="checkbox" name="is_holiday" id="holidayCheck" class="form-check-input">
                    <label class="form-check-label">Holiday</label>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger me-auto" id="deleteEvent">Delete</button>
                <button class="btn btn-success">Save</button>
            </div>
        </form>
    </div>
</div>
@endrole

{{-- FullCalendar + Bootstrap --}}
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const isAdmin = @json(auth()->user()->hasRole('admin'));
    let eventModal = isAdmin ? new bootstrap.Modal(document.getElementById('eventModal')) : null;
    let currentFilter = 'all';

    // Initialize FullCalendar
    const calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
        initialView: 'dayGridMonth',
        displayEventTime: false,
        editable: isAdmin,

        events: (info, success) => {
            fetch(`{{ route('calendar.fetch') }}?filter=${currentFilter}`)
                .then(res => res.json())
                .then(data => success(data));
        },

        eventClick: info => {
            if (info.event.extendedProps.can_edit && isAdmin && eventModal) {
                document.querySelector('[name=title]').value = info.event.title;
                document.querySelector('[name=start]').value = info.event.startStr;
                document.querySelector('[name=end]').value = info.event.endStr ?? '';
                document.getElementById('holidayCheck').checked = info.event.extendedProps.is_holiday;
                document.getElementById('event_id').value = info.event.id;
                eventModal.show();
            } else {
                alert(info.event.title);
            }
        },

        eventDrop: info => { if (info.event.extendedProps.can_edit) saveDrag(info); },
        eventResize: info => { if (info.event.extendedProps.can_edit) saveDrag(info); }
    });

    calendar.render();

    // Only admin can add/edit/delete events
    if (isAdmin) {
        document.getElementById('addEventBtn')?.addEventListener('click', () => {
            document.getElementById('eventForm').reset();
            document.getElementById('event_id').value = '';
            eventModal.show();
        });

        document.getElementById('eventForm').addEventListener('submit', e => {
            e.preventDefault();
            const id = document.getElementById('event_id').value;
            const url = id ? `/calendar/update/${id}` : `{{ route('calendar.store') }}`;

            fetch(url, {
                method: id ? 'PUT' : 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: new FormData(e.target)
            }).then(() => {
                eventModal.hide();
                calendar.refetchEvents();
            });
        });

        document.getElementById('deleteEvent')?.addEventListener('click', () => {
            const id = document.getElementById('event_id').value;
            if (!id || !confirm('Delete this event?')) return;

            fetch(`/calendar/delete/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            }).then(() => {
                eventModal.hide();
                calendar.refetchEvents();
            });
        });
    }

    // Drag/resize update
    function saveDrag(info) {
        fetch(`/calendar/drag/${info.event.id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                start: info.event.startStr,
                end: info.event.endStr
            })
        });
    }

    // Filter buttons
    window.setFilter = function(type) {
        currentFilter = type;
        calendar.refetchEvents();
    }
});
</script>
</x-app-layout>

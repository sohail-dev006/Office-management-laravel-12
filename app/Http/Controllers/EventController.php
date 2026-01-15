<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EventController extends Controller
{
    public function index()
    {
        return view('calendar.index');
    }

    public function fetch(Request $request)
    {
        $query = Event::query();

        if ($request->filter === 'holiday') {
            $query->where('is_holiday', true);
        }

        if ($request->filter === 'event') {
            $query->where('is_holiday', false);
        }

        return $query->get()->map(fn ($e) => [
            'id' => $e->id,
            'title' => $e->title,
            'start' => $e->start->toDateString(),
            'end' => $e->end
                ? $e->end->copy()->addDay()->toDateString()
                : null,
            'allDay' => true,
            'color' => $e->is_holiday ? '#dc3545' : '#0d6efd',

           
            'editable' => false, 
            'extendedProps' => [
                'is_holiday' => $e->is_holiday,
                'can_edit' => auth()->check() && auth()->user()->hasRole('admin'),
            ]
           
        ]);
    }


    public function store(Request $request)
    {
        Event::create([
            'title' => $request->title,
            'start' => Carbon::parse($request->start)->startOfDay(),
            'end' => $request->end
                ? Carbon::parse($request->end)->endOfDay()
                : null,
            'is_holiday' => $request->boolean('is_holiday'),
            'color' => $request->boolean('is_holiday') ? '#dc3545' : '#0d6efd',
            'created_by' => auth()->id(),
        ]);

        return response()->json(true);
    }

    public function update(Request $request, Event $event)
    {
        $event->update([
            'title' => $request->title,
            'start' => Carbon::parse($request->start)->startOfDay(),
            'end' => $request->end
                ? Carbon::parse($request->end)->endOfDay()
                : null,
            'is_holiday' => $request->boolean('is_holiday'),
            'color' => $request->boolean('is_holiday') ? '#dc3545' : '#0d6efd',
        ]);

        return response()->json(true);
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return response()->json(true);
    }

    public function dragUpdate(Request $request, Event $event)
    {
        $event->update([
            'start' => Carbon::parse($request->start)->startOfDay(),
            'end' => $request->end
                ? Carbon::parse($request->end)->endOfDay()
                : null,
        ]);

        return response()->json(true);
    }
}

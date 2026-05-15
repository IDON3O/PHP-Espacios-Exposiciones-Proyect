<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventRequest;
use App\Models\Event;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Event::with('venue')->paginate();
//        return DB::table('events')
//            ->leftJoin('venues', function (JoinClause $join) {
//                $join->on('events.fk_venue_event', '=', 'venues.id_venue')
//                    ->whereNull('venues.deleted_at');
//            })
//            ->get([
//                'event_name',
//                "venue_name"
//            ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EventRequest $request)
    {
        $event = Event::create($request->all());
        return $event;
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        return response()->json(['event' => $event->load('venue')]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EventRequest $request, Event $event)
    {
        $event->update($request->all());
        return $event;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();
        return response()->json(['mensaje' => 'Registro eliminado con éxito'], Response::HTTP_ACCEPTED);
    }

    public function indexActiveEvents() {
        return Event::with('venue')
            ->where('event_status', true)
            ->where('event_name', 'like', '%a%')
            ->get();
    }
}

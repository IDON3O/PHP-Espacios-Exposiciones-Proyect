<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class SpaceController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Spaces/Index', [
            'spaces' => Venue::withCount('reservations')->latest()->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Spaces/Form', ['space' => null]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'venue_name'         => 'required|string|max:255',
            'venue_type'         => 'required|string|max:100',
            'venue_description'  => 'nullable|string',
            'venue_rules'        => 'nullable|string',
            'venue_address'      => 'required|string|max:255',
            'venue_max_capacity' => 'required|integer|min:1',
            'price_per_hour'     => 'required|numeric|min:0',
            'is_active'          => 'boolean',
            'venue_image'        => 'nullable|image|max:2048',
        ]);

        $data['slug'] = Str::slug($data['venue_name']);

        if ($request->hasFile('venue_image')) {
            $data['venue_image'] = $request->file('venue_image')->store('venue-images', 'public');
        }

        Venue::create($data);

        return redirect()->route('spaces.index')->with('message', 'Espacio creado correctamente.');
    }

    public function edit(Venue $space)
    {
        return Inertia::render('Admin/Spaces/Form', ['space' => $space]);
    }

    public function update(Request $request, Venue $space)
    {
        $data = $request->validate([
            'venue_name'         => 'required|string|max:255',
            'venue_type'         => 'required|string|max:100',
            'venue_description'  => 'nullable|string',
            'venue_rules'        => 'nullable|string',
            'venue_address'      => 'required|string|max:255',
            'venue_max_capacity' => 'required|integer|min:1',
            'price_per_hour'     => 'required|numeric|min:0',
            'is_active'          => 'boolean',
            'venue_image'        => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('venue_image')) {
            if ($space->venue_image) Storage::disk('public')->delete($space->venue_image);
            $data['venue_image'] = $request->file('venue_image')->store('venue-images', 'public');
        }

        $space->update($data);

        return redirect()->route('spaces.index')->with('message', 'Espacio actualizado.');
    }

    public function destroy(Venue $space)
    {
        $space->delete();
        return redirect()->route('spaces.index')->with('message', 'Espacio eliminado.');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Obra;
use App\Models\Artista;

class ArtworkController extends Controller
{
    public function create()
    {
        $artists = Artista::all();
        return view('artworks.create', compact('artists'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'                  => 'required|string|max:255',
            'artist_id'             => 'required|exists:artistas,id',
            'genre_id'              => 'required|string',
            'base_price'            => 'required|numeric|min:0',
            'commission_percentage' => 'required|numeric|min:5|max:10',
            'photo'                 => 'required|image|max:2048',
        ]);

        $photoPath = $request->file('photo')->store('artworks', 'public');

        Obra::create([
            'name'                  => $request->name,
            'artist_id'             => $request->artist_id,
            'genre_id'              => $request->genre_id,
            'base_price'            => $request->base_price,
            'commission_percentage' => $request->commission_percentage,
            'photo'                 => $photoPath,
            'material'              => $request->material,
            'weight'                => $request->weight,
            'dimensions'            => $request->dimensions,
            'technique'             => $request->technique,
            'support'               => $request->support,
            'edition'               => $request->edition,
            'print_type'            => $request->print_type,
            'clay_type'             => $request->clay_type,
            'temperature'           => $request->temperature,
            'precious_material'     => $request->precious_material,
            'purity'                => $request->purity,
        ]);

        return redirect()->route('artworks.index')->with('success', '¡Obra registrada exitosamente!');
    }
}
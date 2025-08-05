<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Parking;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreParkingRequest;
use Illuminate\Support\Facades\Log;


class ParkingController extends Controller
{

    public function index()
    {
        return response()->json(Parking::orderBy('created_at', 'desc')->get());
    }

    public function store(StoreParkingRequest $request): JsonResponse
    {
        $parking = Parking::create($request->validated());

        return response()->json($parking, 201);
    }

    public function show($id)
    {
        $parking = \App\Models\Parking::find($id);

        if (!$parking) {
            return response()->json(['message' => 'Parking no encontrado'], 404);
        }

        return response()->json($parking);
    }

    public function nearest(Request $request)
    {
        $request->validate([
            'lat' => ['required', 'numeric', 'between:-90,90'],
            'lng' => ['required', 'numeric', 'between:-180,180'],
        ]);

        $lat = $request->lat;
        $lng = $request->lng;

        $nearest = Parking::selectRaw("
            *, 
            (6371000 * acos(
                cos(radians(:lat)) * cos(radians(latitud)) *
                cos(radians(longitud) - radians(:lng)) +
                sin(radians(:lat2)) * sin(radians(latitud))
            )) AS distance
        ", [
            'lat' => $lat,
            'lng' => $lng,
            'lat2' => $lat,
        ])
        ->orderBy('distance')
        ->first();

        if (!$nearest) {
            return response()->json(['message' => 'No hay parkings registrados'], 404);
        }

        $distance = round($nearest->distance);

        if ($distance > 500) {
            Log::channel('parkings')->info("Parking a más de 500m", [
                'lat' => $lat,
                'lng' => $lng,
                'distance_m' => $distance,
                'parking_id' => $nearest->id,
                'timestamp' => now()->toIso8601String(), // Fecha y hora de la solicitud
            ]);
        }

        return response()->json([
            'parking' => $nearest,
            'distance_meters' => $distance,
            'lejos' => $distance > 500,
        ]);
    }

}

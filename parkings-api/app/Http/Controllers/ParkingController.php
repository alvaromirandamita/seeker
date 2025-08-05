<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Parking;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreParkingRequest;
use Illuminate\Support\Facades\Log;

use OpenApi\Annotations as OA;


/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="API de Parkings",
 *     description="Documentación de la API REST para gestionar parkings"
 * )
 */

class ParkingController extends Controller
{


    /**
     * @OA\Get(
     *     path="/api/parkings",
     *     summary="Listar parkings",
     *     tags={"Parkings"},
     *     @OA\Response(
     *         response=200,
     *         description="Listado de parkings (puede ser vacío)",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=12),
     *                 @OA\Property(property="nombre", type="string", example="Parking Central"),
     *                 @OA\Property(property="direccion", type="string", example="Av. Siempre Viva 123"),
     *                 @OA\Property(property="latitud", type="string", example="-34.6037220"),
     *                 @OA\Property(property="longitud", type="string", example="-58.3815920"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-05T19:46:22.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-05T19:46:22.000000Z")
     *             )
     *         )
     *     )
     * )
     */

    public function index()
    {
        return response()->json(Parking::orderBy('created_at', 'desc')->get());
    }

    /**
     * @OA\Post(
     *     path="/api/parkings",
     *     summary="Crear un nuevo parking",
     *     operationId="storeParking",
     *     tags={"Parkings"},
     *   
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos necesarios para crear un nuevo parking",
     *         @OA\JsonContent(
     *             required={"nombre","direccion","latitud","longitud"},
     *             @OA\Property(property="nombre", type="string", example="Parking Central"),
     *             @OA\Property(property="direccion", type="string", example="Av. Siempre Viva 123"),
     *             @OA\Property(property="latitud", type="number", format="float", example=-34.603722),
     *             @OA\Property(property="longitud", type="number", format="float", example=-58.381592)
     *         )
     *     ),
     *     
     *     @OA\Response(
     *         response=201,
     *         description="Parking creado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="nombre", type="string", example="Parking Central"),
     *             @OA\Property(property="direccion", type="string", example="Av. Siempre Viva 123"),
     *             @OA\Property(property="latitud", type="number", format="float", example=-34.603722),
     *             @OA\Property(property="longitud", type="number", format="float", example=-58.381592),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-05T10:30:00Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-05T10:30:00Z")
     *         )
     *     ),
     *     
     *     @OA\Response(
     *         response=422,
     *         description="Datos inválidos o incompletos",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="nombre",
     *                     type="array",
     *                     @OA\Items(type="string", example="El campo nombre es obligatorio.")
     *                 )
     *             )
     *         )
     *     )
     * )
     */

    public function store(StoreParkingRequest $request): JsonResponse
    {
        $parking = Parking::create($request->validated());

        return response()->json($parking, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/parkings/{id}",
     *     summary="Obtener un parking por ID",
     *     tags={"Parkings"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *      * @OA\Response(
     *     response=200,
     *     description="Parking encontrado",
     *     @OA\JsonContent(
     *         @OA\Property(property="id", type="integer", example=11),
     *         @OA\Property(property="nombre", type="string", example="Parking Central"),
     *         @OA\Property(property="direccion", type="string", example="Av. Siempre Viva 123"),
     *         @OA\Property(property="latitud", type="string", example="-34.6037220"),
     *         @OA\Property(property="longitud", type="string", example="-35.0000000"),
     *         @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-05T19:36:48.000000Z"),
     *         @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-05T19:36:48.000000Z")
     *     )
     * ),
     *    @OA\Response(
     *         response=404,
     *         description="Parking no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Parking no encontrado")
     *         )
     *     ),
     * )
     */
    public function show($id)
    {
        $parking = \App\Models\Parking::find($id);

        if (!$parking) {
            return response()->json(['message' => 'Parking no encontrado'], 404);
        }

        return response()->json($parking);
    }

    /**
     * @OA\Get(
     *     path="/api/parkings/nearest",
     *     summary="Obtener el parking más cercano",
     *     tags={"Parkings"},
     *
     *     @OA\Parameter(
     *         name="lat",
     *         in="query",
     *         required=true,
     *         description="Latitud entre -90 y 90",
     *         @OA\Schema(type="number", format="float", example=-34.60)
     *     ),
     *     @OA\Parameter(
     *         name="lng",
     *         in="query",
     *         required=true,
     *         description="Longitud entre -180 y 180",
     *         @OA\Schema(type="number", format="float", example=-58.38)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Parking más cercano retornado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="El parking más cercano está a más de 500 metros"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="parking",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=5),
     *                     @OA\Property(property="nombre", type="string", example="Parking San Telmo"),
     *                     @OA\Property(property="direccion", type="string", example="Defensa 800"),
     *                     @OA\Property(property="latitud", type="string", example="-34.6181300"),
     *                     @OA\Property(property="longitud", type="string", example="-58.3737070"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-05T20:17:58.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-05T20:17:58.000000Z"),
     *                     @OA\Property(property="distance", type="number", format="float", example=7164813.13)
     *                 ),
     *                 @OA\Property(property="distance_meters", type="integer", example=7164813),
     *                 @OA\Property(property="is_far", type="boolean", example=true),
     *                 @OA\Property(
     *                     property="requested_coordinates",
     *                     type="object",
     *                     @OA\Property(property="lat", type="string", example="0"),
     *                     @OA\Property(property="lng", type="string", example="0")
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="No hay parkings registrados",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No hay parkings registrados")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The lat field must be a number. (and 1 more error)"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="lat",
     *                     type="array",
     *                     @OA\Items(type="string", example="The lat field must be a number.")
     *                 ),
     *                 @OA\Property(
     *                     property="lng",
     *                     type="array",
     *                     @OA\Items(type="string", example="The lng field is required.")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
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
            'status' => 'success',
            'message' => $distance > 500
                ? 'El parking más cercano está a más de 500 metros'
                : 'Parking cercano encontrado',
            'data' => [
                'parking' => $nearest,
                'distance_meters' => $distance,
                'is_far' => $distance > 500,
                'requested_coordinates' => [
                    'lat' => $lat,
                    'lng' => $lng,
                ]
            ]
        ]);
    }
}

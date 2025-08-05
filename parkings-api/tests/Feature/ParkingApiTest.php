<?php

    use function Pest\Laravel\postJson;
    use Illuminate\Foundation\Testing\RefreshDatabase;
    use App\Models\Parking;

    uses(RefreshDatabase::class);

    // ✅ Tests positivos
    it('puede crear un nuevo parking', function () {
        $data = [
            'nombre' => 'Parking Central',
            'direccion' => 'Av. Siempre Viva 123',
            'latitud' => -34.603722,
            'longitud' => -58.381592,
        ];

        postJson('/api/parkings', $data)
            ->assertCreated()
            ->assertJsonFragment([
                'nombre' => $data['nombre'],
                'direccion' => $data['direccion'],
            ]);

        expect(Parking::where('nombre', $data['nombre'])->exists())->toBeTrue();
    });

    it('puede obtener un parking existente por ID', function () {
        $parking = \App\Models\Parking::factory()->create();

        $response = $this->getJson("/api/parkings/{$parking->id}");

        $response->assertOk()
            ->assertJsonFragment([
                'id' => $parking->id,
                'nombre' => $parking->nombre,
                'direccion' => $parking->direccion,
            ]);
    });

    it('puede obtener el parking más cercano y marcar si está lejos', function () {
        // Creamos un parking en una ubicación conocida
        $parking = \App\Models\Parking::factory()->create([
            'latitud' => -34.603722,
            'longitud' => -58.381592,
        ]);

        // Coordenadas cercanas (menos de 500m)
        $response = $this->getJson('/api/parkings/nearest?lat=-34.603800&lng=-58.381600');

        $response->assertOk()
            ->assertJson([
                'parking' => [
                    'id' => $parking->id,
                ],
                'lejos' => false,
            ]);
    });

    it('devuelve "lejos" como true si el parking más cercano está a más de 500m', function () {
        // Parking en Buenos Aires
        $parking = \App\Models\Parking::factory()->create([
            'latitud' => -34.603722,
            'longitud' => -58.381592,
        ]);

        // Coordenadas en otro continente (por ejemplo, cerca del ecuador)
        $response = $this->getJson('/api/parkings/nearest?lat=0.0&lng=0.0');

        $response->assertOk()
            ->assertJson([
                'parking' => [
                    'id' => $parking->id,
                ],
                'lejos' => true,
            ]);
    });

    // ❌ Tests negativos
    it('no puede crear un parking sin datos requeridos', function () {
        $response = $this->postJson('/api/parkings', []);
        $response->assertStatus(422); // Unprocessable Entity
        $response->assertJsonValidationErrors(['nombre', 'direccion', 'latitud', 'longitud']);
    });

    it('retorna 404 si el parking no existe', function () {
        $response = $this->getJson('/api/parkings/9999');
        $response->assertStatus(404);
    });

    it('retorna error si las coordenadas están mal en nearest', function () {
        $response = $this->getJson('/api/parkings/nearest?lat=abc&lng=def');
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['lat', 'lng']);
    });
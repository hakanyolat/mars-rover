<?php

use Illuminate\Http\Response;
use Laravel\Lumen\Testing\DatabaseTransactions;

class PlateauApiTest extends TestCase {
    use DatabaseTransactions;

    public function test_should_return_all_plateaus(){
        $this->post('/api/v1/plateaus', ['x' => 25, 'y' => 25]);
        $this->post('/api/v1/plateaus', ['x' => 35, 'y' => 45]);
        $this->get('/api/v1/plateaus');
        $this->seeStatusCode(Response::HTTP_OK);
        $this->seeJsonStructure([[
            'id',
            'created_at',
            'updated_at',
            'width',
            'height',
        ]]);
    }

    public function test_should_create_plateau() {
        $this->post('/api/v1/plateaus', ['x' => 25, 'y' => 25]);
        $this->seeStatusCode(Response::HTTP_OK);
        $this->seeJsonStructure([
            'id',
            'created_at',
            'updated_at',
            'width',
            'height',
        ]);
    }

    public function test_should_not_accept_plateau_height() {
        $this->post('/api/v1/plateaus', ['x' => 555, 'y' => 25]);
        $this->seeStatusCode(Response::HTTP_BAD_REQUEST);
        $this->seeJsonStructure(['error']);
        $this->seeJsonContains(['error' => 'The Plateau height(x) must be lower than 50.']);
    }

    public function test_should_not_accept_plateau_width() {
        $this->post('/api/v1/plateaus', ['x' => 25, 'y' => 300]);
        $this->seeStatusCode(Response::HTTP_BAD_REQUEST);
        $this->seeJsonStructure(['error']);
        $this->seeJsonContains(['error' => 'The Plateau width(y) must be lower than 50.']);
    }

    public function test_should_return_plateau() {
        $testPlateau = $this->post(
            '/api/v1/plateaus',
            ['x' => 50, 'y' => 50]
        )->response->decodeResponseJson();

        $this->get("/api/v1/plateaus/{$testPlateau['id']}");
        $this->seeStatusCode(Response::HTTP_OK);
        $this->seeJsonStructure([
            'id',
            'created_at',
            'updated_at',
            'width',
            'height',
        ]);
    }

    public function test_should_update_plateau() {
        $testPlateau = $this->post(
            '/api/v1/plateaus',
            ['x' => 50, 'y' => 50]
        )->response->decodeResponseJson();
        $this->put("/api/v1/plateaus/{$testPlateau['id']}", ['x' => 25, 'y' => 25]);
        $this->seeStatusCode(Response::HTTP_OK);
    }

    public function test_should_not_update_plateau() {
        $testPlateau = $this->post(
            '/api/v1/plateaus',
            ['x' => 50, 'y' => 50]
        )->response->decodeResponseJson();
        $this->put("/api/v1/plateaus/{$testPlateau['id']}", ['x' => 500, 'y' => 25]);
        $this->seeStatusCode(Response::HTTP_BAD_REQUEST);
    }

    public function test_should_not_delete_plateau() {
        $this->delete('/api/v1/plateaus/999');
        $this->seeStatusCode(Response::HTTP_NOT_FOUND);
        $this->seeJsonStructure(['error']);
        $this->seeJsonContains(['error' => 'Plateau not found.']);
    }

    public function test_should_delete_plateau() {
        $testPlateau = $this->post(
            '/api/v1/plateaus',
            ['x' => 50, 'y' => 50]
        )->response->decodeResponseJson();

        $this->delete("/api/v1/plateaus/{$testPlateau['id']}");
        $this->seeStatusCode(Response::HTTP_OK);
        $this->seeJsonStructure(['success']);
        $this->seeJsonContains(['success' => true]);
    }
}

<?php

use Illuminate\Http\Response;
use Laravel\Lumen\Testing\DatabaseTransactions;

class RoverApiTest extends TestCase {
    use DatabaseTransactions;

    protected $plateau;

    public function setUp(): void
    {
        parent::setUp();
        $this->plateau = $this->post(
            '/api/v1/plateaus',
            ['x' => 10, 'y' => 10]
        )->response->decodeResponseJson();
    }

    public function test_should_return_all_rovers() {
        $this->post('/api/v1/rovers', [
            'x' => 5,
            'y' => 5,
            'direction' => 'N',
            'plateau_id' => $this->plateau['id'],
        ]);
        $this->post('/api/v1/rovers', [
            'x' => 9,
            'y' => 9,
            'direction' => 'N',
            'plateau_id' => $this->plateau['id'],
        ]);
        $this->get('/api/v1/rovers');
        $this->seeStatusCode(Response::HTTP_OK);
        $this->seeJsonStructure([[
            'id',
            'created_at',
            'updated_at',
            'x',
            'y',
            'direction',
            'state',
            'queue',
            'plateau_id',
        ]]);
    }

    public function test_should_return_rover() {
        $testRover = $this->post(
            '/api/v1/rovers', [
                'x' => 5,
                'y' => 5,
                'direction' => 'N',
                'plateau_id' => $this->plateau['id'],
            ]
        )->response->decodeResponseJson();

        $this->get("/api/v1/rovers/{$testRover['id']}");
        $this->seeStatusCode(Response::HTTP_OK);
        $this->seeJsonStructure([
            'id',
            'created_at',
            'updated_at',
            'x',
            'y',
            'direction',
            'state',
            'queue',
            'plateau_id',
        ]);
    }

    public function test_should_not_deploy_rover_the_unavailable_position() {
        $this->post('/api/v1/rovers', [
            'x' => 5,
            'y' => 5,
            'direction' => 'N',
            'plateau_id' => $this->plateau['id'],
        ]);
        $this->post('/api/v1/rovers', [
            'x' => 5,
            'y' => 5,
            'direction' => 'N',
            'plateau_id' => $this->plateau['id'],
        ]);
        $this->seeStatusCode(Response::HTTP_BAD_REQUEST);
        $this->seeJsonStructure(['error']);
        $this->seeJsonContains(['error' => 'The rover can not be deploy here.']);
    }

    public function test_should_not_deploy_rover_the_out_of_plateau_bounds() {
        $this->post('/api/v1/rovers', [
            'x' => 50,
            'y' => 40,
            'direction' => 'N',
            'plateau_id' => $this->plateau['id'],
        ]);
        $this->seeStatusCode(Response::HTTP_BAD_REQUEST);
        $this->seeJsonStructure(['error']);
        $this->seeJsonContains(['error' => 'The rover can not be deploy here.']);
    }

    public function test_should_not_delete_rover(){
        $this->delete('/api/v1/rovers/999');
        $this->seeStatusCode(Response::HTTP_NOT_FOUND);
        $this->seeJsonStructure(['error']);
        $this->seeJsonContains(['error' => 'Rover not found.']);
    }

    public function test_should_delete_rover(){
        $testRover = $this->post('/api/v1/rovers', [
            'x' => 0,
            'y' => 0,
            'direction' => 'N',
            'plateau_id' => $this->plateau['id'],
        ])->response->decodeResponseJson();
        $this->delete("/api/v1/rovers/{$testRover['id']}");
        $this->seeStatusCode(Response::HTTP_OK);
        $this->seeJsonStructure(['success']);
        $this->seeJsonContains(['success' => true]);
    }

    public function test_should_run_commands() {
        $testRover = $this->post('/api/v1/rovers', [
            'x' => 0,
            'y' => 0,
            'direction' => 'N',
            'plateau_id' => $this->plateau['id'],
        ])->response->decodeResponseJson();

        $this->post("/api/v1/rovers/{$testRover['id']}/execute", [
            'commands' => 'MMRMMR'
        ]);

        $this->seeStatusCode(Response::HTTP_OK);
        $this->seeJsonContains([
            'x' => 2,
            'y' => 2,
            'state' => 'READY',
            'queue' => null,
            'direction' => 'S',
        ]);
    }

    public function test_should_interrupt_commands() {
        $testRover = $this->post('/api/v1/rovers', [
            'x' => 2,
            'y' => 2,
            'direction' => 'N',
            'plateau_id' => $this->plateau['id'],
        ])->response->decodeResponseJson();

        $this->post("/api/v1/rovers/{$testRover['id']}/execute", [
            'commands' => 'LMMMRL'
        ]);

        $this->seeStatusCode(Response::HTTP_OK);
        $this->seeJsonContains([
            'x' => 0,
            'y' => 2,
            'state' => 'INTERRUPTED',
            'queue' => 'MRL',
            'direction' => 'W',
        ]);
    }

    public function test_should_not_execute_commands_of_interrupted_rover() {
        $testRover = $this->post('/api/v1/rovers', [
            'x' => 2,
            'y' => 2,
            'direction' => 'N',
            'plateau_id' => $this->plateau['id'],
        ])->response->decodeResponseJson();

        $this->post("/api/v1/rovers/{$testRover['id']}/execute", [
            'commands' => 'LMMMRL'
        ]);

        $this->post("/api/v1/rovers/{$testRover['id']}/execute", [
            'commands' => 'MMM'
        ]);

        $this->seeStatusCode(Response::HTTP_BAD_REQUEST);
        $this->seeJsonStructure(['error']);
        $this->seeJsonContains(['error' => 'Rover is not ready. Please cancel commands in queue first.']);
    }

    public function test_should_stop_interrupted_rover() {
        $testRover = $this->post('/api/v1/rovers', [
            'x' => 2,
            'y' => 2,
            'direction' => 'N',
            'plateau_id' => $this->plateau['id'],
        ])->response->decodeResponseJson();

        $this->post("/api/v1/rovers/{$testRover['id']}/execute", [
            'commands' => 'LMMMRL'
        ]);

        $this->post("/api/v1/rovers/{$testRover['id']}/stop");
        $this->seeStatusCode(Response::HTTP_OK);
        $this->seeJsonContains([
            'x' => 0,
            'y' => 2,
            'state' => 'READY',
            'queue' => null,
            'direction' => 'W',
        ]);
    }
}

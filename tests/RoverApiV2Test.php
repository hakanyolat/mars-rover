<?php

use Illuminate\Http\Response;
use Laravel\Lumen\Testing\DatabaseTransactions;

class RoverApiV2Test extends TestCase {
    use DatabaseTransactions;

    protected $plateau;

    public function setUp(): void
    {
        parent::setUp();
        $this->plateau = $this->post(
            '/api/v2/plateaus',
            ['x' => 10, 'y' => 10]
        )->response->decodeResponseJson();
    }

    public function test_should_run_commands() {
        $testRover = $this->post('/api/v2/rovers', [
            'x' => 0,
            'y' => 0,
            'direction' => 'N',
            'plateau_id' => $this->plateau['id'],
        ])->response->decodeResponseJson();

        $this->post("/api/v2/rovers/{$testRover['id']}/execute", [
            'commands' => '+++>+>>-R+M'
        ]);

        $this->seeStatusCode(Response::HTTP_OK);
        $this->seeJsonContains([
            'x' => 4,
            'y' => 4,
            'state' => 'READY',
            'queue' => null,
            'direction' => 'E',
        ]);
    }

    public function test_should_interrupt_commands() {
        $testRover = $this->post('/api/v2/rovers', [
            'x' => 3,
            'y' => 2,
            'direction' => 'W',
            'plateau_id' => $this->plateau['id'],
        ])->response->decodeResponseJson();

        $this->post("/api/v2/rovers/{$testRover['id']}/execute", [
            'commands' => 'LL<---LMRM+><'
        ]);

        $this->seeStatusCode(Response::HTTP_OK);
        $this->seeJsonContains([
            'x' => 2,
            'y' => 0,
            'state' => 'INTERRUPTED',
            'queue' => '-LMRM+><',
            'direction' => 'E',
        ]);
    }
}

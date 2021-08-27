<?php

use App\Helpers\Direction;
use App\Helpers\Position;

class PositionTest extends TestCase {
    protected Position $testPosition;

    public function setUp(): void
    {
        parent::setUp();
        $this->testPosition = new Position(0, 0);
    }

    public function test_should_validate_forward_of_north_face() {
        $forward = $this->testPosition->getForward(Direction::North);
        $this->assertEquals(0, $forward->x);
        $this->assertEquals(1, $forward->y);
    }

    public function test_should_validate_forward_of_south_face() {
        $forward = $this->testPosition->getForward(Direction::South);
        $this->assertEquals(0, $forward->x);
        $this->assertEquals(-1, $forward->y);
    }


    public function test_should_validate_forward_of_east_face() {
        $forward = $this->testPosition->getForward(Direction::East);
        $this->assertEquals(1, $forward->x);
        $this->assertEquals(0, $forward->y);
    }


    public function test_should_validate_forward_of_west_face() {
        $forward = $this->testPosition->getForward(Direction::West);
        $this->assertEquals(-1, $forward->x);
        $this->assertEquals(0, $forward->y);
    }

}

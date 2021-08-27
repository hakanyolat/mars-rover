<?php

use App\Helpers\Direction;

class DirectionTest extends TestCase {
    public function test_should_validate_direction() {
        $this->assertTrue(Direction::validate('N'));
        $this->assertTrue(Direction::validate('E'));
        $this->assertTrue(Direction::validate('S'));
        $this->assertTrue(Direction::validate('W'));
    }

    public function test_should_not_validate_direction() {
        $this->assertFalse(Direction::validate('X'));
    }

    public function test_should_valid_direction_when_request_to_left() {
        $this->assertEquals('W', Direction::toLeft('N'));
        $this->assertEquals('S', Direction::toLeft('W'));
        $this->assertEquals('E', Direction::toLeft('S'));
        $this->assertEquals('N', Direction::toLeft('E'));
    }

    public function test_should_valid_direction_when_request_to_right() {
        $this->assertEquals('W', Direction::toRight('S'));
        $this->assertEquals('S', Direction::toRight('E'));
        $this->assertEquals('E', Direction::toRight('N'));
        $this->assertEquals('N', Direction::toRight('W'));
    }
}

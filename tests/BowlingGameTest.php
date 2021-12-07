<?php

use App\BowlingGame;
use PHPUnit\Framework\TestCase;

class BowlingGameTest extends TestCase
{
    /**
     * @test
     */
    function it_can_start_the_game()
    {
        $game = new BowlingGame;

        $this->assertEquals(0, $game->totalScore());
    }

    /**
     * @test
     */
    function it_can_track_first_roll()
    {
        $game = new BowlingGame;

        $game->roll(7);

        $this->assertEquals(7, $game->totalScore());
    }

    /**
     * @test
     */
    function it_can_track_second_roll()
    {
        $game = new BowlingGame;

        $game->roll(7);
        $game->roll(2);

        $this->assertEquals(9, $game->totalScore());
    }

    /**
     * @test
     */
    function it_can_track_second_frame()
    {
        $game = new BowlingGame;

        $game->roll(7);
        $game->roll(2);

        $game->roll(4);

        $this->assertEquals(2, $game->getCurrentFrame());
    }

    /**
     * @test
     */
    function it_can_track_a_spare()
    {
        $game = new BowlingGame;

        $game->roll(7);
        $game->roll(3);

        $game->roll(4);
        $game->roll(4);

        $this->assertEquals(22, $game->totalScore());
    }

    /**
     * @test
     */
    function it_can_track_a_zero_ten_spare()
    {
        $game = new BowlingGame;

        $game->roll(0);
        $game->roll(10);

        $game->roll(4);
        $game->roll(4);

        $this->assertEquals(22, $game->totalScore());
    }

    /**
     * @test
     */
    function it_can_track_a_strike()
    {
        $game = new BowlingGame;

        $game->roll(10);

        $game->roll(4);
        $game->roll(4);

        $this->assertEquals(26, $game->totalScore());
    }

    /**
     * @test
     */
    function it_can_track_a_perfect_game()
    {
        $game = new BowlingGame;

        for ($frame = 1; $frame <= 9; $frame++) {
            $game->roll(10);
        }

        $game->roll(10);
        $game->roll(10);
        $game->roll(10);

        $this->assertEquals(300, $game->totalScore());
    }

    /**
     * @test
     */
    function it_stops_scoring_after_non_spared_tenth_frame()
    {
        $game = new BowlingGame;

        for ($frame = 1; $frame <= 10; $frame++) {
            $game->roll(1);
            $game->roll(1);
        }

        $this->assertEquals(20, $game->totalScore());

        $game->roll(1);

        $this->assertEquals(20, $game->totalScore());
    }

    /**
     * @test
     */
    function it_stops_scoring_after_spared_tenth_frame()
    {
        $game = new BowlingGame;

        for ($frame = 1; $frame <= 9; $frame++) {
            $game->roll(1);
            $game->roll(1);
        }

        $game->roll(5);
        $game->roll(5);
        $game->roll(5);

        $this->assertEquals(33, $game->totalScore());

        $game->roll(5);

        $this->assertEquals(33, $game->totalScore());
    }

    /**
     * @test
     */
    function it_stops_scoring_after_striked_tenth_frame()
    {
        $game = new BowlingGame;

        for ($frame = 1; $frame <= 9; $frame++) {
            $game->roll(1);
            $game->roll(1);
        }

        $game->roll(10);
        $game->roll(10);
        $game->roll(10);

        $this->assertEquals(48, $game->totalScore());

        $game->roll(5);

        $this->assertEquals(48, $game->totalScore());
    }

    /**
     * @test
     */
    function it_can_track_the_weirdest_game_ever()
    {
        $game = new BowlingGame;

        $game->roll(0);
        $game->roll(10);

        $game->roll(10);

        $game->roll(0);
        $game->roll(0);

        $game->roll(10);

        $game->roll(10);

        $game->roll(0);
        $game->roll(10);

        $game->roll(0);
        $game->roll(0);

        $game->roll(0);
        $game->roll(10);

        $game->roll(10);

        $game->roll(0);
        $game->roll(10);
        $game->roll(10);

        $this->assertEquals(140, $game->totalScore());
    }
}

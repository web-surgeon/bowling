<?php
namespace App;

class BowlingGame
{
    const MAX_PINS   = 10;
    const MAX_FRAMES = 10;

    protected $score = 0;
    protected $frame = 1;
    protected $rolls = [];

    /**
     * @param int $pins
     * @return bool
     */
    public function roll(int $pins): bool
    {
        if ($this->isGameOver($pins)) {
            return false;
        }

        $this->rolls[$this->frame][] = $pins;

        if ($this->isFrameOver($pins)) {
            $this->frame++;
        }

        return true;
    }

    /**
     * @return int
     */
    public function getCurrentFrame(): int
    {
        return $this->frame;
    }

    /**
     * @param int $frame
     * @return int
     */
    public function rollsInFrame(int $frame): int
    {
        if (!$this->doesFrameExist($frame)) {
            return 0;
        }

        return count($this->rolls[$frame]);
    }

    /**
     * @param int $pins
     * @return bool
     */
    public function isGameOver(int $pins): bool
    {
        if ($this->frame > self::MAX_FRAMES) {
            return true;
        }

        if ($this->isLastFrame($this->frame) && $this->isLastFrameOver($pins)) {
            return true;
        }

        return false;
    }

    /**
     * @param int $frame
     * @return bool
     */
    public function isLastFrame(int $frame): bool
    {
        return $frame == self::MAX_FRAMES;
    }

    /**
     * @param int $pins
     * @return bool
     */
    public function isLastFrameOver(int $pins): bool
    {
        if ($this->rollsInFrame(self::MAX_FRAMES) == 2 && !$this->isSpareRolled($this->frame) && $pins != self::MAX_PINS) {
            return true;
        }

        if ($this->rollsInFrame(self::MAX_FRAMES) >= 3) {
            return true;
        }

        return false;
    }

    /**
     * @param int $pins
     * @return bool
     */
    public function isFrameOver(int $pins): bool
    {
        if ($this->isLastFrame($this->frame)) {
            if ($this->isLastFrameOver($pins)) {
                return true;
            }
        } else {
            if ($pins == self::MAX_PINS) {
                return true;
            }

            if ($this->rollsInFrame($this->frame) == 2) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $frame
     * @return bool
     */
    public function doesFrameExist($frame): bool
    {
        return isset($this->rolls[$frame]);
    }

    /**
     * @param int $frame
     * @param bool $onlyFirstTwoRolls
     * @return int
     */
    public function getFrameScore(int $frame, bool $onlyFirstTwoRolls = false): int
    {
        $score = 0;

        if (isset($this->rolls[$frame][0])) {
            $score += $this->rolls[$frame][0];
        }

        if (isset($this->rolls[$frame][1])) {
            $score += $this->rolls[$frame][1];
        }

        if (isset($this->rolls[$frame][2]) && !$onlyFirstTwoRolls) {
            $score += $this->rolls[$frame][2];
        }

        return $score;
    }

    /**
     * @param int $frame
     */
    public function addStrikeToScore(int $frame)
    {
        if ($this->doesFrameExist($frame + 1)) {
            $this->score += $this->getFrameScore($frame + 1, true);

            if ($this->isStrikeRolled($frame + 1) && $this->doesFrameExist($frame + 2)) {
                $this->score += $this->rolls[$frame + 2][0];
            }
        }
    }

    /**
     * @param int $frame
     */
    public function addSpareToScore(int $frame)
    {
        if ($this->doesFrameExist($frame + 1)) {
            $this->score += $this->rolls[$frame + 1][0];
        }
    }

    /**
     * @param int $frame
     * @return bool
     */
    public function isSpareRolled(int $frame): bool
    {
        if ($this->rollsInFrame($frame) <= 1) {
            return false;
        }

        if ($this->getFrameScore($frame) < self::MAX_PINS) {
            return false;
        }

        if ($this->rollsInFrame($frame) == 2 && $this->getFrameScore($frame) == self::MAX_PINS) {
            return true;
        }

        return false;
    }

    /**
     * @param int $frame
     * @return bool
     */
    public function isStrikeRolled(int $frame): bool
    {
        if ($this->rolls[$frame][0] == self::MAX_PINS) {
            return true;
        }

        return false;
    }

    /**
     * @return int
     */
    public function totalScore(): int
    {
        $this->score = 0;

        foreach ($this->rolls as $frame => $rolls) {
            $this->score += array_sum($rolls);

            if ($frame < self::MAX_FRAMES) {
                if ($this->isStrikeRolled($frame)) {
                    $this->addStrikeToScore($frame);
                } elseif ($this->isSpareRolled($frame)) {
                    $this->addSpareToScore($frame);
                }
            }

            echo $frame . ' = > ' . $this->score . "\n";
        }

        // print_r($this->rolls);

        echo 'Final Score: ' . $this->score . "\n\n";

        return $this->score;
    }
}
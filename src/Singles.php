<?php

namespace Puggan\SgtJsonPuzzle;

class Singles extends Game
{
    public int $columns;
    public int $rows;
    public string $difficulty;

    public function __construct(array $config)
    {

    }

    public function startState(): array
    {
        $this->runSolver('singlessolver 12dk');
        if (!preg_match('/^(?<w>\d+)x(?<h>\d+)d(?<d>.)$/', $this->configString, $matches)) {
            throw new \Exception('failed to parse config: ' . $this->configString);
        }
        $this->columns = $matches['w'];
        $this->rows = $matches['h'];
        $this->difficulty = match ($matches['d']) {
            'e' => 'Easy',
            'k' => 'Tricky',
        };

        return [
            'id' => $this->id,
            'name' => $this->name,
            'seed' => $this->seed,
            'settings' => [
                'columns' => $this->columns,
                'rows' => $this->rows,
                'difficulty' => $this->difficulty,
            ],
            'state' => array_chunk(
                array_map(
                    static function ($char): int {
                        return $char <= '9' ?
                            +$char :
                            ord($char) - ($char <= 'Z' ? 29 : 87);
                    },
                    str_split($this->id)
                ),
                $this->columns
            ),
        ];
    }
}
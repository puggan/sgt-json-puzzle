<?php

namespace Puggan\SgtJsonPuzzle;

class Slant extends Game
{
    public int $columns;
    public int $rows;
    public string $difficulty;

    public function __construct(array $config)
    {

    }

    public function startState(): array
    {
        $this->runSolver('slantsolver');
        if (!preg_match('/^(?<w>\d+)x(?<h>\d+)d(?<d>.)$/', $this->configString, $matches)) {
            throw new \Exception('failed to parse config: ' . $this->configString);
        }
        $this->columns = $matches['w'];
        $this->rows = $matches['h'];
        $this->difficulty = match($matches['d']) {
            'e' => 'Easy',
            't' => 'Tricky',
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
            'state' => (new GridParser($this->rows + 1, $this->columns + 1))->parse($this->id)
        ];
    }
}
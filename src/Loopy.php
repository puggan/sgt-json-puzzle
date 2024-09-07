<?php

namespace Puggan\SgtJsonPuzzle;

class Loopy extends Game
{
    public int $columns;
    public int $rows;
    public int $type;
    public string $difficulty;

    public function __construct(array $config)
    {

    }

    public function startState(): array
    {
        $this->runSolver('loopysolver');
        if (!preg_match('/^(?<w>\d+)x(?<h>\d+)t(?<t>\d+)d(?<d>.)$/', $this->configString, $matches)) {
            throw new \Exception('failed to parse config: ' . $this->configString);
        }
        $this->columns = $matches['w'];
        $this->rows = $matches['h'];
        $this->type = $matches['t'];
        $this->difficulty = match($matches['d']) {
            'e' => 'Easy',
            'n' => 'Normal',
            't' => 'Tricky',
            'h' => 'Hard',
        };

        return [
            'id' => $this->id,
            'name' => $this->name,
            'seed' => $this->seed,
            'settings' => [
                'columns' => $this->columns,
                'rows' => $this->rows,
                'type' => $this->type,
                'difficulty' => $this->difficulty,
            ],
            'state' => (new GridParser($this->rows, $this->columns))->parse($this->id)
        ];
    }
}
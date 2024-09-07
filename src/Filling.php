<?php

namespace Puggan\SgtJsonPuzzle;

class Filling extends Game
{
    public int $columns;
    public int $rows;

    public function __construct(array $config)
    {

    }

    public function startState(): array
    {
        $this->runSolver('fillingsolver');
        if (!preg_match('/^(?<w>\d+)x(?<h>\d+)$/', $this->configString, $matches)) {
            throw new \Exception('failed to parse config: ' . $this->configString);
        }
        $this->columns = $matches['w'];
        $this->rows = $matches['h'];

        return [
            'id' => $this->id,
            'name' => $this->name,
            'seed' => $this->seed,
            'settings' => [
                'columns' => $this->columns,
                'rows' => $this->rows,
            ],
            'state' => (new GridParser($this->rows, $this->columns))->parse($this->id)
        ];
    }
}
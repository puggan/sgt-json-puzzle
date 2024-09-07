<?php

namespace Puggan\SgtJsonPuzzle;

class Tents extends Game
{
    public int $columns;
    public int $rows;
    public string $difficulty;

    public function __construct(array $config)
    {

    }

    public function startState(): array
    {
        $this->runSolver('tentssolver');
        if (!preg_match('/^(?<w>\d+)x(?<h>\d+)d(?<d>.)$/', $this->configString, $matches)) {
            throw new \Exception('failed to parse config: ' . $this->configString);
        }
        $this->columns = $matches['w'];
        $this->rows = $matches['h'];
        $this->difficulty = match ($matches['d']) {
            'e' => 'Easy',
            't' => 'Tricky',
        };

        $clues = explode(',', $this->id);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'seed' => $this->seed,
            'settings' => [
                'columns' => $this->columns,
                'rows' => $this->rows,
            ],
            'state' => (new GridParser(
                $this->rows,
                $this->columns,
                true,
                '1',
                static function () {
                    return 1;
                }
            ))->parse($clues[0]),
        ];
    }
}
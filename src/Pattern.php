<?php

namespace Puggan\SgtJsonPuzzle;

class Pattern extends Game
{
    public int $columns;
    public int $rows;

    public function __construct(array $config)
    {

    }

    protected function parseSolverOutput(): void
    {
        [$row0name, $row0value] = explode(': ', $this->output[0] ?? '', 2) + ['', ''];
        [$row1name, $this->seed] = explode(': ', $this->output[1] ?? '', 2) + ['', ''];
        [$this->configString, $this->id] = explode(':', $row0value, 2) + ['', ''];
    }

    public function startState(): array
    {
        $this->runSolver('patternsolver');
        if (!preg_match('/^(?<w>\d+)x(?<h>\d+)$/', $this->configString, $matches)) {
            throw new \Exception('failed to parse config: ' . $this->configString);
        }
        $this->columns = $matches['w'];
        $this->rows = $matches['h'];

        $clues = array_map(
            static function ($cell) {
                return explode('.', $cell);
            },
            explode('/', $this->id)
        );

        return [
            'id' => $this->id,
            'name' => 'pattern',
            'seed' => $this->seed,
            'settings' => [
                'columns' => $this->columns,
                'rows' => $this->rows,
            ],
            'state' => [
                'columns' => array_slice($clues, 0, $this->columns),
                'rows' => array_slice($clues, $this->columns),
            ],
        ];
    }
}
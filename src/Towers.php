<?php

namespace Puggan\SgtJsonPuzzle;

class Towers extends Game
{
    public int $columns;
    public int $rows;
    public string $difficulty;

    public function __construct(array $config)
    {

    }

    public function startState(): array
    {
        $this->runSolver('towerssolver');
        if (!preg_match('/^(?<w>\d+)d(?<d>.)$/', $this->configString, $matches)) {
            throw new \Exception('failed to parse config: ' . $this->configString);
        }

        $this->columns = $matches['w'];
        $this->rows = $matches['w'];
        $this->difficulty = match ($matches['d']) {
            'e' => 'Easy',
            'h' => 'Hard',
            'x' => 'Extreme',
            'u' => 'Unreasonable',
        };


        [$clues, $towers] = explode(',', $this->id, 2) + ['', ''];
        $clues = array_map(
            static function ($clue): ?int {
                return $clue ? +$clue : null;
            },
            explode('/', $clues)
        );

        /** @var array{N: list<?int>, S: list<?int>, W: list<?int>, E: list<?int>} $directionClues */
        $directionClues = ['N' => [], 'S' => [], 'W' => [], 'E' => []];
        foreach (['N', 'S', 'W', 'E'] as $index => $dir) {
            $directionClues[$dir] = array_slice($clues, $index * $this->columns, $this->columns);
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'seed' => $this->seed,
            'settings' => [
                'columns' => $this->columns,
                'rows' => $this->rows,
                'difficulty' => $this->difficulty,
            ],
            'state' => $directionClues + [
                'grid' => (new GridParser($this->rows, $this->columns))->parse($towers)
            ]
        ];
    }
}
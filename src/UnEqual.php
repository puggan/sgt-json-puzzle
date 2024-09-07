<?php

namespace Puggan\SgtJsonPuzzle;

class UnEqual extends Game
{
    public int $columns;
    public int $rows;
    public string $difficulty;
    public bool $adjacent;

    public function __construct(array $config)
    {

    }

    public function startState(): array
    {
        $this->runSolver('unequalsolver');
        if (!preg_match('/^(?<w>\d+)(?<a>a?)d(?<d>.)$/', $this->configString, $matches)) {
            throw new \Exception('failed to parse config: ' . $this->configString);
        }
        $this->columns = $matches['w'];
        $this->rows = $matches['w'];
        $this->adjacent = $matches['a'] === 'a';
        $this->difficulty = match ($matches['d']) {
            't' => 'Trival',
            'e' => 'Easy',
            'k' => 'Tricky',
            'x' => 'Extreme',
            'r' => 'Recursive',
        };

        if(!preg_match_all('#(?<d>\d+)(?<c>[URDL]*),#', $this->id, $parts, PREG_SET_ORDER))
        {
            throw new \Exception('failed to parse grid: ' . $this->id);
        }

        $clues = [];
        foreach($parts as $cell)
        {
            $clues[] = [
                $cell['d'] ? +$cell['d'] : null,
                $cell['c'] ?: null
            ];
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'seed' => $this->seed,
            'settings' => [
                'columns' => $this->columns,
                'rows' => $this->rows,
                'difficulty' => $this->difficulty,
                'adjacent' => $this->adjacent,
            ],
            'state' => array_chunk($clues, $this->columns),
        ];
    }
}
<?php

namespace Puggan\SgtJsonPuzzle;

class Dominosa extends Game
{
    public int $columns;
    public int $rows;
    public string $difficulty;
    public int $maxValue;

    public function __construct(array $config)
    {

    }

    public function startState(): array
    {
        $this->runSolver('dominosasolver');
        if (!preg_match('/^(?<m>\d+)d(?<d>.)$/', $this->configString, $matches)) {
            throw new \Exception('failed to parse config: ' . $this->configString);
        }
        $this->maxValue = (int)$matches['m'];
        $this->columns = $this->maxValue + 2;
        $this->rows = $this->maxValue + 1;
        $this->difficulty = match($matches['d']) {
            't' => 'Trival',
            'b' => 'Basic',
            'h' => 'Hard',
            'e' => 'Extreme',
            'a' => 'Ambiguous',
        };

        if(!preg_match_all('#\[\d+]|\d#', $this->id, $parts))
        {
            die('Failed generations');
        }

        $clues = array_map(
            static function ($n): int {
                return $n[0] === '[' ? (int)substr($n, 1, -1) : (int)$n;
            },
            $parts[0]
        );

        return [
            'id' => $this->id,
            'name' => $this->name,
            'seed' => $this->seed,
            'settings' => [
                'columns' => $this->columns,
                'rows' => $this->rows,
                'difficulty' => $this->difficulty,
                'maxValue' => $this->maxValue,
            ],
            'state' => array_chunk($clues, $this->columns),
        ];
    }
}
<?php

namespace Puggan\SgtJsonPuzzle;

class Solo extends Game
{
    public int $columns;
    public int $rows;
    public string $difficulty;
    public bool $diagonal;
    public bool $killer;

    public function __construct(array $config)
    {

    }

    public function startState(): array
    {
        $this->runSolver('solosolver');
        if (!preg_match('/^^(?<w>\d+)x(?<h>\d+)(?<x>x)?(?<k>k)?(?<s>a|[mr]d?[248])?(d(?<d>.))?$/', $this->configString, $matches)) {
            throw new \Exception('failed to parse config: ' . $this->configString);
        }
        $this->columns = $matches['w'];
        $this->rows = $matches['h'];
        $size = $this->columns * $this->rows;
        $this->difficulty = match($matches['d'] ?? 't') {
            't' => 'Trival',
            'b' => 'Basic',
            'i' => 'Intermediate',
            'a' => 'Advanced',
            'e' => 'Extreme',
            'u' => 'Unreasonable',
        };
        $this->diagonal = $matches['x'] === 'x';
        $this->killer = $matches['k'] === 'k';

        return [
            'id' => $this->id,
            'name' => $this->name,
            'seed' => $this->seed,
            'settings' => [
                'columns' => $this->columns,
                'rows' => $this->rows,
                'difficulty' => $this->difficulty,
                'diagonal' => $this->diagonal,
                'jigsaw' => false,
                'killer' => $this->killer,
            ],
            'state' => (new GridParser($size, $size))->parse($this->id)
        ];
    }
}
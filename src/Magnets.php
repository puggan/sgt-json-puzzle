<?php

namespace Puggan\SgtJsonPuzzle;

class Magnets extends Game
{
    public int $columns;
    public int $rows;
    public string $difficulty;
    public bool $strip;
    private string $cluesRaw;
    private string $layoutRaw;

    public function __construct(array $config)
    {

    }

    protected function parseSolverOutput(): void
    {
        if (!preg_match('/^(?<config>(?<w>\d+)x(?<h>\d+)d(?<d>.)(?<s>S?)):(?<id>(?<c>[\d.,]+)(?<l>[BLRT]+)) \(seed (?<seed>\d+)\)$/', $this->output[0], $matches)) {
            throw new \Exception('Failed to parse output');
        }
        $this->name = 'Magnets';
        $this->id = $matches['id'];
        $this->seed = $matches['seed'];
        $this->configString = $matches['config'];
        $this->columns = $matches['w'];
        $this->rows = $matches['h'];
        $this->difficulty = match($matches['d']) {
            'e' => 'Easy',
            't' => 'Tricky',
        };
        $this->strip = $matches['s'] === 'S';
        $this->cluesRaw = $matches['c'];
        $this->layoutRaw = $matches['l'];
    }

    public function startState(): array
    {
        $this->runSolver('magnetssolver');

        $cluesSections = explode(',', strtr($this->cluesRaw, ['.' => ' ']));

        return [
            'id' => $this->id,
            'name' => $this->name,
            'seed' => $this->seed,
            'settings' => [
                'columns' => $this->columns,
                'rows' => $this->rows,
                'difficulty' => $this->difficulty,
                'strip' => $this->strip,
            ],
            'state' => [
                'N' => str_split($cluesSections[0]),
                'W' => str_split($cluesSections[1]),
                'S' => str_split($cluesSections[2]),
                'E' => str_split($cluesSections[3]),
                'layout' => array_chunk(str_split($this->layoutRaw), $this->columns),
            ],
        ];
    }
}
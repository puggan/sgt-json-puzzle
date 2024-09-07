<?php

namespace Puggan\SgtJsonPuzzle;

class UnRuly extends Game
{
    public int $columns;
    public int $rows;
    public string $difficulty;
    public bool $unique;

    public function __construct(array $config)
    {

    }

    protected function parseSolverOutput(): void
    {
        $this->name = 'Unruly';
        $this->configString = preg_replace('/.* /', '', $this->output[0]);
        $lastRows = array_slice($this->output, -2);
        [$row1name, $this->id] = explode(': ', $lastRows[0] ?? '', 2) + ['', ''];
        [$row2name, $this->seed] = explode(': ', $lastRows[1] ?? '', 2) + ['', ''];
    }

    public static function mapChar2Int($char): ?int
    {
        return match($char) {
            '0', '1' => (int)$char,
            '.' => null,
        };
    }

    public function startState(): array
    {
        $this->runSolver('unrulysolver');
        if (!preg_match('/^(?<w>\d+)x(?<h>\d+)(?<x>u)?d(?<d>.)$/', $this->configString, $matches)) {
            throw new \Exception('failed to parse config: ' . $this->configString);
        }
        $this->columns = $matches['w'];
        $this->rows = $matches['h'];
        $this->difficulty = match($matches['d']) {
            'e' => 'Easy',
            'n' => 'Normal',
        };
        $this->unique = $matches['u'] === 'u';

        $chr_table = '.01';
        $chr_lookup = static function ($c) use ($chr_table) {
            return strpos($chr_table, $c);
        };

        $state = [];
        foreach(range(1, $this->rows) as $row_nr)
        {
            $state[] = array_map(
                /** @see mapChar2Int */
                self::class . '::mapChar2Int',
                explode(' ', $this->output[$row_nr])
            );
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'seed' => $this->seed,
            'settings' => [
                'columns' => $this->columns,
                'rows' => $this->rows,
                'difficulty' => $this->difficulty,
                'unique' => $this->unique,
            ],
            'state' => $state,
        ];
    }
}
<?php

namespace Puggan\SgtJsonPuzzle;

abstract class Game
{
    public string $name;
    public string $id;
    public string $seed;
    public string $configString;

    /** @var list<string> $output */
    public array $output = [];
    abstract public function __construct(array $config);
    abstract public function startState(): array;

    protected function runSolver(string $solverName): void
    {
        if (false === exec(dirname(__DIR__) . '/bin/' . $solverName, $this->output)) {
            throw new \Exception('Failed to run solver: ' . $solverName);
        }

        [$this->name, $this->configString] = explode(': ', $this->output[0], 2) + ['', ''];
        [$row1name, $row1value] = explode(': ', $this->output[1], 2) + ['', ''];
        [$row2name, $row2value] = explode(': ', $this->output[2], 2) + ['', ''];
        $this->seed = $row1name === 'Seed' ? $row1value : $row2value;
        $this->id = $row1name !== 'Seed' ? $row1value : $row2value;
    }
}
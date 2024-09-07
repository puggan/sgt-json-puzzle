<?php

namespace Puggan\SgtJsonPuzzle;

class GridParser
{
    public function __construct(
        public int $rows,
        public int $columns,
        public bool $skipZ = false,
        public string $valueRegexp = '\d',
        public ?\Closure $valueCallback = null,
    )
    {
    }

    public function emptyGrid($value = null)
    {
        return array_fill(0, $this->rows, array_fill(0, $this->columns, $value));
    }


    public function parse(string $id, ?array $grid = null): array
    {
        $grid ??= $this->emptyGrid();
        $regexp = '/(?<d>z*[a-' . ($this->skipZ ? 'y' : 'z') . '_])?(?<n>' . $this->valueRegexp . '?)/';

        if (!preg_match_all($regexp, $id, $parts, PREG_SET_ORDER)) {
            return $grid;
        }

        $in_range_error = null;
        $columns = count($grid[0]);
        $pos = 0;
        foreach ($parts as $part) {
            if ($part[0] === '') {
                continue;
            }
            if ($in_range_error) {
                throw new \RuntimeException($in_range_error);
            }

            if ($part['d'] && $part['d'] !== '_') {
                $d = $part['d'];
                while ($d && $d[0] === 'z') {
                    $pos += $this->skipZ ? 25 : 26;
                    $d = substr($d, 1);
                }
                if ($d) {
                    $pos += ord($d[0]) - 96;
                }
            }

            $col = $pos % $columns;
            $row = ($pos - $col) / $columns;

            if (!array_key_exists($col, $grid[$row] ?? [])) {
                $in_range_error = "Missing cell: {$row}x{$col}";
                continue;
            }

            $grid[$row][$col] = $this->valueCallback ? ($this->valueCallback)($part['n']) : +$part['n'];
            $pos++;
        }

        return $grid;
    }
}
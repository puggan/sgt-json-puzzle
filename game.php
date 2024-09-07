<?php

use Puggan\SgtJsonPuzzle\Game;

require __DIR__ . '/vendor/autoload.php';

try {
    $result = (static function (?string $gameName) {

        if (empty($gameName)) {
            throw new \Exception('Missing parameter game');
        }

        /** @var class-string<Game> $className */
        $className = '\\Puggan\\SgtJsonPuzzle\\' . $gameName;
        if (!class_exists($className)) {
            throw new \Exception('Unknown game: ' . $gameName);
        }

        $game = new $className($_GET);

        return $game->startState();
    })(PHP_SAPI === 'cli' ? ($argv[1] ?? null) : ($_GET['game'] ?? null));

    header('Content-Type: application/json');
    exit(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
} catch (\Throwable $e) {
    if (PHP_SAPI === 'cli') {
        echo $e;
        exit(1);
    }
    header('HTTP/1.0 500 Bad Request');
    exit(json_encode(['error' => $e->getMessage(), 'trace' => $e->getTrace()]));
}

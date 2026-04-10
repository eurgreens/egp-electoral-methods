<?php

namespace App\Utils;

use Illuminate\Support\Collection;

class Election
{
    protected array $ballots;

    protected array $candidates;

    public function __construct(protected string $method = 'borda', protected int $toElect = 8, protected bool $test = false)
    {
        $this->loadBallots();
        $this->loadCandidates();
    }

    public function loadBallots(): self
    {
        $folder = $this->test ? 'sample-ballots' : 'ballots';
        try {
            $ballots = file_get_contents(storage_path('app/' . $folder . '/' . $this->method . '.csv'));
        } catch (\Exception $e) {
            throw new \Exception("Error loading ballots file for method '{$this->method}': " . $e->getMessage());
        }

        $ballots = explode("\n", $ballots);
        $this->ballots = array_map(function ($ballot) {
            return explode(',', $ballot);
        }, $ballots);

        return $this;
    }

    public function loadCandidates(): self
    {
        try {
            $candidates = file_get_contents(storage_path('app/candidates.csv'));
        } catch (\Exception $e) {
            throw new \Exception('Error loading candidates file: ' . $e->getMessage());
        }

        $candidates = explode("\n", $candidates);
        foreach ($candidates as $candidate) {
            [$name, $gender] = explode(' - ', $candidate);
            $this->candidates[$candidate] = [
                'name' => trim($name),
                'gender' => trim($gender),
                'votes' => 0,
            ];
        }

        return $this;
    }

    public function getBallots(): array
    {
        return $this->ballots;
    }

    public function getCandidates(): array
    {
        return $this->candidates;
    }

    public function getResults(): array
    {
        $results = collect($this->candidates)
            ->sortByDesc(function (array $candidate): float|int {
                return $candidate['sort_value'] ?? $candidate['votes'];
            })
            ->values()
            ->map(function ($candidate, $i) {
                return [
                    'position' => $i + 1,
                    'name' => $candidate['name'],
                    'gender' => $candidate['gender'],
                    'votes' => $candidate['votes'],
                    'elected' => $i < $this->toElect ? '✅' : '',
                    'comments' => $candidate['comments'] ?? '',
                ];
            });

        $results = $this->applyGenderBalance($results);
        $results = $this->checkTies($results);

        return $results->map(function ($candidate) {
            return [
                $candidate['position'],
                $candidate['name'],
                $candidate['gender'],
                $candidate['votes'],
                $candidate['elected'],
                $candidate['comments'],
            ];
        })->toArray();
    }

    private function applyGenderBalance(Collection $results): Collection
    {
        // Apply gender corrections
        $electedCandidates = $results->take($this->toElect);
        $maleCount = $electedCandidates->where('gender', 'male')->count();
        $femaleCount = $electedCandidates->where('gender', 'female')->count();

        if ($maleCount > $femaleCount) {
            $swapsNeeded = intdiv($maleCount - $femaleCount + 1, 2);

            $replacementFemaleIndexes = $results
                ->slice($this->toElect)
                ->filter(function ($candidate) {
                    return $candidate['gender'] === 'female';
                })
                ->take($swapsNeeded)
                ->keys()
                ->all();

            $displacedMaleIndexes = $results
                ->take($this->toElect)
                ->reverse()
                ->filter(function ($candidate) {
                    return $candidate['gender'] === 'male';
                })
                ->take(count($replacementFemaleIndexes))
                ->keys()
                ->all();

            $results = $results->map(function ($candidate, $index) use ($replacementFemaleIndexes, $displacedMaleIndexes) {
                if (in_array($index, $replacementFemaleIndexes, true)) {
                    $candidate['elected'] = '✅';
                    $candidate['comments'] = trim(($candidate['comments'] !== '' ? $candidate['comments'] . ' ' : '') . 'Elected due to gender balance');
                }

                if (in_array($index, $displacedMaleIndexes, true)) {
                    $candidate['elected'] = '❌';
                    $candidate['comments'] = trim(($candidate['comments'] !== '' ? $candidate['comments'] . ' ' : '') . 'Not elected due to gender balance');
                }

                return $candidate;
            });
        }

        return $results;
    }

    private function checkTies(Collection $results): Collection
    {
        // Check for ties at the last elected position
        $lastElectedVotes = $results->where('position', $this->toElect)->first()['votes'];
        $tiedCandidates = $results->filter(function ($candidate) use ($lastElectedVotes) {
            return $candidate['votes'] === $lastElectedVotes;
        });

        if ($tiedCandidates->count() > 1) {
            $tiedNames = $tiedCandidates->pluck('name')->join(', ');
            $results = $results->map(function ($candidate) use ($tiedNames, $lastElectedVotes) {
                if ($candidate['votes'] === $lastElectedVotes) {
                    $candidate['comments'] = trim($candidate['comments'] . " Tied with {$tiedNames}");
                }

                return $candidate;
            });
        }

        return $results;
    }
}

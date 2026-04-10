<?php

namespace App\Utils;

use InvalidArgumentException;

class IRVElection extends Election
{
    public function __construct(protected int $toElect = 8, protected bool $test = false)
    {
        $this->method = 'irv';
        $this->loadBallots();
        $this->loadCandidates();
    }

    public function count(): self
    {
        $candidateNames = array_keys($this->candidates);
        $candidateIndexes = array_flip($candidateNames);
        $ballots = $this->normaliseBallots($candidateIndexes);
        $remainingCandidates = $candidateNames;
        $electedMaleCandidates = 0;
        $maleCandidateLimit = intdiv($this->toElect, 2);
        $seatsToFill = min($this->toElect, count($remainingCandidates));

        for ($seat = 1; $seat <= $seatsToFill; $seat++) {
            $femaleOnlyRound = $electedMaleCandidates >= $maleCandidateLimit;
            $eligibleCandidates = $femaleOnlyRound
                ? array_values(array_filter(
                    $remainingCandidates,
                    fn(string $candidateName): bool => $this->candidates[$candidateName]['gender'] === 'female'
                ))
                : $remainingCandidates;

            if ($eligibleCandidates === []) {
                break;
            }

            [$winner, $winnerTally, $roundCount] = $this->runSingleWinnerIrv($ballots, $eligibleCandidates, $candidateIndexes);
            $remainingCandidates = array_values(array_filter(
                $remainingCandidates,
                fn(string $candidateName): bool => $candidateName !== $winner
            ));

            $genderBalanceNote = $femaleOnlyRound
                ? ' Female-only round required after reaching the male-seat limit.'
                : '';

            $this->candidates[$winner]['votes'] = round($winnerTally, 4);
            $this->candidates[$winner]['sort_value'] = (1000 - $seat) + $winnerTally;
            $this->candidates[$winner]['comments'] = sprintf(
                'Elected for seat %d after %d IRV round%s with %.4f votes.%s',
                $seat,
                $roundCount,
                $roundCount === 1 ? '' : 's',
                $winnerTally,
                $genderBalanceNote
            );

            if ($this->candidates[$winner]['gender'] === 'male') {
                $electedMaleCandidates++;
            }
        }

        foreach ($candidateNames as $candidateName) {
            $this->candidates[$candidateName]['votes'] = round((float) $this->candidates[$candidateName]['votes'], 4);
            $this->candidates[$candidateName]['sort_value'] = $this->candidates[$candidateName]['sort_value']
                ?? $this->candidates[$candidateName]['votes'];
            $this->candidates[$candidateName]['comments'] = trim((string) ($this->candidates[$candidateName]['comments'] ?? ''));
        }

        return $this;
    }

    /**
     * @param  array<string, int>  $candidateIndexes
     * @return array<int, array<int, string>>
     */
    private function normaliseBallots(array $candidateIndexes): array
    {
        $ballots = [];

        foreach ($this->ballots as $ballot) {
            $preferences = [];

            foreach ($ballot as $candidateName) {
                $candidateName = trim($candidateName);

                if ($candidateName === '') {
                    continue;
                }

                if (! array_key_exists($candidateName, $candidateIndexes)) {
                    throw new InvalidArgumentException("Candidate '{$candidateName}' not found in the candidates list.");
                }

                if (! in_array($candidateName, $preferences, true)) {
                    $preferences[] = $candidateName;
                }
            }

            if ($preferences !== []) {
                $ballots[] = $preferences;
            }
        }

        return $ballots;
    }

    /**
     * @param  array<int, array<int, string>>  $ballots
     * @param  array<int, string>  $eligibleCandidates
     * @param  array<string, int>  $candidateIndexes
     * @return array{0: string, 1: float, 2: int}
     */
    private function runSingleWinnerIrv(array $ballots, array $eligibleCandidates, array $candidateIndexes): array
    {
        $runningCandidates = array_fill_keys($eligibleCandidates, true);
        $firstPreferenceVotes = $this->tallyCurrentPreferences($ballots, array_keys($runningCandidates));
        $lastTallies = $firstPreferenceVotes;
        $roundCount = 0;

        while (count($runningCandidates) > 1) {
            $roundCount++;
            $candidateNames = array_keys($runningCandidates);
            $tallies = $this->tallyCurrentPreferences($ballots, $candidateNames);
            $lastTallies = $tallies;
            $totalVotes = array_sum($tallies);
            $majorityCandidates = array_values(array_filter(
                $candidateNames,
                static fn(string $candidateName): bool => $totalVotes > 0 && $tallies[$candidateName] > ($totalVotes / 2)
            ));

            if ($majorityCandidates !== []) {
                usort($majorityCandidates, function (string $left, string $right) use ($tallies, $firstPreferenceVotes, $candidateIndexes): int {
                    return ($tallies[$right] <=> $tallies[$left])
                        ?: ($firstPreferenceVotes[$right] <=> $firstPreferenceVotes[$left])
                        ?: ($candidateIndexes[$left] <=> $candidateIndexes[$right]);
                });

                $winner = $majorityCandidates[0];

                return [$winner, $tallies[$winner], $roundCount];
            }

            usort($candidateNames, function (string $left, string $right) use ($tallies, $firstPreferenceVotes, $candidateIndexes): int {
                return ($tallies[$left] <=> $tallies[$right])
                    ?: ($firstPreferenceVotes[$left] <=> $firstPreferenceVotes[$right])
                    ?: ($candidateIndexes[$right] <=> $candidateIndexes[$left]);
            });

            unset($runningCandidates[$candidateNames[0]]);
        }

        /** @var string $winner */
        $winner = array_key_first($runningCandidates);

        return [$winner, $lastTallies[$winner] ?? 0.0, max($roundCount, 1)];
    }

    /**
     * @param  array<int, array<int, string>>  $ballots
     * @param  array<int, string>  $runningCandidates
     * @return array<string, float>
     */
    private function tallyCurrentPreferences(array $ballots, array $runningCandidates): array
    {
        $tallies = array_fill_keys($runningCandidates, 0.0);
        $runningLookup = array_fill_keys($runningCandidates, true);

        foreach ($ballots as $ballot) {
            foreach ($ballot as $candidateName) {
                if (! array_key_exists($candidateName, $runningLookup)) {
                    continue;
                }

                $tallies[$candidateName] += 1.0;

                break;
            }
        }

        return $tallies;
    }
}

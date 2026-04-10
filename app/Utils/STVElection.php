<?php

namespace App\Utils;

use InvalidArgumentException;

class STVElection extends Election
{
    protected string $method;

    protected array $roundSummaries = [];

    public function __construct(protected int $toElect = 8, protected bool $test = false)
    {
        $this->method = 'stv';
        $this->loadBallots();
        $this->loadCandidates();
    }

    public function count(): self
    {
        $candidateNames = array_keys($this->candidates);
        $candidateIndexes = array_flip($candidateNames);
        $ballots = $this->normaliseBallots($candidateIndexes);
        $this->roundSummaries = [];

        if ($ballots === []) {
            return $this;
        }

        $quota = $this->calculateQuota(count($ballots));
        $statuses = array_fill_keys($candidateNames, 'running');
        $firstPreferenceVotes = array_fill_keys($candidateNames, 0.0);
        $weightedBallots = array_map(
            static fn (array $ballot): array => ['preferences' => $ballot, 'weight' => 1.0],
            $ballots
        );

        foreach ($ballots as $ballot) {
            $firstPreferenceVotes[$ballot[0]] += 1.0;
        }

        $electedCount = 0;
        $electionOrder = 0;
        $round = 0;

        while ($electedCount < min($this->toElect, count($candidateNames))) {
            $runningCandidates = array_values(array_filter(
                $candidateNames,
                static fn (string $candidateName): bool => ($statuses[$candidateName] ?? null) === 'running'
            ));

            if ($runningCandidates === []) {
                break;
            }

            $eligibleCandidates = $this->eligibleCandidatesForRound($runningCandidates, $statuses);

            if ($eligibleCandidates === []) {
                break;
            }

            $round++;
            $femaleOnlyRound = count($eligibleCandidates) !== count($runningCandidates);
            $restrictionReason = $femaleOnlyRound
                ? 'Only female candidates are eligible this round to preserve gender balance.'
                : null;
            [$tallies, $assignments] = $this->tallyBallots($weightedBallots, $statuses, $eligibleCandidates);
            $seatsRemaining = $this->toElect - $electedCount;

            $qualifiedCandidates = array_values(array_filter(
                $eligibleCandidates,
                static fn (string $candidateName): bool => $quota <= $tallies[$candidateName] + 1e-9
            ));

            if ($qualifiedCandidates !== []) {
                usort($qualifiedCandidates, function (string $left, string $right) use ($tallies, $firstPreferenceVotes, $candidateIndexes): int {
                    return ($tallies[$right] <=> $tallies[$left])
                        ?: ($firstPreferenceVotes[$right] <=> $firstPreferenceVotes[$left])
                        ?: ($candidateIndexes[$left] <=> $candidateIndexes[$right]);
                });

                $candidateName = $qualifiedCandidates[0];
                $statuses[$candidateName] = 'elected';
                $electedCount++;
                $electionOrder++;

                $currentTally = $tallies[$candidateName];
                $surplus = max($currentTally - $quota, 0.0);
                $transferRatio = $currentTally > 0 ? $surplus / $currentTally : 0.0;
                $genderBalanceNote = $femaleOnlyRound
                    ? ' Female-only round required to preserve gender balance.'
                    : '';
                $reason = sprintf(
                    '%s is elected with %.4f votes after reaching the quota of %.4f. Surplus %.4f transfers at a ratio of %.4f.%s',
                    $this->candidates[$candidateName]['name'],
                    $currentTally,
                    $quota,
                    $surplus,
                    $transferRatio,
                    $genderBalanceNote
                );

                $this->recordRound(
                    round: $round,
                    quota: $quota,
                    seatsRemaining: $seatsRemaining,
                    tallies: $tallies,
                    eligibleCandidates: $eligibleCandidates,
                    action: 'elected',
                    candidateName: $candidateName,
                    reason: $reason,
                    restrictionReason: $restrictionReason,
                );

                $this->recordCandidateOutcome(
                    candidateName: $candidateName,
                    tally: $currentTally,
                    order: $electionOrder,
                    elected: true,
                    comment: sprintf(
                        'Elected in round %d with %.4f votes (quota %.4f).%s',
                        $round,
                        $currentTally,
                        $quota,
                        $genderBalanceNote
                    )
                );

                foreach ($assignments[$candidateName] ?? [] as $ballotIndex) {
                    $weightedBallots[$ballotIndex]['weight'] *= $transferRatio;
                }

                continue;
            }

            if (count($eligibleCandidates) <= $seatsRemaining) {
                usort($eligibleCandidates, function (string $left, string $right) use ($tallies, $firstPreferenceVotes, $candidateIndexes): int {
                    return ($tallies[$right] <=> $tallies[$left])
                        ?: ($firstPreferenceVotes[$right] <=> $firstPreferenceVotes[$left])
                        ?: ($candidateIndexes[$left] <=> $candidateIndexes[$right]);
                });

                $candidateName = $eligibleCandidates[0];
                $statuses[$candidateName] = 'elected';
                $electedCount++;
                $electionOrder++;

                $currentTally = $tallies[$candidateName];
                $genderBalanceNote = $femaleOnlyRound
                    ? ' Female-only round required to preserve gender balance.'
                    : '';
                $reason = sprintf(
                    '%s is elected with %.4f votes because only %d eligible candidate(s) remain for %d seat(s).%s',
                    $this->candidates[$candidateName]['name'],
                    $currentTally,
                    count($eligibleCandidates),
                    $seatsRemaining,
                    $genderBalanceNote
                );

                $this->recordRound(
                    round: $round,
                    quota: $quota,
                    seatsRemaining: $seatsRemaining,
                    tallies: $tallies,
                    eligibleCandidates: $eligibleCandidates,
                    action: 'elected',
                    candidateName: $candidateName,
                    reason: $reason,
                    restrictionReason: $restrictionReason,
                );

                $this->recordCandidateOutcome(
                    candidateName: $candidateName,
                    tally: $currentTally,
                    order: $electionOrder,
                    elected: true,
                    comment: sprintf(
                        'Elected in round %d as one of the remaining eligible candidates with %.4f votes.%s',
                        $round,
                        $currentTally,
                        $genderBalanceNote
                    )
                );

                foreach ($assignments[$candidateName] ?? [] as $ballotIndex) {
                    $weightedBallots[$ballotIndex]['weight'] = 0.0;
                }

                continue;
            }

            usort($eligibleCandidates, function (string $left, string $right) use ($tallies, $firstPreferenceVotes, $candidateIndexes): int {
                return ($tallies[$left] <=> $tallies[$right])
                    ?: ($firstPreferenceVotes[$left] <=> $firstPreferenceVotes[$right])
                    ?: ($candidateIndexes[$right] <=> $candidateIndexes[$left]);
            });

            $candidateName = $eligibleCandidates[0];
            $statuses[$candidateName] = 'eliminated';
            $genderBalanceNote = $femaleOnlyRound
                ? ' Female-only round required to preserve gender balance.'
                : '';
            $reason = sprintf(
                '%s is eliminated with %.4f votes because they have the lowest tally among the eligible candidates.%s',
                $this->candidates[$candidateName]['name'],
                $tallies[$candidateName],
                $genderBalanceNote
            );

            $this->recordRound(
                round: $round,
                quota: $quota,
                seatsRemaining: $seatsRemaining,
                tallies: $tallies,
                eligibleCandidates: $eligibleCandidates,
                action: 'eliminated',
                candidateName: $candidateName,
                reason: $reason,
                restrictionReason: $restrictionReason,
            );

            $this->recordCandidateOutcome(
                candidateName: $candidateName,
                tally: $tallies[$candidateName],
                order: 0,
                elected: false,
                comment: sprintf(
                    'Eliminated in round %d with %.4f votes because this was the lowest eligible tally.%s',
                    $round,
                    $tallies[$candidateName],
                    $genderBalanceNote
                )
            );
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
     * @return array<int, array<string, mixed>>
     */
    public function getRoundSummaries(): array
    {
        return $this->roundSummaries;
    }

    /**
     * @param  array<string, float>  $tallies
     * @param  array<int, string>  $eligibleCandidates
     */
    private function recordRound(
        int $round,
        float $quota,
        int $seatsRemaining,
        array $tallies,
        array $eligibleCandidates,
        string $action,
        string $candidateName,
        string $reason,
        ?string $restrictionReason,
    ): void {
        usort($eligibleCandidates, function (string $left, string $right) use ($tallies): int {
            return ($tallies[$right] <=> $tallies[$left])
                ?: strcmp($this->candidates[$left]['name'], $this->candidates[$right]['name']);
        });

        $this->roundSummaries[] = [
            'round' => $round,
            'quota' => round($quota, 4),
            'seats_remaining' => $seatsRemaining,
            'action' => $action,
            'candidate' => $this->candidates[$candidateName]['name'],
            'reason' => $reason,
            'restriction_reason' => $restrictionReason,
            'tallies' => array_map(function (string $eligibleCandidate) use ($tallies): array {
                return [
                    'name' => $this->candidates[$eligibleCandidate]['name'],
                    'gender' => $this->candidates[$eligibleCandidate]['gender'],
                    'votes' => round($tallies[$eligibleCandidate], 4),
                ];
            }, $eligibleCandidates),
        ];
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

    private function calculateQuota(int $ballotCount): float
    {
        return (float) (intdiv($ballotCount, $this->toElect + 1) + 1);
    }

    /**
     * @param  array<int, string>  $runningCandidates
     * @param  array<string, string>  $statuses
     * @return array<int, string>
     */
    private function eligibleCandidatesForRound(array $runningCandidates, array $statuses): array
    {
        $maleLimit = intdiv($this->toElect, 2);
        $requiredFemales = $this->toElect - $maleLimit;
        $runningFemaleCandidates = array_values(array_filter(
            $runningCandidates,
            fn (string $candidateName): bool => $this->candidates[$candidateName]['gender'] === 'female'
        ));
        $electedMales = $this->countElectedCandidatesByGender($statuses, 'male');
        $electedFemales = $this->countElectedCandidatesByGender($statuses, 'female');
        $femaleSeatsStillNeeded = max($requiredFemales - $electedFemales, 0);

        if ($femaleSeatsStillNeeded > 0 && ($maleLimit <= $electedMales || count($runningFemaleCandidates) <= $femaleSeatsStillNeeded)) {
            return $runningFemaleCandidates;
        }

        return $runningCandidates;
    }

    /**
     * @param  array<string, string>  $statuses
     */
    private function countElectedCandidatesByGender(array $statuses, string $gender): int
    {
        return count(array_filter(
            array_keys($statuses),
            fn (string $candidateName): bool => $statuses[$candidateName] === 'elected'
                && $this->candidates[$candidateName]['gender'] === $gender
        ));
    }

    /**
     * @param  array<int, array{preferences: array<int, string>, weight: float}>  $weightedBallots
     * @param  array<string, string>  $statuses
     * @param  array<int, string>|null  $eligibleCandidates
     * @return array{0: array<string, float>, 1: array<string, array<int, int>>}
     */
    private function tallyBallots(array $weightedBallots, array $statuses, ?array $eligibleCandidates = null): array
    {
        $candidateNames = array_keys($statuses);
        $tallies = array_fill_keys($candidateNames, 0.0);
        $assignments = array_fill_keys($candidateNames, []);
        $eligibleLookup = $eligibleCandidates === null ? null : array_fill_keys($eligibleCandidates, true);

        foreach ($weightedBallots as $ballotIndex => $ballot) {
            if ($ballot['weight'] <= 0.0) {
                continue;
            }

            foreach ($ballot['preferences'] as $candidateName) {
                if (($statuses[$candidateName] ?? null) !== 'running') {
                    continue;
                }

                if ($eligibleLookup !== null && ! array_key_exists($candidateName, $eligibleLookup)) {
                    continue;
                }

                $tallies[$candidateName] += $ballot['weight'];
                $assignments[$candidateName][] = $ballotIndex;

                break;
            }
        }

        return [$tallies, $assignments];
    }

    private function recordCandidateOutcome(string $candidateName, float $tally, int $order, bool $elected, string $comment): void
    {
        $this->candidates[$candidateName]['votes'] = round($tally, 4);
        $this->candidates[$candidateName]['sort_value'] = $elected
            ? (1000 - $order) + $tally
            : $tally;
        $this->candidates[$candidateName]['comments'] = $comment;
    }
}

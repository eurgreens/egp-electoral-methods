<?php

namespace App\Utils;

use InvalidArgumentException;

class MajorityJudgementElection extends Election
{
    /**
     * @var array<string, int>
     */
    private const GRADE_VALUES = [
        'Poor' => 1,
        'Acceptable' => 2,
        'Good' => 3,
        'Excellent' => 4,
    ];

    protected string $method;

    public function __construct(protected int $toElect = 8)
    {
        $this->method = 'mj';
        $this->loadBallots();
        $this->loadCandidates();
    }

    public function addMajorityJudgementPoints(): self
    {
        $candidateNames = array_keys($this->candidates);
        $gradesByCandidate = array_fill_keys($candidateNames, []);

        foreach ($this->ballots as $ballot) {
            foreach ($candidateNames as $index => $candidateName) {
                $grade = trim($ballot[$index] ?? '');

                if ($grade === '') {
                    continue;
                }

                if (! array_key_exists($grade, self::GRADE_VALUES)) {
                    throw new InvalidArgumentException("Invalid Majority Judgement grade '{$grade}' for candidate '{$candidateName}'.");
                }

                $gradesByCandidate[$candidateName][] = self::GRADE_VALUES[$grade];
            }
        }

        foreach ($gradesByCandidate as $candidateName => $grades) {
            if ($grades === []) {
                continue;
            }

            sort($grades);

            $medianValue = $this->medianValue($grades);
            $higherGrades = count(array_filter($grades, fn(int $grade): bool => $grade > $medianValue));
            $lowerGrades = count(array_filter($grades, fn(int $grade): bool => $grade < $medianValue));
            $averageValue = array_sum($grades) / count($grades);

            $this->candidates[$candidateName]['votes'] = round(
                $medianValue + (($higherGrades - $lowerGrades) / (count($grades) * 100)) + ($averageValue / 1000),
                4
            );
        }

        return $this;
    }

    /**
     * @param  array<int, int>  $grades
     */
    private function medianValue(array $grades): int
    {
        $middleIndex = intdiv(count($grades) - 1, 2);

        return $grades[$middleIndex];
    }
}

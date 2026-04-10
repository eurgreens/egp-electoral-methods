<?php

namespace App\Utils;

class MNTVElection extends Election
{
    protected string $method;

    public function __construct(protected int $toElect = 8)
    {
        $this->method = 'mntv';
        $this->loadBallots();
        $this->loadCandidates();
    }

    public function count(): self
    {
        // Add points to candidates
        foreach ($this->ballots as $ballot) {
            foreach ($ballot as $candidateName) {
                if (isset($this->candidates[$candidateName])) {
                    $this->candidates[$candidateName]['votes'] += 1;
                } else {
                    throw new \Exception("Candidate '$candidateName' not found in candidates list.");
                }
            }
        }

        return $this;
    }
}

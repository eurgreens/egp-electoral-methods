<?php

namespace App\Utils;

class DowdallElection extends Election
{
    protected string $method;

    public function __construct(protected int $toElect = 8)
    {
        $this->method = 'dowdall';
        $this->loadBallots();
        $this->loadCandidates();
    }

    public function addDowdallPoints(): self
    {
        // Add points to candidates based on their rank in each ballot
        foreach ($this->ballots as $ballot) {
            foreach ($ballot as $rank => $candidateName) {
                if (isset($this->candidates[$candidateName])) {
                    $this->candidates[$candidateName]['votes'] += 1 / ($rank + 1);
                } else {
                    throw new \Exception("Candidate '$candidateName' not found in candidates list.");
                }
            }
        }

        return $this;
    }
}

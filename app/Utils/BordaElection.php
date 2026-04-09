<?php

namespace App\Utils;

class BordaElection extends Election
{
    public function __construct(protected int $toElect = 8)
    {
        $this->method = 'borda';
        $this->loadBallots();
        $this->loadCandidates();
    }

    public function addBordaPoints(): self
    {
        // Add points to candidates based on their rank in each ballot
        foreach ($this->ballots as $ballot) {
            $ballot = array_reverse($ballot);
            foreach ($ballot as $rank => $candidate) {
                if (isset($this->candidates[$candidate])) {
                    $this->candidates[$candidate]['votes'] += $rank + 1;
                } else {
                    throw new \Exception("Candidate '$candidate' not found in candidates list.");
                }
            }
        }

        return $this;
    }
}

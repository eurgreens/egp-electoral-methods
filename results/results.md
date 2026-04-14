# General notes

- For this simulation, we used the votes as submitted for each voting method.
- However, it must be noted that some rankings varied greatly between the methods, which suggests that some voters may have misunderstood the simulation goal.
- Therefore, the results from each method cannot be easily compared to each other, and we should not draw conclusions about the relative performance of the methods based on these results.
- Due to its "rigidness" or ease of use, we can use the Majority Judgement results as a control for true preference of candidates, and we can compare the results of the other methods to it to see how well they reflect voters' preferences.
- Two more simulations were run: one excluding votes that did not match the Majority Judgement results, and another one only using the STV ballots to make comparison easier.

# Single Transferable Vote

+----+-------------+--------+---------+---------+--------------------------------------------------------------------------------------+
| # | Candidate | Gender | Points | Elected | Comments |
+----+-------------+--------+---------+---------+--------------------------------------------------------------------------------------+
| 1 | Belgium | female | 36 | ✅ | Elected in round 1 with 36.0000 votes (quota 14.0000). |
| 2 | Denmark | female | 18 | ✅ | Elected in round 2 with 18.0000 votes (quota 14.0000). |
| 3 | Germany | male | 19.9034 | ✅ | Elected in round 12 with 19.9034 votes (quota 14.0000). |
| 4 | Cyprus | male | 19.0424 | ✅ | Elected in round 15 with 19.0424 votes (quota 14.0000). |
| 5 | Portugal | female | 17.6774 | ✅ | Elected in round 13 with 17.6774 votes (quota 14.0000). |
| 6 | Croatia | male | 15.3333 | ✅ | Elected in round 3 with 15.3333 votes (quota 14.0000). |
| 7 | Italy | female | 17.1305 | ✅ | Elected in round 16 with 17.1305 votes (quota 14.0000). |
| 8 | Lithuania | female | 17.1464 | ✅ | Elected in round 17 with 17.1464 votes (quota 14.0000). |
| 9 | UK | male | 9.0533 | | Eliminated in round 14 with 9.0533 votes because this was the lowest eligible tally. |
| 10 | France | male | 6.4836 | | Eliminated in round 11 with 6.4836 votes because this was the lowest eligible tally. |
| 11 | Finland | female | 4.679 | | Eliminated in round 10 with 4.6790 votes because this was the lowest eligible tally. |
| 12 | Netherlands | male | 2.4444 | | Eliminated in round 9 with 2.4444 votes because this was the lowest eligible tally. |
| 13 | Latvia | male | 2 | | Eliminated in round 8 with 2.0000 votes because this was the lowest eligible tally. |
| 14 | Ukraine | male | 2 | | Eliminated in round 7 with 2.0000 votes because this was the lowest eligible tally. |
| 15 | Moldova | female | 1 | | Eliminated in round 6 with 1.0000 votes because this was the lowest eligible tally. |
| 16 | Poland | male | 0 | | Eliminated in round 5 with 0.0000 votes because this was the lowest eligible tally. |
| 17 | Sweden | male | 0 | | Eliminated in round 4 with 0.0000 votes because this was the lowest eligible tally. |
+----+-------------+--------+---------+---------+--------------------------------------------------------------------------------------+

## Notes

- Droop quota was 14 votes (117/(8+1)+1)
- No gender corrections were needed
- There were two tied rounds (in this simulations ties were resolved randomly). We would need to establish a tie-breaking rule.

# Instant Runoff Voting

+----+-------------+--------+--------+---------+-------------------------------------------------------------------------------------------------------------------------+
| # | Candidate | Gender | Points | Elected | Comments |
+----+-------------+--------+--------+---------+-------------------------------------------------------------------------------------------------------------------------+
| 1 | Germany | male | 68 | ✅ | Elected for seat 7 after 10 IRV rounds with 68 votes. |
| 2 | Belgium | female | 60 | ✅ | Elected for seat 1 after 14 IRV rounds with 60 votes. |
| 3 | Croatia | male | 60 | ✅ | Elected for seat 2 after 13 IRV rounds with 60 votes. |
| 4 | Cyprus | male | 59 | ✅ | Elected for seat 3 after 14 IRV rounds with 59 votes. |
| 5 | Finland | female | 61 | ✅ | Elected for seat 5 after 12 IRV rounds with 61 votes. |
| 6 | Denmark | female | 59 | ✅ | Elected for seat 4 after 13 IRV rounds with 59 votes. |
| 7 | France | male | 59 | ✅ | Elected for seat 6 after 11 IRV rounds with 59 votes. |
| 8 | Italy | female | 53 | ✅ | Elected for seat 8 after 1 IRV round with 53 votes. Female-only round required after reaching the male-seat limit. |
| 9 | Latvia | male | 0 | | |
| 10 | Moldova | female | 0 | | |
| 11 | Lithuania | female | 0 | | |
| 12 | Netherlands | male | 0 | | |
| 13 | Poland | male | 0 | | |
| 14 | Portugal | female | 0 | | |
| 15 | Sweden | male | 0 | | |
| 16 | UK | male | 0 | | |
| 17 | Ukraine | male | 0 | | |
+----+-------------+--------+--------+---------+-------------------------------------------------------------------------------------------------------------------------+

## Notes

- One gender correction was needed in the last round
- There were no ties, but we would need to establish a tie-breaking rule in case of ties.
- Counting stops when all seats are filled, so the last 9 candidates have 0 votes. This is a feature of IRV, but it means that we don't have information about voters' preferences for these candidates.

# Modified Borda Count

+----+-------------+--------+--------+---------+----------+
| # | Candidate | Gender | Points | Elected | Comments |
+----+-------------+--------+--------+---------+----------+
| 1 | Belgium | female | 1006 | ✅ | |
| 2 | Denmark | female | 913 | ✅ | |
| 3 | Croatia | male | 815 | ✅ | |
| 4 | Italy | female | 734 | ✅ | |
| 5 | Finland | female | 708 | ✅ | |
| 6 | France | male | 708 | ✅ | |
| 7 | Germany | male | 680 | ✅ | |
| 8 | Portugal | female | 635 | ✅ | |
| 9 | Lithuania | female | 634 | | |
| 10 | Cyprus | male | 596 | | |
| 11 | UK | male | 467 | | |
| 12 | Netherlands | male | 461 | | |
| 13 | Latvia | male | 433 | | |
| 14 | Moldova | female | 416 | | |
| 15 | Poland | male | 348 | | |
| 16 | Sweden | male | 242 | | |
| 17 | Ukraine | male | 207 | | |
+----+-------------+--------+--------+---------+----------+

# Notes

- No gender corrections were needed
- Average candidates ranked was X, which means that most people did not use their points to their full advantage. More education on how to use the voting system could help voters to express their preferences more effectively.
- There were no ties, but we would need to establish a tie-breaking rule in case of ties (e.g. if two candidates have the same number of points, the one with more first-place votes wins).

Dowdall Count
+----+-------------+--------+-----------------+---------+----------+
| # | Candidate | Gender | Points | Elected | Comments |
+----+-------------+--------+-----------------+---------+----------+
| 1 | Belgium | female | 62.276923076923 | ✅ | |
| 2 | Croatia | male | 29.872946334711 | ✅ | |
| 3 | Denmark | female | 25.717893217893 | ✅ | |
| 4 | France | male | 25.219047619048 | ✅ | |
| 5 | Germany | male | 21.935594389271 | ✅ | |
| 6 | Portugal | female | 21.662180874681 | ✅ | |
| 7 | Italy | female | 20.303363303363 | ✅ | |
| 8 | Lithuania | female | 17.843409368409 | ✅ | |
| 9 | Finland | female | 17.638347763348 | | |
| 10 | Cyprus | male | 17.486421911422 | | |
| 11 | Netherlands | male | 15.067625838214 | | |
| 12 | UK | male | 13.756067951656 | | |
| 13 | Ukraine | male | 10.699556489262 | | |
| 14 | Latvia | male | 9.9273462648463 | | |
| 15 | Moldova | female | 7.8792142498025 | | |
| 16 | Sweden | male | 7.7247171945701 | | |
| 17 | Poland | male | 6.8925421800422 | | |
+----+-------------+--------+-----------------+---------+----------+

# Notes

- No gender corrections were needed
- Ties are very unlikely in this system, but we would need to establish a tie-breaking rule in case of ties (e.g. if two candidates have the same number of points, the one with more first-place votes wins).

Majority Judgement
+----+-------------+--------+--------+---------+----------+
| # | Candidate | Gender | Points | Elected | Comments |
+----+-------------+--------+--------+---------+----------+
| 1 | Denmark | female | 3.0067 | ✅ | |
| 2 | Belgium | female | 3.0051 | ✅ | |
| 3 | Moldova | female | 3.0049 | ✅ | |
| 4 | Portugal | female | 3.0045 | ✅ | |
| 5 | Germany | male | 3.0022 | ✅ | |
| 6 | Finland | female | 3.0021 | ✅ | |
| 7 | Italy | female | 3.0021 | ✅ | |
| 8 | Croatia | male | 3.0017 | ✅ | |
| 9 | UK | male | 3.0005 | | |
| 10 | France | male | 3.0003 | | |
| 11 | Latvia | male | 3.0001 | | |
| 12 | Poland | male | 2.006 | | |
| 13 | Netherlands | male | 2.0055 | | |
| 14 | Sweden | male | 2.0043 | | |
| 15 | Lithuania | female | 2.0039 | | |
| 16 | Cyprus | male | 2.0032 | | |
| 17 | Ukraine | male | 2.0032 | | |
+----+-------------+--------+--------+---------+----------+

# Notes

- No gender corrections were needed.
- Ties are very unlikely in this system, but we would need to establish a tie-breaking rule in case of ties

MNTV
+----+-------------+--------+--------+---------+----------+
| # | Candidate | Gender | Points | Elected | Comments |
+----+-------------+--------+--------+---------+----------+
| 1 | Denmark | female | 98 | ✅ | |
| 2 | Belgium | female | 82 | ✅ | |
| 3 | Finland | female | 71 | ✅ | |
| 4 | Portugal | female | 69 | ✅ | |
| 5 | Germany | male | 64 | ✅ | |
| 6 | Italy | female | 64 | ✅ | |
| 7 | France | male | 62 | ✅ | |
| 8 | Lithuania | female | 59 | ✅ | |
| 9 | UK | male | 50 | | |
| 10 | Poland | male | 46 | | |
| 11 | Croatia | male | 43 | | |
| 12 | Sweden | male | 33 | | |
| 13 | Cyprus | male | 32 | | |
| 14 | Moldova | female | 31 | | |
| 15 | Netherlands | male | 26 | | |
| 16 | Ukraine | male | 23 | | |
| 17 | Latvia | male | 22 | | |
+----+-------------+--------+--------+---------+----------+

# Notes

- No gender corrections needed
- No ties, but we would need to establish a tie-breaking rule in case of ties (e.g. running a tie-breaking vote between the tied candidates)

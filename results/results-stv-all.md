# General notes
- For this simulation, STV, IRV, Borda and Dowdall were rerun using the STV ballots.
- This was done to better compare the outcome of each system with the same input of ballots.

# Single Transferable Vote

+----+-------------+--------+---------+---------+--------------------------------------------------------------------------------------+
| #  | Candidate   | Gender | Points  | Elected | Comments                                                                             |
+----+-------------+--------+---------+---------+--------------------------------------------------------------------------------------+
| 1  | Belgium     | female | 36      | ✅      | Elected in round 1 with 36.0000 votes (quota 14.0000).                               |
| 2  | Denmark     | female | 18      | ✅      | Elected in round 2 with 18.0000 votes (quota 14.0000).                               |
| 3  | Germany     | male   | 19.9034 | ✅      | Elected in round 12 with 19.9034 votes (quota 14.0000).                              |
| 4  | Cyprus      | male   | 19.0424 | ✅      | Elected in round 15 with 19.0424 votes (quota 14.0000).                              |
| 5  | Portugal    | female | 17.6774 | ✅      | Elected in round 13 with 17.6774 votes (quota 14.0000).                              |
| 6  | Croatia     | male   | 15.3333 | ✅      | Elected in round 3 with 15.3333 votes (quota 14.0000).                               |
| 7  | Italy       | female | 17.1305 | ✅      | Elected in round 16 with 17.1305 votes (quota 14.0000).                              |
| 8  | Lithuania   | female | 17.1464 | ✅      | Elected in round 17 with 17.1464 votes (quota 14.0000).                              |
| 9  | UK          | male   | 9.0533  |         | Eliminated in round 14 with 9.0533 votes because this was the lowest eligible tally. |
| 10 | France      | male   | 6.4836  |         | Eliminated in round 11 with 6.4836 votes because this was the lowest eligible tally. |
| 11 | Finland     | female | 4.679   |         | Eliminated in round 10 with 4.6790 votes because this was the lowest eligible tally. |
| 12 | Netherlands | male   | 2.4444  |         | Eliminated in round 9 with 2.4444 votes because this was the lowest eligible tally.  |
| 13 | Latvia      | male   | 2       |         | Eliminated in round 8 with 2.0000 votes because this was the lowest eligible tally.  |
| 14 | Ukraine     | male   | 2       |         | Eliminated in round 7 with 2.0000 votes because this was the lowest eligible tally.  |
| 15 | Moldova     | female | 1       |         | Eliminated in round 6 with 1.0000 votes because this was the lowest eligible tally.  |
| 16 | Poland      | male   | 0       |         | Eliminated in round 5 with 0.0000 votes because this was the lowest eligible tally.  |
| 17 | Sweden      | male   | 0       |         | Eliminated in round 4 with 0.0000 votes because this was the lowest eligible tally.  |
+----+-------------+--------+---------+---------+--------------------------------------------------------------------------------------+

# Instant-Runoff Voting
+----+-------------+--------+--------+---------+------------------------------------------------------------+
| #  | Candidate   | Gender | Points | Elected | Comments                                                   |
+----+-------------+--------+--------+---------+------------------------------------------------------------+
| 1  | Finland     | female | 75     | ✅      | Elected for seat 7 after 10 IRV rounds with 75.0000 votes. |
| 2  | Denmark     | female | 69     | ✅      | Elected for seat 3 after 14 IRV rounds with 69.0000 votes. |
| 3  | Croatia     | male   | 67     | ✅      | Elected for seat 2 after 15 IRV rounds with 67.0000 votes. |
| 4  | Belgium     | female | 59     | ✅      | Elected for seat 1 after 15 IRV rounds with 59.0000 votes. |
| 5  | Germany     | male   | 58     | ✅      | Elected for seat 4 after 13 IRV rounds with 58.0000 votes. |
| 6  | Portugal    | female | 59     | ✅      | Elected for seat 5 after 12 IRV rounds with 59.0000 votes. |
| 7  | France      | male   | 61     | ✅      | Elected for seat 8 after 9 IRV rounds with 61.0000 votes.  |
| 8  | Cyprus      | male   | 55     | ✅      | Elected for seat 6 after 11 IRV rounds with 55.0000 votes. |
| 9  | Italy       | female | 0      |         |                                                            |
| 10 | Latvia      | male   | 0      |         |                                                            |
| 11 | Moldova     | female | 0      |         |                                                            |
| 12 | Lithuania   | female | 0      |         |                                                            |
| 13 | Netherlands | male   | 0      |         |                                                            |
| 14 | Poland      | male   | 0      |         |                                                            |
| 15 | Sweden      | male   | 0      |         |                                                            |
| 16 | UK          | male   | 0      |         |                                                            |
| 17 | Ukraine     | male   | 0      |         |                                                            |
+----+-------------+--------+--------+---------+------------------------------------------------------------+

# Modified Borda Count

+----+-------------+--------+--------+---------+----------+
| #  | Candidate   | Gender | Points | Elected | Comments |
+----+-------------+--------+--------+---------+----------+
| 1  | Belgium     | female | 1253   | ✅      |          |
| 2  | Denmark     | female | 1175   | ✅      |          |
| 3  | Croatia     | male   | 979    | ✅      |          |
| 4  | Italy       | female | 949    | ✅      |          |
| 5  | Germany     | male   | 912    | ✅      |          |
| 6  | Finland     | female | 886    | ✅      |          |
| 7  | France      | male   | 883    | ✅      |          |
| 8  | Cyprus      | male   | 847    | ✅      |          |
| 9  | Portugal    | female | 792    |         |          |
| 10 | Lithuania   | female | 762    |         |          |
| 11 | Latvia      | male   | 637    |         |          |
| 12 | UK          | male   | 591    |         |          |
| 13 | Netherlands | male   | 580    |         |          |
| 14 | Moldova     | female | 528    |         |          |
| 15 | Poland      | male   | 456    |         |          |
| 16 | Sweden      | male   | 376    |         |          |
| 17 | Ukraine     | male   | 274    |         |          |
+----+-------------+--------+--------+---------+----------+

# Dowdall Count

+----+-------------+--------+-----------------+---------+----------+
| #  | Candidate   | Gender | Points          | Elected | Comments |
+----+-------------+--------+-----------------+---------+----------+
| 1  | Belgium     | female | 58.00231990232  | ✅      |          |
| 2  | Denmark     | female | 37.078277278277 | ✅      |          |
| 3  | Germany     | male   | 29.343371742636 | ✅      |          |
| 4  | Croatia     | male   | 28.715755486344 | ✅      |          |
| 5  | Portugal    | female | 25.65288045288  | ✅      |          |
| 6  | Italy       | female | 25.583513708514 | ✅      |          |
| 7  | Lithuania   | female | 22.936806494159 | ✅      |          |
| 8  | France      | male   | 22.474501315678 | ✅      |          |
| 9  | Cyprus      | male   | 22.054645354645 |         |          |
| 10 | UK          | male   | 20.55696329814  |         |          |
| 11 | Finland     | female | 18.806457431457 |         |          |
| 12 | Moldova     | female | 12.612776520865 |         |          |
| 13 | Latvia      | male   | 12.175561203502 |         |          |
| 14 | Netherlands | male   | 11.640644078144 |         |          |
| 15 | Poland      | male   | 9.5843424549307 |         |          |
| 16 | Ukraine     | male   | 9.2806112188465 |         |          |
| 17 | Sweden      | male   | 8.2552369199428 |         |          |
+----+-------------+--------+-----------------+---------+----------+
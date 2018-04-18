#Grant Miller homework 8

USE jobs;

#Activity 1
SELECT DISTINCT statecode FROM employer;

#Activity 2
SELECT employer.companyname, \
		employer.division,\
		employer.statecode, \
		interview.salaryoffered FROM employer INNER JOIN interview ON \
		employer.companyname = interview.companyname \
		AND employer.division = interview.division \
		GROUP BY employer.companyname, employer.division;

#Activity 3
SELECT state.statecode, state.description FROM state LEFT JOIN employer ON employer.statecode = state.statecode WHERE employer.statecode IS NULL;

#Activity 4
SELECT DISTINCT companyname, minhrsoffered FROM interview;

#Activity 5
SELECT statecode, description FROM state WHERE SUBSTR(description, 3, 1) LIKE "a" \
	OR SUBSTR(description, 3, 1) LIKE "e" \
	OR SUBSTR(description, 3, 1) LIKE "i" \
	OR SUBSTR(description, 3, 1) LIKE "o" \
	OR SUBSTR(description, 3, 1) LIKE "u";

#Activity 6
SELECT quarter.qtrcode, quarter.location, state.description FROM quarter, state WHERE quarter.location = state.statecode;

#Activity 7
SELECT state.description, employer.companyname FROM state LEFT JOIN employer ON employer.statecode = state.statecode;
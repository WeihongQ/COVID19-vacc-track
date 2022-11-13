SET GLOBAL local_infile = true;
SHOW GLOBAL VARIABLES LIKE 'local_infile';


-- load student
LOAD DATA LOCAL INFILE
	'/Users/colinzang/Desktop/taskC/student.csv' INTO TABLE student
    FIELDS TERMINATED BY ","
    LINES TERMINATED BY '\n'
    IGNORE 1 LINES;
SELECT * FROM student;

-- load teacher table
LOAD DATA LOCAL INFILE
	'/Users/colinzang/Desktop/taskC/teacher.csv' INTO TABLE teacher
    FIELDS TERMINATED BY ","
    LINES TERMINATED BY '\r\n'
    IGNORE 1 LINES;
SELECT * FROM teacher;

-- load health_worker table
LOAD DATA LOCAL INFILE
	'/Users/colinzang/Desktop/taskC/health_worker.csv' INTO TABLE health_worker
    FIELDS TERMINATED BY ","
    LINES TERMINATED BY '\r\n'
    IGNORE 1 LINES;
SELECT * FROM health_worker;


-- load grades table
LOAD DATA LOCAL INFILE
'/Users/colinzang/Desktop/taskC/grades.csv' INTO TABLE grades
    FIELDS TERMINATED BY ","
    LINES TERMINATED BY '\r\n'
    IGNORE 1 LINES;
SELECT * FROM grades;

LOAD DATA LOCAL INFILE '/Users/colinzang/Desktop/taskC/vaccination_records.csv' INTO TABLE vaccination_records
    FIELDS TERMINATED BY ","
    LINES TERMINATED BY '\r\n'
    IGNORE 1 LINES;

SELECT * FROM vaccination_records;
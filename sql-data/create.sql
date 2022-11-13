--  milestone3 for CSC461
--  Erqian Xu, Yongyi Zang, Weihong Qi
DROP DATABASE IF EXISTS `schoolDashboard`;

CREATE DATABASE `schoolDashboard`;

USE `schoolDashboard`;

create student table CREATE TABLE `student` (
  realname CHAR(20),
  studentID CHAR(20),
  username CHAR(20),
  password CHAR(255),
  sex CHAR(10),
  age INT,
  teacherID CHAR(20),
  health_workerID CHAR(20)
);

set
  studentID as the PK
ALTER TABLE
  `schoolDashboard`.`student` CHANGE COLUMN `studentID` `studentID` CHAR(10) NOT NULL,
ADD
  PRIMARY KEY (`studentID`);

CREATE TABLE `teacher` (
  realname CHAR(20),
  username CHAR(20),
  teacherID CHAR(20),
  password CHAR(255)
);

set
  teacherID as the PK
ALTER TABLE
  `schoolDashboard`.`teacher` CHANGE COLUMN `teacherID` `teacherID` CHAR(10) NOT NULL,
ADD
  PRIMARY KEY (`teacherID`);

create the health_worker table CREATE TABLE `health_worker` (
  realname CHAR(20),
  username CHAR(20),
  health_workerID CHAR(20),
  password CHAR(255)
);

set
  health_workerID as the PK
ALTER TABLE
  `schoolDashboard`.`health_worker` CHANGE COLUMN `health_workerID` `health_workerID` CHAR(20) NOT NULL,
ADD
  PRIMARY KEY (`health_workerID`);

;

CREATE TABLE `grades` (
  studentID CHAR(20),
  teacherID CHAR(20),
  gradeReportID CHAR(20),
  first_grade INT,
  second_grade INT,
  final_grade INT
);

ALTER TABLE
  `schoolDashboard`.`grades` CHANGE COLUMN `studentID` `studentID` CHAR(20) NULL,
  CHANGE COLUMN `gradeReportID` `gradeReportID` CHAR(20) NOT NULL,
ADD
  PRIMARY KEY (`gradeReportID`);

;

CREATE TABLE `vaccination_records`(
  studentID CHAR(20),
  health_workerID CHAR (20),
  first_vaccine CHAR(10),
  second_vaccine CHAR(10),
  third_vaccine CHAR(10),
  first_vaccine_date DATE,
  second_vaccine_date DATE,
  third_vaccine_date DATE,
  recordID CHAR(20)
);

ALTER TABLE
  `schoolDashboard`.`vaccination_records` CHANGE COLUMN `recordID` `recordID` CHAR(20) NOT NULL,
ADD
  PRIMARY KEY (`recordID`);

;

--   NOTE: load all the data before create foreign keys
--   create foreign keys
ALTER TABLE
  `schoolDashboard`.`student`
ADD
  INDEX `teacherID_idx` (`teacherID` ASC) VISIBLE;

;

ALTER TABLE
  `schoolDashboard`.`student`
ADD
  CONSTRAINT `teacherID` FOREIGN KEY (`teacherID`) REFERENCES `schoolDashboard`.`teacher` (`teacherID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE
  `schoolDashboard`.`student`
ADD
  INDEX `health_workerID_idx` (`health_workerID` ASC) VISIBLE;

;

ALTER TABLE
  `schoolDashboard`.`student`
ADD
  CONSTRAINT `health_workerID` FOREIGN KEY (`health_workerID`) REFERENCES `schoolDashboard`.`health_worker` (`health_workerID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE
  `schoolDashboard`.`grades`
ADD
  INDEX `view_grade_idx` (`studentID` ASC) VISIBLE,
ADD
  INDEX `edit_grade_idx` (`teacherID` ASC) VISIBLE;

;

ALTER TABLE
  `schoolDashboard`.`grades`
ADD
  CONSTRAINT `view_grade` FOREIGN KEY (`studentID`) REFERENCES `schoolDashboard`.`student` (`studentID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD
  CONSTRAINT `edit_grade` FOREIGN KEY (`teacherID`) REFERENCES `schoolDashboard`.`teacher` (`teacherID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

SET
  sql_mode = '';

ALTER TABLE
  `schoolDashboard`.`vaccination_records`
ADD
  CONSTRAINT `view_vacc` FOREIGN KEY (`studentID`) REFERENCES `schoolDashboard`.`student` (`studentID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE
  `schoolDashboard`.`vaccination_records`
ADD
  INDEX `edit_vacc_idx` (`health_workerID` ASC) VISIBLE;

;

ALTER TABLE
  `schoolDashboard`.`vaccination_records`
ADD
  CONSTRAINT `edit_vacc` FOREIGN KEY (`health_workerID`) REFERENCES `schoolDashboard`.`health_worker` (`health_workerID`) ON DELETE NO ACTION ON UPDATE NO ACTION;
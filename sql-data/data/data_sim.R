#csc 461 milestone 3
#dataset simulation
rm(list=ls())
setwd("/Users/weihong/Desktop/data science/CSC461 Database/milestone3")

library(tidyr)
library(digest)
library(lubridate)
set.seed(666)

###################
##table: teacher##
###################
teacher <- read.table("randomNames.txt", header = F, skip=201, nrows = 10, sep=",")
colnames(teacher)[1] = 'realname'

#username
set.seed(1)
teacher$u1 <- sample(letters, size = 10, replace = T)
set.seed(2)
teacher$u2 <- sample(letters, size = 10, replace = T)
set.seed(3)
teacher$u3 <- sample(letters, size = 10, replace = T)
teacher$u4 <- sample(100:999, size = 10, replace = F)
username <- unite(teacher, 'username', u1, u2, u3, u4, sep='')
teacher <- cbind(teacher, username[,2])
teacher <- teacher[-c(2:5)]
colnames(teacher)[2] <- 'username'

#teacher ID starting with number 5
teacher$teacherID <- sample(500:599, size=10, replace = F)

#password
set.seed(4)
teacher$p1 <- sample(letters, size=10, replace=T)
set.seed(5)
teacher$p2 <- sample(letters, size=10, replace=T)
set.seed(6)
teacher$p3 <- sample(letters, size=10, replace=T)
teacher$p4 <- rep('csc261')

password <- unite(teacher, 'password', p1, p2, p3, p4, sep = '')

teacher <- cbind(teacher, password[,4])
teacher <- teacher[-c(4:7)]
colnames(teacher)[4] <- 'password'

teacher$password <- sapply(teacher$password, digest, algo='md5')

write.csv(teacher, file='teacher.csv')

###################
##table: health worker##
###################
health_worker <- read.table('randomNames.txt', header = F, skip = 301, nrows = 10, sep = ',')
colnames(health_worker)[1] <- 'realname'

#username
set.seed(11)
health_worker$u1 <- sample(letters, size = 10, replace = T)
set.seed(12)
health_worker$u2 <- sample(letters, size = 10, replace = T)
set.seed(13)
health_worker$u3 <- sample(letters, size = 10, replace = T)
health_worker$u4 <- sample(100:999, size = 10, replace = F)
username <- unite(health_worker, 'username', u1, u2, u3, u4, sep='')
health_worker <- cbind(health_worker, username[,2])
health_worker <- health_worker[-c(2:5)]
colnames(health_worker)[2] <- 'username'

#health_worker ID starting from number 6
health_worker$health_workerID <- sample(600:699, size=10, replace = F)

#password
set.seed(14)
health_worker$p1 <- sample(letters, size=10, replace=T)
set.seed(15)
health_worker$p2 <- sample(letters, size=10, replace=T)
set.seed(16)
health_worker$p3 <- sample(letters, size=10, replace=T)
health_worker$p4 <- rep('csc261')

password <- unite(health_worker, 'password', p1, p2, p3, p4, sep = '')

health_worker <- cbind(health_worker, password[,4])
health_worker <- health_worker[-c(4:7)]
colnames(health_worker)[4] <- 'password'

health_worker$password <- sapply(health_worker$password, digest, algo='md5')
write.csv(health_worker, file='health_worker.csv')

###################
##table: student##
###################

#import real name
student <- read.table("randomNames.txt", head=T, nrows = 200, sep=",")
#simulate student id starting with number 1-4
student$studentID <- sample(100:499, size=200, replace = F)

#username
student$u1 <- sample(letters, size = 200, replace = T)
set.seed(111)
student$u2 <- sample(letters, size = 200, replace = T)
set.seed(222)
student$u3 <- sample(letters, size = 200, replace = T)
set.seed(333)
student$u4 <- sample(100:999, size = 200, replace=FALSE)
username<- unite(student,"username",u1,u2,u3,u4, sep="")
student <- cbind(student, username[,3])
colnames(student)[7] <- 'username'
student <- student[-c(3:6)]

#password
set.seed(111)
student$p1 <- sample(letters, size = 200, replace = T)
set.seed(222)
student$p2 <- sample(letters, size = 200, replace = T)
set.seed(333)
student$p3<- sample(letters, size = 200, replace = T)
student$p4 <- rep('csc261')

password<- unite(student, "password", p1, p2, p3, p4, sep="")
student <- cbind(student, password[,4])
student <- student[-c(4:7)]
colnames(student)[4]<-'password'

student$password <- sapply(student$password, digest, algo="md5")

#sex
sex <- c('F','M')
student$sex <- sample(sex, 200, replace = T)

#age
age <- c(18:25)
student$age <- sample(age,200, replace = T)

#create FK refer to teacher
teacherID <- teacher[,3]
student$teacherID <- sample(teacherID, size=200, replace = T)

#create FK refer to health worker
health_workerID <- health_worker[,3]
student$health_workerID <- sample(health_workerID, size=200, replace=T)

write.csv(student, file="student.csv")

###################
##table: grades##
###################
grades <- data.frame(matrix(ncol=0, nrow = 200))
grades$studentID <- student[,2]
grades$teacherID <- student[,7]


grades$gradeReportID <- sample(1001:2000, size=200, replace=F)
set.seed(99)
grades$first_grade <- rnorm(200, mean = 70, sd=10)
grades$first_grade <- round(grades$first_grade)
set.seed(100)
grades$second_grade <- rnorm(200, mean = 70, sd=10)
grades$second_grade <- round(grades$second_grade)
set.seed(101)
grades$final_grade <- rnorm(200, mean = 70, sd=10)
grades$final_grade <- round(grades$final_grade)

write.csv(grades, file='grades.csv')

###################
##table: vaccination record##
###################
vaccination_records <-data.frame(matrix(ncol=0, nrow = 200))
vaccination_records$studentID <-student[,2]
vaccination_records$health_workerID <- student[,8]

vacc <- c('yes', 'no')
day <- c(1:29)
month <- c(1:12)
year <- c(2020:2022)
#vacc_date <- data.frame(matrix(nrow=200,ncol=0))

vaccination_records$first_vaccine <- sample(vacc, 200, replace = T, prob=c(0.8,0.2))
vaccination_records$second_vaccine <- rep(NA, 200)
vaccination_records$third_vaccine <- rep(NA, 200)
vaccination_records$vd <- rep(NA, 200)
vaccination_records$vm <- rep(NA, 200)
vaccination_records$vy <- rep(NA, 200)


for(i in 1:200){
    if(vaccination_records$first_vaccine[i] == 'yes'){
        vaccination_records$second_vaccine[i] <- sample(vacc,1, replace = T, prob=c(0.8,0.2))
        vaccination_records$vd[i] <- sample(day,1)
        vaccination_records$vm[i] <- sample(month,1)
        vaccination_records$vy[i] <- 2020
    }
    else{
        vaccination_records$second_vaccine[i] <- 'no'
    }
}

first_vaccine_date<- unite(vaccination_records,"first_vaccine_date",vy,vm,vd, sep="-")
first_vaccine_date[,6] <- as.Date(first_vaccine_date[,6])
vaccination_records$first_vaccine_date <-first_vaccine_date[,6]

set.seed(123)
vaccination_records$vd <- rep(NA, 200)
vaccination_records$vm <- rep(NA, 200)
vaccination_records$vy <- rep(NA, 200)
for(i in 1:200){
    if(vaccination_records$second_vaccine[i] == 'yes'){
        vaccination_records$third_vaccine[i] <- sample(vacc,1, replace = T, prob=c(0.8,0.2))
        vaccination_records$vd[i] <- sample(day,1)
        vaccination_records$vm[i] <- sample(month,1)
        vaccination_records$vy[i] <- 2021
    }
    else{
        vaccination_records$third_vaccine[i] <- 'no'
    }
}

second_vaccine_date<- unite(vaccination_records,"second_vaccine_date",vy,vm,vd, sep="-")
second_vaccine_date[,6] <- as.Date(second_vaccine_date[,6])
vaccination_records$second_vaccine_date <-second_vaccine_date[,6]

set.seed(321)
vaccination_records$vd <- rep(NA, 200)
vaccination_records$vm <- rep(NA, 200)
vaccination_records$vy <- rep(NA, 200)
for(i in 1:200){
    if(vaccination_records$third_vaccine[i] == 'yes'){
        vaccination_records$vd[i] <- sample(day,1)
        vaccination_records$vm[i] <- sample(month,1)
        vaccination_records$vy[i] <- 2022
    }
}

third_vaccine_date<- unite(vaccination_records,"third_vaccine_date",vy,vm,vd, sep="-")
third_vaccine_date[,6] <- as.Date(third_vaccine_date[,6])
vaccination_records$third_vaccine_date <-third_vaccine_date[,6]

vaccination_records <- vaccination_records[-c(6:8)]

vaccination_records$recordID <- sample(2000:2999, 200, replace = F)

write.csv(vaccination_records, file = 'vaccination_records.csv')

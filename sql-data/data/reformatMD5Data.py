import os, sys, hashlib

# open student.csv and read the contents into a list of lists
with open('health_worker.csv', 'r') as f:
    student_list = [line.split(',') for line in f]
    


for i in range(len(student_list)):
    if i == 0:
        continue
    item = student_list[i]
    password = item[3]
    md5password = hashlib.md5(password.encode('utf-8')).hexdigest()
    item[4] = md5password
    item[3] = password.split('csc261')[0]

# write back to student.csv
with open('health_worker_with_password.csv', 'w') as f:
    for item in student_list:
        f.write(','.join(item))
        f.write('\n')



with open('health_worker_without_password_pre.csv', 'w') as f:
    for item in student_list:
        # remove the item[3]
        item = item[:3] + item[4:]
        f.write(','.join(item))
        f.write('\n')
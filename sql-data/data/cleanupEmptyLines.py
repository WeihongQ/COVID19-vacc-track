import os, sys

with open("teacher_with_password.csv", "r") as f:
    lines = f.readlines()
    for line in lines:
        if line.strip() == "":
            lines.remove(line)
    with open("teacher_with_password.csv", "w") as f:
        f.writelines(lines)
        f.close()
        print("Done")
        sys.exit(0)
    f.close()
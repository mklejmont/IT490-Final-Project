from mysql.connector import connect
from datetime import datetime
from os import makedirs
import smtplib
conn = connect(host='localhost', user='root', passwd='sql', database='testdb')
cursor = conn.cursor(); 
userfile = "./backups/backup_users_"+datetime.today().strftime("%d_%m_%y")+".txt"
mailfile = "./backups/backup_mailing_list_"+datetime.today().strftime("%d_%m_%y")+".txt"
ratingfile = "./backups/backup_ratings_"+datetime.today().strftime("%d_%m_%y")+".txt"
makedirs("./backups/", exist_ok=True); 
with open(userfile, "w+") as fileopen:
    cursor.execute("SELECT * FROM accounts")
    for (user_id,username, email, pwd_hash,height,weight,time_available,gym_access,exercise_goals,equipment) in cursor:
        fileopen.write(str(user_id)+","+str(username)+","+str(email)+","+str(pwd_hash)+","+str(height)+","+str(weight)+","+str(time_available)+","+str(gym_access)+","+str(exercise_goals)+",'"+str(equipment)+"'\n")

with open(mailfile, "w+") as file:
    cursor.execute("SELECT * FROM push_notifications")
    to=[]
    for (email, phone) in cursor:
        file.write(str(email)+","+str(phone)+"\n")
        #Send emails here
        if to.count(email) == 0:
            to.append(email)
    sender = "jimbo@jimbotron.com"
    subject="Get out and exercise!"
    text="Go to the gym! Now!"
    message = """\
From: %s
To: %s
Subject: %s

%s
""" % (sender, ", ".join(to), subject, text)
    server = smtplib.SMTP('localhost')
    server.sendmail(sender, to, str(message))
    server.quit()


with open(ratingfile, "w+") as file:
    cursor.execute("SELECT * FROM user_ratings")
    for (user, exercise, rating) in cursor:
        file.write(str(user)+","+str(exercise)+","+str(rating)+"\n")
cursor.close()
conn.close(); 

from mysql.connector import connect
from datetime import datetime

conn = connect(host='localhost', user='root', passwd='sql', database='testdb').cursor()

conn.execute("SELECT * FROM accounts")
records = conn.fetchall()
with open("backup_users_"+datetime.today().strftime("%d_%m_%y")+".txt") as file:
    for record in records: 
        file.write(record+"\n")

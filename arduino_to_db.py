import serial
import mysql.connector
from datetime import datetime

# Establish connection to Arduino
ser = serial.Serial('COM5', 9600)  # Change 'COM5' to your actual port

# Establish connection to MySQL database
db = mysql.connector.connect(
    host="localhost",
    user="your_username",
    password="your_password",
    database="smart_irrigation"
)

cursor = db.cursor()

while True:
    data = ser.readline().decode('utf-8').strip()
    print(data)
    
    if "Soil Moisture Level:" in data:
        moisture_level = int(data.split(":")[1].strip())
        status = ""
        if moisture_level >= 1020:
            status = "Sensor not in water"
        elif 180 <= moisture_level <= 300:
            status = "Water moisture is high"
        elif 950 <= moisture_level <= 1000:
            status = "Low moisture"
        
        timestamp = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
        
        # Insert into soil_moisture table
        cursor.execute("INSERT INTO soil_moisture (moisture_level, timestamp) VALUES (%s, %s)",
                       (moisture_level, timestamp))
        db.commit()
        
        # Check for operational status
        if status == "Sensor not in water":
            cursor.execute("INSERT INTO system_status (status, timestamp) VALUES (%s, %s)",
                           ("Not Operational", timestamp))
            db.commit()
        else:
            cursor.execute("INSERT INTO operational_status (status, timestamp) VALUES (%s, %s)",
                           ("Operational", timestamp))
            db.commit()

import mysql.connector
import sense_hat
import time
import datetime

# Connect to the database
conn = mysql.connector.connect(
    host="localhost",
    port="3306",
    user="root",
    password="",
    database="nerdygadgets"
)
cursor = conn.cursor()

# Initialize the Sense HAT
sense = sense_hat.SenseHat()

while True:
    # Get the current temperature
    temperature = sense.get_temperature()

    # Get the current datetime
    recorded_when = datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S")

    # Calculate the ValidTo datetime
    valid_to = (datetime.datetime.now() + datetime.timedelta(seconds=3)).strftime("%Y-%m-%d %H:%M:%S")

    # Insert the temperature into the coldroomtemperatures table
    sql = "INSERT INTO coldroomtemperatures (ColdRoomSensorNumber, RecordedWhen, Temperature, ValidFrom, ValidTo) VALUES (%s, %s, %s, %s, %s)"
    val = (5, recorded_when, temperature, recorded_when, valid_to)
    cursor.execute(sql, val)
    conn.commit()

    # Move the previous record to the coldroomtemperatures_archive table
    sql = "INSERT INTO coldroomtemperatures_archive SELECT * FROM coldroomtemperatures WHERE ColdRoomTemperatureID = (SELECT MAX(ColdRoomTemperatureID) - 1 FROM coldroomtemperatures)"
    cursor.execute(sql)
    conn.commit()

    # Delete the previous record from the coldroomtemperatures table
    sql = "DELETE FROM coldroomtemperatures WHERE ColdRoomTemperatureID = (SELECT MAX(ColdRoomTemperatureID) - 1 FROM coldroomtemperatures)"
    cursor.execute(sql)
    conn.commit()

    # Sleep for 3 seconds
    time.sleep(3)

conn.close()
# MySQL database connection module
from mysql.connector import Connect, Error

def connect():
    conn = Connect(
                host="localhost",
                user="root",
                password="",
                database="attendance")
    return conn

def query(sql, connection):
    csr = connection.cursor(dictionary=True)
    csr.execute(sql)
    result = csr.fetchall()
    csr.close()
    return result

def create_update(sql, connection, perintah=''):
    result = {}
    try:
        csr = connection.cursor()
        csr.execute(sql)
        connection.commit()
        csr.close()
        if perintah == 'create':
            result['rowID'] = csr.lastrowid
    except Error as e:
        print("Error query: ", e)
    finally:
        return result

def delete(sql, connection):
    result = {}
    try:
        csr = connection.cursor()
        csr.execute(sql)
        connection.commit()
        csr.close()
        result['id'] = '3'
    except Error as e:
        print("Error query:", e)
    finally:
        return result
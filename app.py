from flask import Flask, request, jsonify

# from custom modules
from pymodules import connection

app = Flask(__name__)
app.secret_key = "employeeattendance!!!"

# Welcome method
@app.route('/welcome', methods=['GET'])
def welcome():
    return "OK"

"""
CRUD operations for karyawan
"""
# Read all karyawan
@app.route('/karyawan', methods=['GET'])
def employee():
    try: 
        conn = connection.connect()
        query = f"SELECT * FROM karyawan"
        result = {
            'data': connection.query(query, conn),
            'status_code': '200',
            'status': 'Query karyawan success'
        }
    except:
        result = {
            'status_code': '400',
            'status': 'Failed to query data karyawan'
        }

    return jsonify(result)

# Create karyawan
@app.route('/karyawan', methods=['POST'])
def employee_create():
    try:
        request_data = request.get_json()
    except:
        result = {
            'status_code': '400',
            'status': 'JSON data is not found'
        }
        return jsonify(result)

    nama = request_data['nama']
    password = request_data['password']
    
    conn = connection.connect()
    query = f"INSERT INTO karyawan(nama, pass_akun) VALUES('{nama}','{password}')"
    result = connection.create_update(query, conn, perintah='create')
    result['status_code'] = '200'
    result['status'] = 'Success created new karyawan'

    return jsonify(result)

# Update karyawan
@app.route('/karyawan', methods=['PUT'])
def employee_update():
    try:
        request_data = request.get_json()
    except:
        result = {
            'status_code': '400',
            'status': 'JSON data is not found'
        }
        return jsonify(result)

    id_data = request.data['id']
    nama = request_data['nama']
    password = request_data['password']

    conn = connection.connect()
    query = f"UPDATE karyawan SET nama='{nama}', pass_nama='{password}' WHERE id = {id_data}"
    result = connection.create_update(query, conn)
    result['status_code'] = '200'
    result['status'] = 'Update karyawan success'

# Delete karyawan
@app.route('/karyawan', methods=['DELETE'])
def employee_delete():
    try:
        request_data = request.get_json()
    except:
        result = {
            'status_code': '400',
            'status': 'JSON data is not found'
        }
        return jsonify(result)
    
    id_data = request_data['id']

    conn = connection.connect()
    query = f"DELETE FROM karyawan WHERE id = {id_data}"
    result = connection.delete(query, conn)
    result['status_code'] = '200'
    result['status'] = 'Delete karyawan success'

    return jsonify(result)

"""
CRUD operations for check-in check-out with geolocation
"""

"""
Check-in & Check-out attendance
"""

if __name__ == '__main__':
    app.run()
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
        request_data = request.get_json(silent=True)
        nama = request_data['nama']
        password = request_data['password']
    except KeyError:
        result = {
            'status_code': '400',
            'status': "The keys (nama, password) are not found in JSON data."
        }
        return jsonify(result)
    except:
        result = {
            'status_code': '400',
            'status': 'JSON data is not found.'
        }
        return jsonify(result)
    
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
        request_data = request.get_json(silent=True)
        id_data = request_data['id']
        nama = request_data['nama']
        password = request_data['password']
    except KeyError:
        result = {
            'status_code': '400',
            'status': "The keys (id, nama, password) are not found in JSON data."
        }
        return jsonify(result)
    except:
        result = {
            'status_code': '400',
            'status': 'JSON data is not found.'
        }
        return jsonify(result)

    conn = connection.connect()
    query = f"UPDATE karyawan SET nama='{nama}', pass_akun='{password}' WHERE id = {id_data}"
    result = connection.create_update(query, conn)
    result['status_code'] = '200'
    result['status'] = 'Update karyawan success'

    return jsonify(result)

# Delete karyawan
@app.route('/karyawan', methods=['DELETE'])
def employee_delete():
    try:
        request_data = request.get_json(silent=True)
        id_data = request_data['id']
    except KeyError:
        result = {
            'status_code': '400',
            'status': "Key 'id' is not found in JSON data."
        }
        return jsonify(result)
    except:
        result = {
            'status_code': '400',
            'status': 'JSON data is not found.'
        }
        return jsonify(result)

    conn = connection.connect()
    query = f"DELETE FROM karyawan WHERE id = {id_data}"
    result = connection.delete(query, conn)
    result['status_code'] = '200'
    result['status'] = 'Delete karyawan success'

    return jsonify(result)

"""
CRUD operations for admin
"""
# Read all admin
@app.route('/admin', methods=['GET'])
def admin():
    try: 
        conn = connection.connect()
        query = f"SELECT * FROM admin"
        result = {
            'data': connection.query(query, conn),
            'status_code': '200',
            'status': 'Query admin success'
        }
    except:
        result = {
            'status_code': '400',
            'status': 'Failed to query data admin'
        }

    return jsonify(result)

# Create admin
@app.route('/admin', methods=['POST'])
def admin_create():
    try:
        request_data = request.get_json(silent=True)
        nama = request_data['nama']
        password = request_data['password']
    except KeyError:
        result = {
            'status_code': '400',
            'status': "The keys (nama, password) are not found in JSON data."
        }
        return jsonify(result)
    except:
        result = {
            'status_code': '400',
            'status': 'JSON data is not found.'
        }
        return jsonify(result)
    
    conn = connection.connect()
    query = f"INSERT INTO admin(nama, pass_akun) VALUES('{nama}','{password}')"
    result = connection.create_update(query, conn, perintah='create')
    result['status_code'] = '200'
    result['status'] = 'Success created new admin'

    return jsonify(result)

# Update admin
@app.route('/admin', methods=['PUT'])
def admin_update():
    try:
        request_data = request.get_json(silent=True)
        id_data = request_data['id']
        nama = request_data['nama']
        password = request_data['password']
    except KeyError:
        result = {
            'status_code': '400',
            'status': "The keys (id, nama, password) are not found in JSON data."
        }
        return jsonify(result)
    except:
        result = {
            'status_code': '400',
            'status': 'JSON data is not found.'
        }
        return jsonify(result)

    conn = connection.connect()
    query = f"UPDATE admin SET nama='{nama}', pass_akun='{password}' WHERE id = {id_data}"
    result = connection.create_update(query, conn)
    result['status_code'] = '200'
    result['status'] = 'Update admin success'

    return jsonify(result)

# Delete admin
@app.route('/admin', methods=['DELETE'])
def admin_delete():
    try:
        request_data = request.get_json(silent=True)
        id_data = request_data['id']
    except KeyError:
        result = {
            'status_code': '400',
            'status': "Key 'id' is not found in JSON data."
        }
        return jsonify(result)
    except:
        result = {
            'status_code': '400',
            'status': 'JSON data is not found.'
        }
        return jsonify(result)

    conn = connection.connect()
    query = f"DELETE FROM admin WHERE id = {id_data}"
    result = connection.delete(query, conn)
    result['id'] = id_data
    result['status_code'] = '200'
    result['status'] = 'Delete admin success'

    return jsonify(result)

"""
Check-in & Check-out attendance
"""


if __name__ == '__main__':
    app.run()
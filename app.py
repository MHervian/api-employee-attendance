from flask import Flask, request, jsonify
from datetime import datetime

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
        hasil = connection.query(query, conn)

        # Check if there is no result
        if hasil == []:
            hasil = None
        
        result = {
            'data': hasil,
            'status_code': '200',
            'status': 'Query karyawan success'
        }
    except:
        result = {
            'status_code': '400',
            'status': 'Failed to query data karyawan'
        }

    return jsonify(result)

# Get karyawan by id
@app.route('/karyawan/<int:id>', methods=['GET'])
def get_karyawan_by_id(id):
    try:
        conn = connection.connect()
        query = f"SELECT * FROM karyawan WHERE id = {id}"
        result = {
            'data': connection.query(query, conn),
            'status_code': '200',
            'status': f"Query karyawan success by id: {id}"
        }
    except:
        result = {
            'status_code': '400',
            'status': f'Failed to query data karyawan by id: {id}'
        }

    return jsonify(result)

# karyawan login by nama and password
@app.route('/karyawan/login', methods=['POST'])
def get_employee():
    try:
        request_data = request.get_json(silent=True)
        nama = request_data['nama']
        password = request_data['pass_akun']

        conn = connection.connect()
        query = f"SELECT * FROM karyawan WHERE nama = '{nama}' AND pass_akun = '{password}'"
        result = {
            'data': connection.query(query, conn),
            'status_code': '200',
            'status': f"Query karyawan success by name: {nama}"
        }
    except KeyError:
        result = {
            'status_code': '400',
            'status': "The keys (nama, pass_akun) are not found in JSON data."
        }
        return jsonify(result)
    except:
        result = {
            'status_code': '400',
            'status': f'Failed to query data karyawan by name: {nama}'
        }

    return jsonify(result)

# Create karyawan
@app.route('/karyawan', methods=['POST'])
def employee_create():
    try:
        request_data = request.get_json(silent=True)
        nama = request_data['nama']
        pass_akun = request_data['pass_akun']
    except KeyError:
        result = {
            'status_code': '400',
            'status': "The keys (nama, pass_akun) are not found in JSON data."
        }
        return jsonify(result)
    except:
        result = {
            'status_code': '400',
            'status': 'JSON data is not found.'
        }
        return jsonify(result)
    
    try: 
        conn = connection.connect()
        query = f"INSERT INTO karyawan(nama, pass_akun) VALUES('{nama}','{pass_akun}')"
        result = connection.create_update(query, conn, perintah='create')
        result['status_code'] = '200'
        result['status'] = 'Success created new karyawan'
    except:
        result = {
            'status_code': '400',
            'status': 'Failed to insert new karyawan'
        }

    return jsonify(result)

# Update karyawan
@app.route('/karyawan', methods=['PUT'])
def employee_update():
    try:
        request_data = request.get_json(silent=True)
        id_data = request_data['id']
        nama = request_data['nama']
        pass_akun = request_data['pass_akun']
    except KeyError:
        result = {
            'status_code': '400',
            'status': "The keys (id, nama, pass_akun) are not found in JSON data."
        }
        return jsonify(result)
    except:
        result = {
            'status_code': '400',
            'status': 'JSON data is not found.'
        }
        return jsonify(result)

    conn = connection.connect()
    query = f"UPDATE karyawan SET nama='{nama}', pass_akun='{pass_akun}' WHERE id = {id_data}"
    result = connection.create_update(query, conn)
    result['status_code'] = '200'
    result['status'] = 'Update karyawan success'

    return jsonify(result)

# Delete karyawan
@app.route('/karyawan/<int:id_karyawan>', methods=['DELETE'])
def employee_delete(id_karyawan):
    try:
        conn = connection.connect()

        # Delete all absensi by id_karyawan
        query = f"DELETE FROM kehadiran WHERE id_karyawan = {id_karyawan}"
        # print(query)
        result = connection.delete(query, conn)

        # Delete karyawan
        query = f"DELETE FROM karyawan WHERE id = {id_karyawan}"
        # print(query)
        result = connection.delete(query, conn)

        result['status_code'] = '200'
        result['status'] = 'Delete karyawan success'
    except:
        result = {
            'status_code': '400',
            'status': 'Failed to insert absensi hari ini'
        }

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
        hasil = connection.query(query, conn)

        # Check if there is no result
        if hasil == []:
            hasil = None

        result = {
            'data': hasil,
            'status_code': '200',
            'status': 'Query admin success'
        }
    except:
        result = {
            'status_code': '400',
            'status': 'Failed to query data admin'
        }

    return jsonify(result)

# Get admin by id
@app.route('/admin/<int:id_admin>', methods=['GET'])
def get_admin_by_id(id_admin):
    try:
        conn = connection.connect()
        query = f"SELECT * FROM admin WHERE id = {id_admin}"
        result = {
            'data': connection.query(query, conn),
            'status_code': '200',
            'status': f"Query admin success by id {id_admin}"
        }
    except KeyError:
        result = {
            'status_code': '400',
            'status': "The keys (nama, pass_akun) are not found in JSON data."
        }
        return jsonify(result)
    except:
        result = {
            'status_code': '400',
            'status': f'Failed to query data admin by id {id_admin}'
        }

    return jsonify(result)

# admin login by nama and password
@app.route('/admin/login', methods=['POST'])
def get_admin():
    try:
        request_data = request.get_json(silent=True)
        nama = request_data['nama']
        password = request_data['pass_akun']

        conn = connection.connect()
        query = f"SELECT * FROM admin WHERE nama = '{nama}' AND pass_akun = '{password}'"
        result = {
            'data': connection.query(query, conn),
            'status_code': '200',
            'status': f"Query admin success by name: {nama}"
        }
    except KeyError:
        result = {
            'status_code': '400',
            'status': "The keys (nama, pass_akun) are not found in JSON data."
        }
        return jsonify(result)
    except:
        result = {
            'status_code': '400',
            'status': f'Failed to query data admin by name: {nama}'
        }

    return jsonify(result)

# Create admin
@app.route('/admin', methods=['POST'])
def admin_create():
    try:
        request_data = request.get_json(silent=True)
        nama = request_data['nama']
        pass_akun = request_data['pass_akun']
    except KeyError:
        result = {
            'status_code': '400',
            'status': "The keys (nama, pass_akun) are not found in JSON data."
        }
        return jsonify(result)
    except:
        result = {
            'status_code': '400',
            'status': 'JSON data is not found.'
        }
        return jsonify(result)
    
    try: 
        conn = connection.connect()
        query = f"INSERT INTO admin(nama, pass_akun) VALUES('{nama}','{pass_akun}')"
        result = connection.create_update(query, conn, perintah='create')
        result['status_code'] = '200'
        result['status'] = 'Success created new admin'
    except:
        result = {
            'status_code': '400',
            'status': 'Failed to insert new admin'
        }

    return jsonify(result)

# Update admin
@app.route('/admin', methods=['PUT'])
def admin_update():
    try:
        request_data = request.get_json(silent=True)
        id_data = request_data['id']
        nama = request_data['nama']
        pass_akun = request_data['pass_akun']
    except KeyError:
        result = {
            'status_code': '400',
            'status': "The keys (id, nama, pass_akun) are not found in JSON data."
        }
        return jsonify(result)
    except:
        result = {
            'status_code': '400',
            'status': 'JSON data is not found.'
        }
        return jsonify(result)

    conn = connection.connect()
    query = f"UPDATE admin SET nama='{nama}', pass_akun='{pass_akun}' WHERE id = {id_data}"
    result = connection.create_update(query, conn)
    result['status_code'] = '200'
    result['status'] = 'Update admin success'

    return jsonify(result)

# Delete admin
@app.route('/admin/<int:id_admin>', methods=['DELETE'])
def admin_delete(id_admin):
    try: 
        conn = connection.connect()
        query = f"DELETE FROM admin WHERE id = {id_admin}"
        result = connection.delete(query, conn)
        result['id'] = id_admin
        result['status_code'] = '200'
        result['status'] = 'Delete admin success'
    except:
        result = {
            'status_code': '400',
            'status': 'Failed to insert new admin'
        }

    return jsonify(result)

"""
Check-in & Check-out absensi
"""
# Read all absensi
@app.route('/absensi', methods=['GET'])
def kehadiran():
    try:
        conn = connection.connect()
        query = f"SELECT * FROM kehadiran"
        result = {
            'data': connection.query(query, conn),
            'status_code': '200',
            'status': 'Query kehadiran success'
        }
    except:
        result = {
            'status_code': '400',
            'status': 'Failed to query data kehadiran'
        }

    return jsonify(result)

# Read all years of absensi by id_karyawan
@app.route('/absensi/years/<int:id_karyawan>', methods=["GET"])
def get_years(id_karyawan):
    try:
        conn = connection.connect()
        query = f"SELECT YEAR(tanggal_kerja) AS tahun FROM kehadiran WHERE id_karyawan = {id_karyawan} GROUP BY YEAR(tanggal_kerja) DESC"
        result = {
            'years': connection.query(query, conn),
            'status_code': '200',
            'status': 'Query tahun - tahun absensi success'
        }
    except:
        result = {
            'status_code': '400',
            'status': 'Failed to query data tahun - tahun absensi'
        }

    return jsonify(result)

# Read current absensi by karyawan name and today date
@app.route('/absensi/today', methods=['POST'])
def kehadiran_today():
    try:
        request_data = request.get_json(silent=True)
        nama = request_data['nama']
        today = request_data['today']
        
        conn = connection.connect()
        query = f"""SELECT
                    k.clock_in AS clock_in,
                    k.geolocation_in AS geolocation_in,
                    k.clock_out AS clock_out,
                    k.geolocation_out AS geolocation_out,
                    k.tanggal_kerja AS tanggal_kerja
                FROM kehadiran k INNER JOIN karyawan ka ON ka.id = k.id_karyawan
                    WHERE ka.nama = '{nama}' AND k.tanggal_kerja = '{today}'"""
        hasil = connection.query(query, conn)
        
        # Check if there is a data kehadiran
        if hasil != []:
            # Convert datetime object into string
            hasil = hasil[0]
            hasil['clock_in'] = str(hasil['clock_in'])
            hasil['clock_out'] = str(hasil['clock_out'])
            hasil['tanggal_kerja'] = str(hasil['tanggal_kerja'])
        else:
            hasil = None

        result = {
            'data': hasil,
            'status_code': '200',
            'status': 'Query absensi today success'
        }
    except KeyError:
        result = {
            'status_code': '400',
            'status': "The keys (nama, today) are not found in JSON data."
        }
        return jsonify(result)
    except:
        result = {
            'status_code': '400',
            'status': 'Failed to query data kehadiran hari ini'
        }

    return jsonify(result)

# Read absensi based by id_karyawan and tanggal (yyyy-mm)
@app.route('/absensi/<int:id_karyawan>/<string:tanggal>', methods=['GET'])
def kehadiran_monthly(id_karyawan, tanggal):
    try:
        conn = connection.connect()
        query = f"SELECT * FROM kehadiran WHERE id_karyawan = {id_karyawan} AND tanggal_kerja LIKE '%{tanggal}%'"
        hasil = connection.query(query, conn)

        # Check if there are some result
        if hasil != []:
            # convert every datetime object into string
            idx = 0
            for data in hasil:
                hasil[idx]['clock_in'] = str(data['clock_in'])
                hasil[idx]['clock_out'] = str(data['clock_out'])
                hasil[idx]['tanggal_kerja'] = str(data['tanggal_kerja'])
                idx += 1
        else:
            hasil = None

        result = {
            'data': hasil,
            'status_code': '200',
            'status': 'Query kehadiran success'
        }
    except:
        result = {
            'status_code': '400',
            'status': 'Failed to query data kehadiran'
        }

    return jsonify(result)

# Create check_in
@app.route('/absensi/checkin', methods=['POST'])
def check_in():
    try:
        request_data = request.get_json(silent=True)
        id_karyawan = request_data['id_karyawan']
        clock_in = request_data['clock_in']
        geolocation_in = request_data['geolocation_in']
        today = request_data['today']
    except KeyError:
        result = {
            'status_code': '400',
            'status': "The keys (id_karyawan, clock_in, geolocation_in, today) are not found in JSON data."
        }
        return jsonify(result)
    except:
        result = {
            'status_code': '400',
            'status': 'JSON data is not found.'
        }
        return jsonify(result)
    
    try:
        conn = connection.connect()
        query = f"""INSERT INTO kehadiran(id_karyawan, clock_in, geolocation_in, tanggal_kerja) 
                    VALUES({id_karyawan},'{clock_in}', '{geolocation_in}', '{today}')"""
        result = connection.create_update(query, conn, perintah='create')
        result['status_code'] = '200'
        result['status'] = 'Check in'
    except:
        result = {
            'status_code': '400',
            'status': 'Failed to insert absensi hari ini'
        }

    return jsonify(result)

# Update check_out
@app.route('/absensi/checkout', methods=['PUT'])
def checkout():
    try:
        request_data = request.get_json(silent=True)
        id_karyawan = request_data['id_karyawan']
        clock_out = request_data['clock_out']
        geolocation_out = request_data['geolocation_out']
        today = request_data['today']
    except KeyError:
        result = {
            'status_code': '400',
            'status': "The keys (id_karyawan, clock_out, geolocation_out, today) are not found in JSON data."
        }
        return jsonify(result)
    except:
        result = {
            'status_code': '400',
            'status': 'JSON data is not found.'
        }
        return jsonify(result)

    conn = connection.connect()
    query = f"""UPDATE kehadiran SET 
                clock_out='{clock_out}', 
                geolocation_out='{geolocation_out}' 
                WHERE id_karyawan = {id_karyawan} AND tanggal_kerja = '{today}'"""
    result = connection.create_update(query, conn)
    result['status_code'] = '200'
    result['status'] = 'Check out success'

    return jsonify(result)

if __name__ == '__main__':
    app.run(debug=True)
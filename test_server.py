#!/usr/bin/env python3

from flask import Flask, Response
from flask_cors import CORS
import io

app = Flask(__name__)
CORS(app)

@app.route('/')
def home():
    return {"status": "success", "message": "Test Server Running"}

@app.route('/export/kerumunan', methods=['GET'])
def test_export():
    """Test endpoint untuk export tanpa database"""
    try:
        # Buat data dummy untuk test
        test_data = "ID,Halte,Waktu,Jumlah\n1,Halte A,2024-01-01 10:00:00,5\n2,Halte B,2024-01-01 11:00:00,3"
        
        # Buat response CSV sederhana
        return Response(
            test_data,
            mimetype='text/csv',
            headers={"Content-Disposition": "attachment;filename=test_export.csv"}
        )
    except Exception as e:
        return Response(f"Error: {str(e)}", status=500, mimetype='text/plain')

if __name__ == '__main__':
    print("Starting test server...")
    print("Server will be available at: http://localhost:5000")
    print("Export endpoint: http://localhost:5000/export/kerumunan")
    app.run(debug=False, host='0.0.0.0', port=5000, threaded=True)
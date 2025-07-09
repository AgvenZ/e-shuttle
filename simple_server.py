#!/usr/bin/env python3

from flask import Flask
from flask_cors import CORS
from dotenv import load_dotenv
import os

# Load environment variables
load_dotenv()

from config.db_config import connection_pool
from routes.export_routes import export_bp

app = Flask(__name__)
CORS(app)  # Enable CORS for all routes

# Register only export blueprint
app.register_blueprint(export_bp)

@app.route('/')
def home():
    return {"status": "success", "message": "Simple Export Server Running"}

if __name__ == '__main__':
    if connection_pool:
        print("Starting simple export server...")
        print("Server will be available at: http://localhost:5000")
        print("Export endpoint: http://localhost:5000/export/kerumunan")
        app.run(debug=False, host='0.0.0.0', port=5000, threaded=True)
    else:
        print("Failed to initialize database connection pool.")
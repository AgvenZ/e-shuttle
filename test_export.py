#!/usr/bin/env python3

import requests
from repositories.export_repository import ExportRepository
from services.export_service import ExportService

def test_database_data():
    """Test apakah ada data di database"""
    print("=== Testing Database Data ===")
    repo = ExportRepository()
    data, error = repo.get_kerumunan_export_data()
    
    if error:
        print(f"Error: {error}")
        return False
    
    if not data:
        print("Tidak ada data kerumunan di database")
        return False
    
    print(f"Ditemukan {len(data)} record data kerumunan:")
    for i, record in enumerate(data[:3]):  # Show first 3 records
        print(f"  {i+1}. ID: {record['id_kerumunan']}, Halte: {record['nama_halte']}, Waktu: {record['waktu']}, Jumlah: {record['jumlah_kerumunan']}")
    
    return True

def test_export_service():
    """Test export service"""
    print("\n=== Testing Export Service ===")
    service = ExportService()
    file_data, error = service.generate_kerumunan_excel_file()
    
    if error:
        print(f"Error in export service: {error}")
        return False
    
    if not file_data:
        print("No file data generated")
        return False
    
    print(f"Excel file generated successfully, size: {len(file_data)} bytes")
    return True

def test_export_endpoint():
    """Test export endpoint"""
    print("\n=== Testing Export Endpoint ===")
    try:
        response = requests.get('http://localhost:5000/export/kerumunan', timeout=10)
        print(f"Response status: {response.status_code}")
        print(f"Response headers: {dict(response.headers)}")
        
        if response.status_code == 200:
            print(f"Response content length: {len(response.content)} bytes")
            return True
        else:
            print(f"Error response: {response.text}")
            return False
    except Exception as e:
        print(f"Error calling endpoint: {e}")
        return False

if __name__ == "__main__":
    print("Testing Export Functionality...\n")
    
    # Test 1: Database data
    has_data = test_database_data()
    
    # Test 2: Export service (only if we have data)
    if has_data:
        service_works = test_export_service()
        
        # Test 3: Export endpoint (only if service works)
        if service_works:
            endpoint_works = test_export_endpoint()
            
            if endpoint_works:
                print("\n✅ All tests passed! Export functionality should work.")
            else:
                print("\n❌ Endpoint test failed.")
        else:
            print("\n❌ Export service test failed.")
    else:
        print("\n❌ No data in database to export.")
        print("\nSuggestion: Add some test data to the kerumunan table first.")
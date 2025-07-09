from flask import request, Response
from services.export_service import ExportService
from datetime import datetime

export_service = ExportService()

def export_kerumunan_data():
    """Menangani request untuk mengekspor data kerumunan ke format Excel."""
    file_data, error = export_service.generate_kerumunan_excel_file()

    if error:
        return Response(f"Error: {error}", status=400, mimetype='text/plain')

    # Siapkan nama file Excel
    timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
    filename = f"laporan_kerumunan_{timestamp}.xlsx"
    mimetype = "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"

    # Buat response untuk mengunduh file Excel
    return Response(
        file_data,
        mimetype=mimetype,
        headers={"Content-Disposition": f"attachment;filename={filename}"}
    )
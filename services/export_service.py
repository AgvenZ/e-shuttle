from repositories.export_repository import ExportRepository
import pandas as pd
import io

class ExportService:
    def __init__(self):
        self.repository = ExportRepository()

    def generate_kerumunan_excel_file(self):
        """
        Memproses data kerumunan dan menghasilkan file Excel.
        """
        data, error = self.repository.get_kerumunan_export_data()
        if error:
            return None, error
        if not data:
            return None, "Tidak ada data untuk diekspor."

        # Buat DataFrame dari pandas
        df = pd.DataFrame(data)

        # Ubah nama kolom
        df.rename(columns={
            'id_kerumunan': 'ID Laporan',
            'nama_halte': 'Nama Halte',
            'latitude': 'Latitude',
            'longitude': 'Longitude',
            'waktu': 'Waktu Pencatatan',
            'jumlah_kerumunan': 'Jumlah Kerumunan'
        }, inplace=True)

        df = df[['ID Laporan', 'Nama Halte', 'Latitude', 'Longitude', 'Waktu Pencatatan', 'Jumlah Kerumunan']]

        # Format kolom waktu
        if 'Waktu Pencatatan' in df.columns:
            df['Waktu Pencatatan'] = pd.to_datetime(df['Waktu Pencatatan']).dt.strftime('%Y-%m-%d %H:%M:%S')

        # Gunakan io.BytesIO untuk menangani output binary Excel
        output = io.BytesIO()
        
        # Buat file Excel dengan styling
        with pd.ExcelWriter(output, engine='openpyxl') as writer:
            df.to_excel(writer, sheet_name='Data Kerumunan', index=False)
            
            # Dapatkan worksheet untuk styling
            worksheet = writer.sheets['Data Kerumunan']
            
            # Auto-adjust column width
            for column in worksheet.columns:
                max_length = 0
                column_letter = column[0].column_letter
                for cell in column:
                    try:
                        if len(str(cell.value)) > max_length:
                            max_length = len(str(cell.value))
                    except:
                        pass
                adjusted_width = min(max_length + 2, 50)
                worksheet.column_dimensions[column_letter].width = adjusted_width
        
        # Dapatkan nilai binary dari buffer
        output.seek(0)
        return output.getvalue(), None

    def generate_kerumunan_csv_file(self):
        """
        Memproses data kerumunan dan menghasilkan file CSV (untuk backward compatibility).
        """
        data, error = self.repository.get_kerumunan_export_data()
        if error:
            return None, error
        if not data:
            return None, "Tidak ada data untuk diekspor."

        # Buat DataFrame dari pandas
        df = pd.DataFrame(data)

        # Ubah nama kolom
        df.rename(columns={
            'id_kerumunan': 'ID Laporan',
            'nama_halte': 'Nama Halte',
            'latitude': 'Latitude',
            'longitude': 'Longitude',
            'waktu': 'Waktu Pencatatan',
            'jumlah_kerumunan': 'Jumlah Kerumunan'
        }, inplace=True)

        df = df[['ID Laporan', 'Nama Halte', 'Latitude', 'Longitude', 'Waktu Pencatatan', 'Jumlah Kerumunan']]

        # Format kolom waktu
        if 'Waktu Pencatatan' in df.columns:
            df['Waktu Pencatatan'] = pd.to_datetime(df['Waktu Pencatatan']).dt.strftime('%Y-%m-%d %H:%M:%S')

        # Gunakan io.StringIO untuk menangani output string sebagai file
        output = io.StringIO()
        df.to_csv(output, index=False, encoding='utf-8')
        
        # Dapatkan nilai string dari buffer
        return output.getvalue(), None
"""
Mini HTTP Server untuk Inferensi Model Machine Learning (Port 5000).
Menggunakan standard library Python http.server (tidak memerlukan library tambahan).

Jalankan dengan:
    python src/serve.py

Endpoints:
    GET  /health   -> Cek status server
    POST /predict  -> Menerima JSON data karyawan dan mengembalikan estimasi risiko
"""

import sys
import os
import json
from http.server import HTTPServer, BaseHTTPRequestHandler

CURRENT_DIR = os.path.dirname(os.path.abspath(__file__))
if CURRENT_DIR not in sys.path:
    sys.path.insert(0, CURRENT_DIR)

from predict import predict


class AttritionPredictionHandler(BaseHTTPRequestHandler):
    def _set_headers(self, status_code=200):
        self.send_response(status_code)
        self.send_header('Content-Type', 'application/json')
        self.send_header('Access-Control-Allow-Origin', '*')
        self.send_header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')
        self.send_header('Access-Control-Allow-Headers', 'Content-Type')
        self.end_headers()

    def do_OPTIONS(self):
        self._set_headers(204)

    def do_GET(self):
        if self.path == '/health' or self.path == '/':
            self._set_headers(200)
            response = {
                "status": "online",
                "service": "EMS Attrition Prediction API",
                "version": "1.0"
            }
            self.wfile.write(json.dumps(response).encode('utf-8'))
        else:
            self._set_headers(404)
            self.wfile.write(json.dumps({"error": "Endpoint tidak ditemukan"}).encode('utf-8'))

    def do_POST(self):
        if self.path == '/predict':
            content_length = int(self.headers.get('Content-Length', 0))
            post_data = self.rfile.read(content_length)

            try:
                payload = json.loads(post_data.decode('utf-8'))
                result = predict(payload)
                self._set_headers(200)
                self.wfile.write(json.dumps(result).encode('utf-8'))
            except json.JSONDecodeError:
                self._set_headers(400)
                self.wfile.write(json.dumps({"error": "Format JSON tidak valid"}).encode('utf-8'))
            except NotImplementedError as e:
                self._set_headers(501)
                self.wfile.write(json.dumps({"error": str(e)}).encode('utf-8'))
            except FileNotFoundError as e:
                self._set_headers(503)
                self.wfile.write(json.dumps({"error": str(e)}).encode('utf-8'))
            except Exception as e:
                self._set_headers(500)
                self.wfile.write(json.dumps({"error": f"Terjadi kesalahan internal: {str(e)}"}).encode('utf-8'))
        else:
            self._set_headers(404)
            self.wfile.write(json.dumps({"error": "Endpoint tidak ditemukan"}).encode('utf-8'))


def run(server_class=HTTPServer, handler_class=AttritionPredictionHandler, port=5000):
    server_address = ('', port)
    httpd = server_class(server_address, handler_class)
    print("=" * 60)
    print(f"EMS ML Inference Server berjalan di http://localhost:{port}")
    print("Endpoints:")
    print(f"  GET  http://localhost:{port}/health")
    print(f"  POST http://localhost:{port}/predict")
    print("Tekan Ctrl+C untuk menghentikan server.")
    print("=" * 60)
    try:
        httpd.serve_forever()
    except KeyboardInterrupt:
        print("\nServer dihentikan.")
        httpd.server_close()


if __name__ == '__main__':
    run()

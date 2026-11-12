
#!/bin/bash

jq() {
    "/c/Windows/System32/jq.exe.exe" "$@"
}

BASE_URL="http://localhost:8000/api/books"

echo "=================================="
echo "PENGUJIAN OTOMATIS REST API BUKU"
echo "=================================="

# 1. GET semua buku
echo ""
echo "1. GET semua buku"
curl -s "$BASE_URL" | jq .

# 2. POST menambahkan buku
echo ""
echo "2. POST menambahkan buku"

RESPONSE=$(curl -s -X POST "$BASE_URL" \
-H "Content-Type: application/json" \
-d '{
    "judul": "Pengujian Otomatis",
    "penulis": "Alnelka",
    "tahun": 2026,
    "stok": 5
}')

echo "$RESPONSE" | jq .

ID=$(echo "$RESPONSE" | jq -r '.data.id')

# 3. GET buku berdasarkan ID
echo ""
echo "3. GET buku berdasarkan ID"
curl -s "$BASE_URL/$ID" | jq .

# 4. PUT memperbarui buku
echo ""
echo "4. PUT memperbarui buku"

curl -s -X PUT "$BASE_URL/$ID" \
-H "Content-Type: application/json" \
-d '{
    "judul": "Pengujian Otomatis Revisi",
    "penulis": "Alnelka",
    "tahun": 2026,
    "stok": 10
}' | jq .

# 5. DELETE menghapus buku
echo ""
echo "5. DELETE menghapus buku"

curl -s -X DELETE "$BASE_URL/$ID" | jq .

# 6. Menguji validasi data
echo ""
echo "6. Menguji validasi data"

curl -s -X POST "$BASE_URL" \
-H "Content-Type: application/json" \
-d '{
    "judul": "",
    "penulis": "Alnelka",
    "tahun": 2026,
    "stok": 5
}' | jq .

echo ""
echo "=================================="
echo "PENGUJIAN SELESAI"
echo "=================================="

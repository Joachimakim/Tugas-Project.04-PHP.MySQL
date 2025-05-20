<?php
// Mengizinkan CORS untuk development
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Tangani request POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validasi data
    if (empty($data['name']) || empty($data['email']) || empty($data['message'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Harap isi semua field yang diperlukan']);
        exit;
    }
    
    // Simpan ke file (alternatif tanpa database)
    $message = [
        'date' => date('Y-m-d H:i:s'),
        'name' => htmlspecialchars($data['name']),
        'email' => filter_var($data['email'], FILTER_SANITIZE_EMAIL),
        'subject' => isset($data['subject']) ? htmlspecialchars($data['subject']) : 'No Subject',
        'message' => htmlspecialchars($data['message'])
    ];
    
    // Simpan ke file messages.json
    $messages = [];
    if (file_exists('messages.json')) {
        $messages = json_decode(file_get_contents('messages.json'), true);
    }
    $messages[] = $message;
    file_put_contents('messages.json', json_encode($messages, JSON_PRETTY_PRINT));
    
    // Kirim response
    http_response_code(200);
    echo json_encode(['success' => 'Pesan berhasil dikirim!']);
    exit;
}

// Jika bukan POST request
http_response_code(405);
echo json_encode(['error' => 'Method tidak diizinkan']);
?>
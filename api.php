<?php
header('Content-Type: application/json');
require_once 'config.php';

// Hata raporlamayı açalım
error_reporting(E_ALL);
ini_set('display_errors', 1);

// CORS ayarları
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Alışkanlıkları getir
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $stmt = $conn->query("SELECT * FROM habits ORDER BY created_at DESC");
        $habits = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Chain verilerini getir
        foreach ($habits as &$habit) {
            $stmt = $conn->prepare("SELECT date FROM habit_chains WHERE habit_id = ?");
            $stmt->execute([$habit['id']]);
            $chains = $stmt->fetchAll(PDO::FETCH_COLUMN);
            $habit['chain'] = $chains;
        }
        
        echo json_encode($habits);
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}

// Yeni alışkanlık ekle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_GET['action'])) {
    $data = json_decode(file_get_contents('php://input'), true);
    
    try {
        $stmt = $conn->prepare("INSERT INTO habits (name, target_days, created_at) VALUES (?, ?, ?)");
        $stmt->execute([$data['name'], $data['targetDays'], date('Y-m-d H:i:s')]);
        $habitId = $conn->lastInsertId();
        
        $habit = [
            'id' => $habitId,
            'name' => $data['name'],
            'target_days' => $data['targetDays'],
            'chain' => [],
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        echo json_encode($habit);
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}

// Alışkanlık sil
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_GET['id'] ?? null;
    if ($id) {
        try {
            // Önce zincirleri sil
            $stmt = $conn->prepare("DELETE FROM habit_chains WHERE habit_id = ?");
            $stmt->execute([$id]);
            
            // Sonra alışkanlığı sil
            $stmt = $conn->prepare("DELETE FROM habits WHERE id = ?");
            $stmt->execute([$id]);
            
            echo json_encode(['success' => true]);
        } catch(PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}

// Zincir güncelle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'toggle') {
    try {
        // Gelen veriyi yazdıralım
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        
        if (!isset($data['habitId']) || !isset($data['date'])) {
            throw new Exception('Eksik parametreler: habitId veya date');
        }

        // Zincir var mı kontrol et
        $stmt = $conn->prepare("SELECT id FROM habit_chains WHERE habit_id = ? AND date = ?");
        $stmt->execute([$data['habitId'], $data['date']]);
        $exists = $stmt->fetch();
        
        if ($exists) {
            // Zinciri sil
            $stmt = $conn->prepare("DELETE FROM habit_chains WHERE habit_id = ? AND date = ?");
            $stmt->execute([$data['habitId'], $data['date']]);
        } else {
            // Zincir ekle
            $stmt = $conn->prepare("INSERT INTO habit_chains (habit_id, date) VALUES (?, ?)");
            $stmt->execute([$data['habitId'], $data['date']]);
        }
        
        echo json_encode(['success' => true]);
    } catch(Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>

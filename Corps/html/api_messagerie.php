<?php
require __DIR__ . '/../../Includes/config.php';

header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_user'])) {
    echo json_encode(['error' => 'Non connecté']);
    exit;
}

$monId = $_SESSION['id_user']; 
$action = $_GET['action'] ?? '';

// 1. RÉCUPÉRER LES MESSAGES
if ($action === 'get_messages' && isset($_GET['contact_id'])) {
    $contactId = (int)$_GET['contact_id'];

    try {
        // On récupère les messages entre Moi et le Contact
        $sql = "SELECT * FROM messages 
                WHERE (id_expediteur = ? AND id_destinataire = ?) 
                OR (id_expediteur = ? AND id_destinataire = ?) 
                ORDER BY date_envoi ASC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$monId, $contactId, $contactId, $monId]);
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $formatted = [];
        foreach($messages as $msg) {
            $formatted[] = [
                'type' => ($msg['id_expediteur'] == $monId) ? 'envoye' : 'recu',
                'texte' => nl2br(htmlspecialchars($msg['message'])),
                'heure' => date('H:i', strtotime($msg['date_envoi']))
            ];
        }
        
        // Marquer comme "lus"
        $update = $pdo->prepare("UPDATE messages SET est_lu = 1 WHERE id_expediteur = ? AND id_destinataire = ?");
        $update->execute([$contactId, $monId]);

        echo json_encode($formatted);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Erreur SQL: ' . $e->getMessage()]);
    }
    exit;
}

// 2. ENVOYER UN MESSAGE
if ($action === 'send_message') {
    $inputJSON = file_get_contents('php://input');
    $data = json_decode($inputJSON, true);
    
    if (!empty($data['contact_id']) && !empty($data['message'])) {
        try {
            $sql = "INSERT INTO messages (id_expediteur, id_destinataire, message, date_envoi) VALUES (?, ?, ?, NOW())";
            $stmt = $pdo->prepare($sql);
            $success = $stmt->execute([$monId, $data['contact_id'], $data['message']]);
            echo json_encode(['success' => $success]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Données incomplètes']);
    }
    exit;
}
?>
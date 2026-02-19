<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = $_POST['subject'] ?? 'No subject';
    $priority = $_POST['priority'] ?? 'LOW';
    $content = $_POST['content'] ?? '';

    $newTicket = [
        'date' => date('Y-m-d H:i:s'),
        'subject' => $subject,
        'priority' => $priority,
        'content' => $content
    ];

    $file = 'data/tickets_db.json';
    $tickets = [];
    if (file_exists($file)) {
        $content_file = file_get_contents($file);
        $tickets = json_decode($content_file, true) ?? [];
    }
    
    $tickets[] = $newTicket;
    
    file_put_contents($file, json_encode($tickets, JSON_PRETTY_PRINT), LOCK_EX);

    echo "<script>alert('TICKET HAS BEEN FORWARDED TO DINOHH.'); window.location.href='dashboard';</script>";
}
?>
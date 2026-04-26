<?php /** @var array $data */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($data['title']) ?></title>
</head>
<body>
    <h1><?= htmlspecialchars($data['title']) ?></h1>
    <ul>
        <li>APP NAME: <?= htmlspecialchars($data['app_name']) ?></li>
        <li>ORGANIZER: <?= htmlspecialchars($data['organizer']) ?></li>
        <li>APP_ENV: <?= htmlspecialchars($data['app_env']) ?></li>
        <li>APP_DEBUG: <?= htmlspecialchars($data['app_debug']) ?></li>
    </ul>

    <h2>Show List</h2>
    <?php foreach ($data['shows'] as $show): ?>
        <div style="margin-bottom: 16px; padding: 12px; border: 1px solid #ccc;">
            <p><strong>Title:</strong> <?= htmlspecialchars($show['title']) ?></p>
            <p><strong>Artist:</strong> <?= htmlspecialchars($show['artist']) ?></p>
            <p><strong>Date:</strong> <?= htmlspecialchars($show['date']) ?></p>
            <p><strong>Tickets Available:</strong> <?= htmlspecialchars((string) $show['tickets_available']) ?></p>
            <p><strong>Status:</strong> <?= $show['tickets_available'] > 0 ? 'Open' : 'Sold Out' ?></p>
        </div>
    <?php endforeach; ?>

    <h2>API Endpoints</h2>
    <ul>
        <li>GET /shows</li>
        <li>HEAD /shows</li>
        <li>POST /bookings</li>
        <li>OPTIONS /bookings</li>
    </ul>
</body>
</html>
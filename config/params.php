<?php
return [
    'adminEmail' => 'admin@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'api.enableCors' => true,
    'api.bypassOwnershipCheck' => false,
    'api.ownershipProtectedActions' => ['update', 'delete', 'restore'],
    'api.ownershipField' => 'created_by',
    'api.except' => ['index', 'list-all'],
];

<?php
defined('MOODLE_INTERNAL') || die();

$capabilities = [
    'local/studentanalytics:view' => [
        'captype' => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => [
            'manager' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'teacher' => CAP_ALLOW
        ]
    ],
];

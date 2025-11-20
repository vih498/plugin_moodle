<?php
require_once(__DIR__ . '/../../../config.php');
require_login();
require_capability('local/studentanalytics:view', context_system::instance());

global $DB;

// Evita erro caso courseid não seja passado
$courseid = optional_param('courseid', 1, PARAM_INT);

// Logs reais (método correto para buscar eventos por curso)
$sql = "
    SELECT u.id, u.firstname, u.lastname,
           COUNT(l.id) AS total_events
    FROM {user} u
    JOIN {logstore_standard_log} l ON l.userid = u.id
    JOIN {context} ctx ON ctx.id = l.contextid
    WHERE ctx.contextlevel = 50
      AND ctx.instanceid = :courseid
    GROUP BY u.id, u.firstname, u.lastname
    ORDER BY total_events DESC
";
$logs = $DB->get_records_sql($sql, ['courseid' => $courseid]);

// Notas reais
$sql_grades = "
    SELECT gg.userid, AVG(gg.finalgrade) AS avg_grade
    FROM {grade_grades} gg
    JOIN {grade_items} gi ON gi.id = gg.itemid
    WHERE gi.courseid = :courseid
    GROUP BY gg.userid
";
$grades = $DB->get_records_sql($sql_grades, ['courseid' => $courseid]);

$data = [];

foreach ($logs as $user) {

    // Evita erro caso usuário não tenha notas
    $grade = isset($grades[$user->id]) ? (float)$grades[$user->id]->avg_grade : 0;

    $data[] = [
        'userid'    => $user->id,
        'name'      => fullname($user),
        'events'    => (int)$user->total_events,
        'avg_grade' => $grade
    ];
}

echo json_encode($data);
die;
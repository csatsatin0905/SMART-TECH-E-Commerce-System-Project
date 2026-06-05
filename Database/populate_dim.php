<?php
function loadDimUser($pdo)
{
    $sql = "
        SET FOREIGN_KEY_CHECKS = 0;
        TRUNCATE TABLE dim_user;
        SET FOREIGN_KEY_CHECKS = 1;
        INSERT INTO dim_user (user_id, name, created_at)
        SELECT user_id, name, created_at
        FROM users
        ON DUPLICATE KEY UPDATE
            name = VALUES(name)
    ";

    $pdo->exec($sql);
}

function loadDimSubject($pdo)
{
    $sql = "
        SET FOREIGN_KEY_CHECKS = 0;
        TRUNCATE TABLE dim_subject;
        SET FOREIGN_KEY_CHECKS = 1;
        INSERT INTO dim_subject (subject_id, name, semester_id)
        SELECT subject_id, name, semester_id
        FROM subjects
        ON DUPLICATE KEY UPDATE
            name = VALUES(name)
    ";

    $pdo->exec($sql);
}

function loadDimSemester($pdo)
{
    $sql = "
        SET FOREIGN_KEY_CHECKS = 0;
        TRUNCATE TABLE dim_semester;
        SET FOREIGN_KEY_CHECKS = 1;
        INSERT INTO dim_semester (semester_id, semester_name, school_year)
        SELECT semester_id, semester_name, school_year
        FROM semesters
        ON DUPLICATE KEY UPDATE
            semester_name = VALUES(semester_name)
    ";

    $pdo->exec($sql);
}

function loadDimXPReason($pdo)
{

    $reasons = [
        ['TASK_COMPLETE', 'TASK'],
        ['TASK_EARLY_BONUS', 'TASK'],
        ['TASK_OVERDUE_PENALTY', 'TASK'],

        ['STUDY_SESSION', 'STUDY'],

        ['DAILY_GOAL_COMPLETE', 'STUDY'],
        ['DAILY_GOAL_EXCEEDED', 'STUDY'],

        ['STREAK_3_DAYS', 'STREAK'],
        ['STREAK_7_DAYS', 'STREAK'],
        ['STREAK_14_DAYS', 'STREAK'],
        ['STREAK_30_DAYS', 'STREAK'],

        ['TASK_CREATED', 'TASK'],
        ['TASK_DELETED', 'TASK'],

        ['QUIZ_CORRECT_ANSWER', 'QUIZ'],
        ['QUIZ_COMPLETION', 'QUIZ'],
    ];

    $pdo->exec('SET FOREIGN_KEY_CHECKS = 0; TRUNCATE TABLE dim_xp_reason; SET FOREIGN_KEY_CHECKS = 1;');

    foreach ($reasons as $r) {
        $stmt = $pdo->prepare("
            INSERT IGNORE INTO dim_xp_reason
            (reason, category)
            VALUES (?, ?)
        ");

        $stmt->execute([$r[0], $r[1]]);
    }
}
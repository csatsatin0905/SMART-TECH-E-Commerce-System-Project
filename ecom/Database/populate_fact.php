<?php
function loadFactStudySession($pdo)
{
    $sql = "
        SET FOREIGN_KEY_CHECKS = 0;
        TRUNCATE TABLE fact_study_session;
        SET FOREIGN_KEY_CHECKS = 1;
        INSERT INTO fact_study_session (
            user_sk,
            subject_sk,
            semester_sk,
            date_sk,
            start_hour,
            duration_seconds,
            target_duration_minutes,
            pause_seconds,
            status
        )

        SELECT
            du.user_sk,
            ds.subject_sk,
            dsem.semester_sk,
            dd.date_sk,
            HOUR(s.start_time),
            s.actual_duration_seconds,
            s.target_duration_minutes,
            s.total_pause_seconds,
            s.status

        FROM sessions s

        JOIN dim_user du
            ON s.user_id = du.user_id

        JOIN dim_subject ds
            ON s.subject_id = ds.subject_id

        JOIN dim_semester dsem
            ON s.semester_id =
               dsem.semester_id

        JOIN dim_date dd
            ON DATE(s.start_time)
               = dd.full_date
    ";

    $pdo->exec($sql);
}

function loadFactDailyProgress($pdo)
{

    $sql = "
        SET FOREIGN_KEY_CHECKS = 0;
        TRUNCATE TABLE fact_daily_progress;
        SET FOREIGN_KEY_CHECKS = 1;
        INSERT INTO fact_daily_progress (
            user_sk,
            semester_sk,
            date_sk,
            total_minutes
        )
        SELECT
            du.user_sk,
            dsem.semester_sk,
            d.date_sk,
            dp.total_minutes

        FROM daily_progress dp

        JOIN dim_user du ON dp.user_id = du.user_id
        JOIN dim_semester dsem ON dp.semester_id = dsem.semester_id
        JOIN dim_date d ON dp.date = d.full_date

        ON DUPLICATE KEY UPDATE
            total_minutes = VALUES(total_minutes)
    ";

    $pdo->exec($sql);
}

function loadFactQuiz($pdo)
{

    $sql = "
        SET FOREIGN_KEY_CHECKS = 0;
        TRUNCATE TABLE fact_quiz;
        SET FOREIGN_KEY_CHECKS = 1;
        INSERT INTO fact_quiz (
            user_sk,
            subject_sk,
            semester_sk,
            date_sk,
            score,
            total_questions,
            duration_seconds,
            xp_earned
        )
        SELECT
            du.user_sk,
            ds.subject_sk,
            dsem.semester_sk,
            d.date_sk,
            q.score,
            q.total_questions,
            q.duration_taken_seconds,
            q.xp_earned

        FROM quizzes q

        JOIN sessions s ON q.session_id = s.session_id

        JOIN dim_user du ON s.user_id = du.user_id
        JOIN dim_subject ds ON s.subject_id = ds.subject_id
        JOIN dim_semester dsem ON s.semester_id = dsem.semester_id
        JOIN dim_date d ON d.full_date = DATE(q.created_at)

        WHERE q.status = 'completed'
    ";

    $pdo->exec($sql);
}

function loadFactTask($pdo)
{

    $sql = "
        SET FOREIGN_KEY_CHECKS = 0;
        TRUNCATE TABLE fact_task;
        SET FOREIGN_KEY_CHECKS = 1;
        INSERT INTO fact_task (
            user_sk,
            subject_sk,
            semester_sk,
            date_created_sk,
            date_completed_sk,
            priority,
            status,
            estimated_seconds,
            actual_seconds,
            is_late
        )
        SELECT
            du.user_sk,
            ds.subject_sk,
            dsem.semester_sk,
            d1.date_sk,
            d2.date_sk,
            t.priority,
            t.status,
            t.estimated_seconds,
            NULL,

            CASE
                WHEN t.completed_at > t.deadline THEN 1
                ELSE 0
            END

        FROM tasks t

        JOIN dim_user du ON t.user_id = du.user_id
        JOIN dim_subject ds ON t.subject_id = ds.subject_id
        JOIN dim_semester dsem ON t.semester_id = dsem.semester_id

        JOIN dim_date d1 ON d1.full_date = DATE(t.created_at)
        LEFT JOIN dim_date d2 ON d2.full_date = DATE(t.completed_at)

        WHERE t.is_archived = 0;
    ";

    $pdo->exec($sql);
}

function loadFactXP($pdo)
{

    $sql = "
        SET FOREIGN_KEY_CHECKS = 0;
        TRUNCATE TABLE fact_xp;
        SET FOREIGN_KEY_CHECKS = 1;
        INSERT INTO fact_xp (
            user_sk,
            semester_sk,
            date_sk,
            reason_sk,
            xp_change
        )
        SELECT
            du.user_sk,
            dsem.semester_sk,
            d.date_sk,
            dx.reason_sk,
            x.xp_change

        FROM xp_logs x

        JOIN dim_user du ON x.user_id = du.user_id
        JOIN dim_semester dsem ON x.semester_id = dsem.semester_id
        JOIN dim_xp_reason dx ON x.reason = dx.reason
        JOIN dim_date d ON d.full_date = DATE(x.created_at)

        ON DUPLICATE KEY UPDATE
            xp_change = VALUES(xp_change)
    ";

    $pdo->exec($sql);
}
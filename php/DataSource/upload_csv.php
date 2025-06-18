<?php
header("Content-Type: text/plain; charset=utf-8");
require_once("../config/database.php");

function convert_date($input_date) {
    if (empty($input_date)) {
        return null;
    }

    // กรณี ISO 8601 เช่น 2024-02-01T00:00:00
    if (preg_match('/^\d{4}-\d{2}-\d{2}T/', $input_date)) {
        $date = date_create($input_date);
        return $date ? $date->format('Y-m-d') : null;
    }

    // กรณี dd/mm/yyyy หรือ d/m/yyyy
    if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $input_date)) {
        $parts = explode('/', $input_date);
        if (count($parts) === 3) {
            $day = str_pad($parts[0], 2, "0", STR_PAD_LEFT);
            $month = str_pad($parts[1], 2, "0", STR_PAD_LEFT);
            $year = $parts[2];
            return "$year-$month-$day";
        }
    }

    // กรณี yyyy-mm-dd หรืออื่น ๆ ที่ PHP อ่านได้
    $date = date_create($input_date);
    return $date ? $date->format('Y-m-d') : null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file'])) {
    $tmpName = $_FILES['csv_file']['tmp_name'];
    $handle = fopen($tmpName, 'r');

    if (!$handle) {
        die("x ไม่สามารถเปิดไฟล์ CSV ได้");
    }

    $rawHeader = fgetcsv($handle);
    $header = array_map(function($h) {
        $clean = preg_replace('/\x{FEFF}/u', '', $h); // remove BOM
        return trim($clean);
    }, $rawHeader);
    // $header = array_map('trim', fgetcsv($handle)); // แก้ bug ช่องว่างหัวตาราง

    // ล้างข้อมูลเดิม
    if ($_POST['import_mode'] === 'clear') {
    $pdo->exec("DELETE FROM covid_cases");
}

    $rowCount = 0;
    while (($data = fgetcsv($handle)) !== false) {
        $row = array_combine($header, $data);

        // ตรวจสอบว่า hos_id มีจริงหรือไม่
        if (!isset($row['hos_id'])) {
            echo "x แถวที่ $rowCount ไม่มี hos_id\n";
            continue;
        }

        // แปลงวันที่
        $date_report     = convert_date($row['date_report'] ?? '');
        $date_sick_start = convert_date($row['date_sick_start'] ?? '');
        $date_diagnose   = convert_date($row['date_diagnose'] ?? '');
        $date_treat      = convert_date($row['date_treat'] ?? '');

        // เตรียมค่าคอลัมน์อื่น
        $stmt = $pdo->prepare("INSERT INTO covid_cases (
            hos_id, date_report, sex, age, nation_type, occupation,
            pro_id_now, dis_id_now, subdis_id_now,
            pro_id_sick, dis_id_sick, subdis_id_sick,
            date_treat, hos_id_treat, date_sick_start, date_diagnose,
            patient_type, respirator_type, patient_condition
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([
            trim($row['hos_id'] ?? ''),
            $date_report,
            trim($row['sex'] ?? ''),
            trim($row['age'] ?? ''),
            trim($row['nation_type'] ?? ''),
            trim($row['occupation'] ?? ''),
            trim($row['pro_id_now'] ?? ''),
            trim($row['dis_id_now'] ?? ''),
            trim($row['subdis_id_now'] ?? ''),
            trim($row['pro_id_sick'] ?? ''),
            trim($row['dis_id_sick'] ?? ''),
            trim($row['subdis_id_sick'] ?? ''),
            $date_treat,
            trim($row['hos_id_treat'] ?? ''),
            $date_sick_start,
            $date_diagnose,
            trim($row['patient_type'] ?? ''),
            trim($row['respirator_type'] ?? ''),
            trim($row['patient_condition'] ?? ''),
        ]);

        $rowCount++;
    }

    fclose($handle);
    echo "OK ล้างข้อมูลเดิม และนำเข้าข้อมูลใหม่จำนวน $rowCount แถว เรียบร้อยแล้ว";
} else {
    echo "! กรุณาอัปโหลดไฟล์ CSV ผ่าน method POST โดยใช้ชื่อ input = csv_file";
}
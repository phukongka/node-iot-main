<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Upload COVID CSV</title>
</head>

<body>
    <h3>อัปโหลดข้อมูลผู้ป่วย COVID-19</h3>
    <form action="upload_csv.php" method="post" enctype="multipart/form-data">
        <label>เลือกไฟล์ CSV:</label>
        <input type="file" name="csv_file" accept=".csv" required><br><br>

        <label>เลือกวิธีการนำเข้าข้อมูล:</label><br>
        <input type="radio" id="clear" name="import_mode" value="clear" checked>
        <label for="clear">ล้างข้อมูลเดิมก่อนนำเข้า</label><br>

        <input type="radio" id="append" name="import_mode" value="append">
        <label for="append">เพิ่มข้อมูลเข้าไปโดยไม่ล้าง</label><br><br>

        <button type="submit">อัปโหลด</button>
    </form>
</body>

</html>
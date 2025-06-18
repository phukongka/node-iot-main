const db = require('../db');
// และทำแบบเดียวกันสำหรับ getAllCases ด้วย (ถ้ายังไม่ได้ทำ)
exports.getAllCases = async (req, res) => {
  try {
    const [rows, fields] = await db.query("SELECT * FROM covid_cases"); // <--- Destructure
    res.json({ status: 'success', data: rows }); // <--- ส่งเฉพาะ rows
  } catch (err) {
    console.error('Error in getAllCases API:', err); // Log error
    res.status(500).json({ status: 'error', message: err.message });
  }
};
exports.getCaseByHospital = (req, res) => {
  const hos_id = req.params.hos_id;
  const sql = 'SELECT * FROM covid_cases WHERE hos_id = ?';
  db.query(sql, [hos_id], (err, results) => {
    if (err) return res.status(500).json({ status: 'error', message: err.message });
    res.json({ status: 'success', data: results });
  });
};

// ใน exports.totalgender
exports.totalgender = async (req, res) => {
  const sql = 'SELECT sex, COUNT(*) AS total FROM covid_cases GROUP BY sex';
  try {
    const [rows, fields] = await db.query(sql); // <--- Destructure results ให้ได้ rows และ fields แยกกัน
    // หรืออีกทางเลือก: const rows = (await db.query(sql))[0];

    // ตรวจสอบโครงสร้างของข้อมูลจริงที่ได้ (เผื่อ sex เป็นตัวเลขหรือมีค่า undefined/null)
    console.log("Raw gender data from DB:", rows);

    // ส่งเฉพาะ rows ไปยัง Frontend
    res.json({ status: 'success', data: rows }); // <--- ส่งเฉพาะ rows
  } catch (err) {
    console.error('Error in totalgender API:', err); // Log error เพื่อการดีบัก
    res.status(500).json({ status: 'error', message: err.message });
  }
};
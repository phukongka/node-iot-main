const db = require('../db');
exports.getAllCases = async (req, res) => {
  try {
    const rows = await db.query("SELECT * FROM covid_cases");
    // res.json(rows);
    res.send({
      rows: rows
    })
  } catch (err) {
    res.status(500).json({ error: err.message });
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

exports.totalgender = (req, res) => {
  const sql = 'SELECT sex, COUNT(*) AS total FROM covid_cases GROUP BY sex';
  db.query(sql, (err, results) => {
    if (err) return res.status(500).json({ status: 'error', message: err.message });
    res.json({ status: 'success', data: results });
  });
};

// const mysql = require('mysql2');
// const dotenv = require('dotenv');
// dotenv.config();

// const db = mysql.createConnection({
//   host: process.env.DB_HOST,
//   user: process.env.DB_USER,
//   password: process.env.DB_PASSWORD,
//   database: process.env.DB_NAME
// });

// db.connect(err => {
//   if (err) {
//     console.error('❌ Database connection failed:', err.stack);
//     return;
//   }
//   console.log('✅ Connected to database');
// });

// module.exports = db;
const mysql = require('mysql2');
const dotenv = require('dotenv');
dotenv.config();

const db = mysql.createPool({
  host: process.env.DB_HOST,
  user: process.env.DB_USER,
  password: process.env.DB_PASSWORD,
  database: process.env.DB_NAME,
  port: process.env.DB_PORT,
  waitForConnections: true,
  connectionLimit: 10,
  queueLimit: 0
});

db.getConnection((err, connection) => {
  if (err) {
    console.error('❌ Database connection failed:', err.stack);
    // เพิ่ม logic ในการ retry
    setTimeout(() => {
      console.log('Retrying database connection...');
      // อาจจะเรียกฟังก์ชันเชื่อมต่อใหม่ หรือ exit(1) ถ้าเกินจำนวนครั้ง
      // สำหรับตอนนี้ ลองแค่ไม่ exit() ทันที
    }, 5000); // ลองใหม่ใน 5 วินาที
    return;
  }
  console.log('✅ Connected to database pool. Connection ID: ' + connection.threadId);
  connection.release();
});
module.exports = db.promise();
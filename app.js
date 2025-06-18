const express = require('express');
const dotenv = require('dotenv');
const path = require('path');
const app = express();
const cors = require('cors');

dotenv.config();

// เสิร์ฟไฟล์ static (HTML, JS, CSS)
// app.exituse(express.static(path.join(__dirname, 'public')));
app.use(express.static(path.join(__dirname, 'public'))); // แก้ไข 'exituse' เป็น 'use'

// กำหนดเส้นทางสำหรับ API
const covidRoutes = require('./routes/covid');
app.set('json spaces', 2);

app.use(cors())
app.use(express.json());

// ใช้ route เริ่มต้น
app.use('/api/covid', covidRoutes);

// เริ่มต้นเซิร์ฟเวอร์
const PORT = process.env.PORT || 7000;
app.listen(PORT, () => {
  console.log(`🚀 Server running on http://localhost:${PORT}`);
});


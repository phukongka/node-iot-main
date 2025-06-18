# ใช้ Node.js เวอร์ชันที่คุณต้องการ
FROM node:18

# ตั้ง working directory ใน container
WORKDIR /app

# คัดลอกไฟล์ package.json และ package-lock.json (ถ้ามี)
COPY package*.json ./

# ติดตั้ง dependencies
RUN npm install

# คัดลอกโค้ดทั้งหมดไปใน container
COPY . .

# เปิดพอร์ต 3000 ที่จะใช้สำหรับแอป
EXPOSE 3000

# คำสั่งที่จะรันเมื่อ container เริ่มทำงาน
CMD ["node", "app.js"]

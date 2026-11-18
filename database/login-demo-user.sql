-- Optional demo account for local testing.
-- Import this file AFTER database/hotel.sql, into the itc_ratnadipa database.
-- Email: admin@itcratnadipa.com
-- Password: admin123
INSERT INTO users (name, email, password, role)
VALUES (
  'ITC Administrator',
  'admin@itcratnadipa.com',
  '$2y$12$OtYUuPk1C/7KkfmZXkQ1OuYitxYq587YmTmxmk0B.NrvL8B4/4k4y',
  'admin'
)
ON DUPLICATE KEY UPDATE
  name = VALUES(name),
  password = VALUES(password),
  role = VALUES(role);

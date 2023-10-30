ALTER TABLE users 
ADD login_attempts INT NOT NULL DEFAULT 0, 
ADD last_failed_login DATE;

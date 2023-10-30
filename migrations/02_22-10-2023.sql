ALTER TABLE usersADD logiin_attemps INT NOT NULL DEFAULT(0);
ADD login_attempts INT NOT NULL DEFAULT(0);

ALTER TABLE users
ADD last_failed_login DATE;
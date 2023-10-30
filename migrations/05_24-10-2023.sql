CREATE TABLE educations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    title VARCHAR(255) NOT NULL,
    date VARCHAR(50),
    description TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

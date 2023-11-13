CREATE TABLE personal_info (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    first_name VARCHAR(255),
    last_name VARCHAR(255),
    address VARCHAR(255),
    description TEXT, 
    FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB

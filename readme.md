CREATE TABLE transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    date DATETIME,
    description VARCHAR(255),
    transaction_type ENUM('deposit', 'withdraw'),
    amount DECIMAL(10, 2),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- 1.- Creamos la Base de Datos
create database proyectorud3 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- Seleccionamos la base de datos "proyectorud3"
use proyectorud3;


CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    is_verified BOOLEAN DEFAULT FALSE
);

CREATE TABLE email_verifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    token VARCHAR(255) UNIQUE,   
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE remember_tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    token_hash VARCHAR(255),
    expires_at DATETIME  , 
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);


-- 3.- Creamos un usuario
create user gestorud3@'localhost' identified by "secreto";
-- 4.- Le damos permiso en la base de datos "proyecto"
grant all on proyectorud3.* to gestorud3@'localhost';
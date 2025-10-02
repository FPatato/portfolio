CREATE DATABASE base_de_donnee;
USE base_de_donnee;

CREATE TABLE Utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    date_inscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE InformationsPersonnelles (
    utilisateur_id INT NOT NULL,
    prenom VARCHAR(50),
    nom VARCHAR(50),
    courriel VARCHAR(100) UNIQUE NOT NULL,
    adresse VARCHAR(50),
    pays VARCHAR(50),
    province VARCHAR(50),
    zip VARCHAR(50),
    age int,
    sexe VARCHAR(50),
    poids FLOAT,
    taille FLOAT,
    niv_activite VARCHAR(50),
    telephone VARCHAR(50),
    FOREIGN KEY (utilisateur_id) REFERENCES Utilisateurs(id) ON DELETE CASCADE
);

CREATE TABLE Aliment (
    id INT AUTO_INCREMENT PRIMARY KEY,
    aliment VARCHAR(100),
    calories INT,
    serving_size_g FLOAT,
    fat_total_g FLOAT,
    fat_satured_g FLOAT,
    protein_g FLOAT,
    sodium_mg FLOAT,
    potassium_mg FLOAT,
    cholesterol_mg FLOAT,
    carbohydrates_total_g FLOAT,
    fiber_g FLOAT,
    sugar_g FLOAT
);

CREATE TABLE ListeAliment (
    id_ali INT,
    id_aliment INT NOT NULL,
    FOREIGN KEY (id_aliment) REFERENCES Aliment(id),
    date DATE NOT NULL
);

CREATE TABLE ObjectifCalorie (
    objCal_id INT AUTO_INCREMENT PRIMARY KEY,
    calories_min FLOAT,
    calories_consomme FLOAT,
    date DATE NOT NULL
);

CREATE TABLE ObjectifProteine (
    objPro_id INT AUTO_INCREMENT PRIMARY KEY,
    proteines_min FLOAT,
    proteines_consomme FLOAT,
    date DATE NOT NULL
);

CREATE TABLE ObjectifEau (
    objEau_id INT AUTO_INCREMENT PRIMARY KEY,
    ml_min INT,
    eau_consomme INT,
    date DATE NOT NULL
);

CREATE TABLE ListeObjectif (
    id_obj INT AUTO_INCREMENT PRIMARY KEY,
    objCal_id INT,
    objPro_id INT,
    objEau_id INT,
    FOREIGN KEY (objCal_id) REFERENCES ObjectifCalorie(objCal_id),
    FOREIGN KEY (objPro_id) REFERENCES ObjectifProteine(objPro_id),
    FOREIGN KEY (objEau_id) REFERENCES ObjectifEau(objEau_id),
    date DATE NOT NULL
);


CREATE TABLE Historique (
    id_hist INT NOT NULL,
    utilisateur_id INT NOT NULL,
    date DATE NOT NULL,
    FOREIGN KEY (utilisateur_id) REFERENCES Utilisateurs(id),
    id_ali INT DEFAULT NULL,
    id_obj INT DEFAULT NULL,
    FOREIGN KEY (id_obj) REFERENCES ListeObjectif(id_obj)
);

CREATE TABLE Tokens (
    id_token INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token VARCHAR(255) NOT NULL,
    date_expiration DATETIME NOT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Utilisateurs(id) ON DELETE CASCADE,
    UNIQUE(token)
);

CREATE TABLE Statistique (
    id_stat INT AUTO_INCREMENT PRIMARY KEY,
    id_hist INT NOT NULL,
    poids FLOAT,
    imc FLOAT,
    calories_total FLOAT,
    proteines_total FLOAT,
    carbs_total FLOAT,
    fat_total FLOAT,
    date DATE NOT NULL
);

-- Ajouter une contrainte d'unicité sur le nom d'utilisateur
ALTER TABLE users
ADD CONSTRAINT uc_username UNIQUE (username);



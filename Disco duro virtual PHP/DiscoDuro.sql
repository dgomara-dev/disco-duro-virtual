CREATE DATABASE discoduro;
USE discoduro;

CREATE USER discoduro IDENTIFIED BY "discoduro";
GRANT ALL ON discoduro.* TO discoduro;

CREATE TABLE usuarios (
	usuario CHAR(15) PRIMARY KEY,
    nombre CHAR(15) NOT NULL,
	clave VARCHAR(255) NOT NULL,
	cuota NUMERIC(10)
);

CREATE TABLE ficheros (
	id CHAR(23) PRIMARY KEY,
	nombre VARCHAR(255) NOT null,
	tamanyo NUMERIC(9),
	tipo VARCHAR(100),
	usuario CHAR(15) NOT null,
	INDEX(usuario),
	FOREIGN KEY (usuario) REFERENCES usuarios (usuario)
);

INSERT INTO usuarios VALUES ("user1", "Marcus Aurelius", "", "500000");
INSERT INTO usuarios VALUES ("user2", "Gaius Octavius", "", "250000");
INSERT INTO usuarios VALUES ("user3", "Lucius Domitius", "", "100000");
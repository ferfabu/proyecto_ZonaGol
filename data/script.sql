CREATE TABLE Users (
	fName VARCHAR(30) NOT NULL,
    lName VARCHAR(30) NOT NULL,
    email VARCHAR(50) NOT NULL PRIMARY KEY,
    passwrd VARCHAR(250) NOT NULL,
    passadmin VARCHAR(250) NOT NULL,
    rol int(3) NOT NULL DEFAULT '2'
);

CREATE TABLE Comments (
    commentDB VARCHAR(500) NOT NULL,
    emailComment VARCHAR(50) NOT NULL,
    FOREIGN KEY (emailComment) REFERENCES Users(email)
);

CREATE TABLE Foto (
    codigo INT(11) NOT NULL primary key AUTO_INCREMENT,
    categoria VARCHAR(30) NOT NULL,
    nombre VARCHAR(30) NOT NULL,
    foto VARCHAR(300) NOT NULL
);
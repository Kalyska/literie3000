create database literie3000;

use literie3000;

create table sizes(
    id TINYINT primary key auto_increment,
    size VARCHAR(7) NOT NULL
);

create table brands(
    id TINYINT primary key auto_increment,
    name VARCHAR(50) NOT NULL
);

create table mattresses(
    id smallint primary key auto_increment,
    name VARCHAR(50) NOT NULL,
    id_brand tinyint,
    id_size tinyint,
    picture varchar(255),
    price DECIMAL(7,2),
    saleprice DECIMAL(7,2),
    FOREIGN KEY (id_brand) REFERENCES brands(id),
    FOREIGN KEY (id_size) REFERENCES sizes(id)
);

INSERT INTO sizes (size) values
("90x190"),
("140x190"),
("160x200"),
("180x200"),
("200x200");

INSERT INTO brands (name) values
("Epeda"),
("Dreamway"),
("Bultex"),
("Dorsoline"),
("MemoryLine");

INSERT into mattresses (name, id_brand, id_size, price, saleprice, picture) values
("Transition", 1, 1, 759, 529, "35f872a2b7ab833a8e31422e59735c2c.webp"),
("Stan", 2, 1, 809, 709, "ead0f36f1b4fe1869f04bcb213d89f63.webp"),
("Teamasse", 3, 2, 759, 529, "b90e9a5470dd59e95a1cf56916f2055d.webp" ),
("Coup de boule", 1, 3, 1019, 509, "de9852061a9d5ced127f82fc9a83db22.webp");
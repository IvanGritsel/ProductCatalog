CREATE DATABASE product_base;
USE product_base;

CREATE TABLE product
(
    id           INT AUTO_INCREMENT NOT NULL,
    name         VARCHAR(45) NOT NULL,
    description  LONGTEXT DEFAULT NULL,
    manufacturer VARCHAR(45) NOT NULL,
    release_date DATE        NOT NULL,
    price_byn    INT         NOT NULL,
    product_type INT         NOT NULL,
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
CREATE TABLE product_service
(
    id         INT AUTO_INCREMENT NOT NULL,
    product_id INT NOT NULL,
    service_id INT NOT NULL,
    price      INT NOT NULL,
    term       INT NOT NULL,
    INDEX      IDX_304481624584665A (product_id),
    INDEX      IDX_30448162ED5CA9E6 (service_id),
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
CREATE TABLE service
(
    id   INT AUTO_INCREMENT NOT NULL,
    name VARCHAR(45) NOT NULL,
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
ALTER TABLE product_service
    ADD CONSTRAINT FK_304481624584665A FOREIGN KEY (product_id) REFERENCES product (id);
ALTER TABLE product_service
    ADD CONSTRAINT FK_30448162ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id);
INSERT INTO service (id, name)
VALUES (1, 'Warranty'),
       (2, 'Delivery'),
       (3, 'Installation'),
       (4, 'Setup');

CREATE TABLE product_type
(
    id   INT AUTO_INCREMENT NOT NULL,
    type VARCHAR(45) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`,
    UNIQUE INDEX type (type),
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = '';
ALTER TABLE product
    ADD CONSTRAINT fk_product_type FOREIGN KEY (product_type) REFERENCES product_type (id) ON UPDATE NO ACTION ON DELETE NO ACTION;
CREATE INDEX fk_product_type ON product (product_type);
INSERT INTO product_type (id, type)
VALUES (1, 'TV'),
       (2, 'Laptop'),
       (3, 'Phone'),
       (4, 'Fridge');

CREATE TABLE user
(
    id       INT AUTO_INCREMENT NOT NULL,
    email    VARCHAR(180) NOT NULL,
    roles    JSON         NOT NULL,
    password VARCHAR(255) NOT NULL,
    UNIQUE INDEX UNIQ_8D93D649E7927C74 (email),
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;


CREATE TABLE currency_conversions
(
    id    INT AUTO_INCREMENT NOT NULL,
    rates JSON NOT NULL,
    date  DATE NOT NULL,
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
INSERT INTO user (id, email, roles, password) VALUE (1, 'mail@mail.com', '[\"ROLE_ADMIN\"]', '$2y$13$pHfF.woqzMCnguTSuHqD2.tVVnNHuGRUXfUhqyIFX.uUFqEc41D02');
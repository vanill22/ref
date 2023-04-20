CREATE TABLE `users`
(
    `id`         INT(11) NOT NULL AUTO_INCREMENT,
    `name`       VARCHAR(255) DEFAULT NULL,
    `gender`     INT(11) NOT NULL COMMENT '0 - не указан, 1 - мужчина, 2 - женщина.',
    `birth_date` INT(11) NOT NULL COMMENT 'Дата в unixtime.',
    PRIMARY KEY (`id`)
);
CREATE TABLE `phone_numbers`
(
    `id`      INT(11) NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) NOT NULL,
    `phone`   VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (`id`)
);

-- Напишите запрос, возвращающий имя и число указанных телефонных номеров девушек в возрасте от 18 до 22 лет.
-- Оптимизируйте таблицы и запрос при необходимости.


CREATE INDEX idx_users_gender ON users (gender);
CREATE INDEX idx_users_birthdate ON users (birth_date);
CREATE INDEX idx_phone_numbers_user_id ON phone_numbers (user_id);

SELECT u.name, COUNT(p.id) as phone_count
FROM users u
         JOIN phone_numbers p ON u.id = p.user_id
WHERE u.gender = 2
  AND u.birth_date >= UNIX_TIMESTAMP('2001-04-20')
  AND u.birth_date <= UNIX_TIMESTAMP('2005-04-20')
GROUP BY u.name;
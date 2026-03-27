CREATE DATABASE IF NOT EXISTS `flavourfolder_db`
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;

USE `flavourfolder_db`;

CREATE TABLE IF NOT EXISTS `users` (
  `id`         INT(11)      NOT NULL AUTO_INCREMENT,
  `username`   VARCHAR(50)  NOT NULL UNIQUE,
  `email`      VARCHAR(100) NOT NULL UNIQUE,
  `password`   VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`username`, `email`, `password`) VALUES
('chef_demo', 'demo@flavourfolder.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

CREATE TABLE IF NOT EXISTS `recipes` (
  `id`           INT(11)      NOT NULL AUTO_INCREMENT,
  `title`        VARCHAR(150) NOT NULL,
  `email`        VARCHAR(100) NOT NULL,
  `category`     VARCHAR(60)  NOT NULL,
  `description`  TEXT         NOT NULL,
  `ingredients`  TEXT         NOT NULL,
  `steps`        TEXT         NOT NULL,
  `image`        VARCHAR(255) DEFAULT NULL,
  `cooking_time` VARCHAR(50)  DEFAULT NULL,
  `servings`     VARCHAR(50)  DEFAULT NULL,
  `difficulty`   VARCHAR(20)  DEFAULT NULL,
  `user_id`      INT(11)      DEFAULT NULL,
  `created_at`   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_recipes_user` (`user_id`),
  CONSTRAINT `fk_recipes_user`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `recipes` (`title`, `email`, `category`, `description`, `ingredients`, `steps`, `cooking_time`, `servings`, `difficulty`, `user_id`) VALUES
(
  'Spicy Sri Lankan Dhal Curry',
  'demo@flavourfolder.com',
  'Main Course',
  'A hearty and warming red lentil curry bursting with Sri Lankan spices. Perfect with rice or roti.',
  '2 cups red lentils\n1 onion, sliced\n3 garlic cloves, minced\n1 tsp turmeric\n1 tsp chilli powder\n1 tsp cumin seeds\n400ml coconut milk\nSalt to taste\nCooking oil',
  'Rinse lentils and soak for 15 minutes.\nHeat oil in a pan and fry cumin seeds until fragrant.\nAdd onion and cook until golden.\nStir in garlic, turmeric and chilli powder.\nAdd lentils and coconut milk, bring to a boil.\nSimmer for 20 minutes until lentils are soft.\nSeason with salt and serve hot.',
  '35 min',
  'Serves 4',
  'Easy',
  1
),
(
  'Mango Coconut Chia Pudding',
  'demo@flavourfolder.com',
  'Dessert',
  'A creamy, tropical chia seed pudding topped with fresh mango. A healthy and refreshing dessert.',
  '4 tbsp chia seeds\n1 cup coconut milk\n1 tbsp honey\n1 ripe mango, diced\nMint leaves for garnish',
  'Mix chia seeds with coconut milk and honey.\nStir well and refrigerate overnight.\nTop with fresh mango just before serving.\nGarnish with mint leaves.',
  '10 min + overnight',
  'Serves 2',
  'Easy',
  1
);

CREATE TABLE IF NOT EXISTS `messages` (
  `id`         INT(11)      NOT NULL AUTO_INCREMENT,
  `name`       VARCHAR(100) NOT NULL,
  `email`      VARCHAR(100) NOT NULL,
  `subject`    VARCHAR(150) DEFAULT NULL,
  `message`    TEXT         NOT NULL,
  `created_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `messages` (`name`, `email`, `subject`, `message`) VALUES
('Test User', 'test@example.com', 'General Enquiry', 'Hello! I love this recipe website. Keep up the great work!');

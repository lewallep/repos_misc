#If I have time add another table which tracks the total sales by date.


DROP TABLE IF EXISTS menu_users;
CREATE TABLE menu_users (
id INT UNSIGNED NOT NULL AUTO_INCREMENT,
user_name VARCHAR(255),
user_pw VARCHAR(255), 
resturaunt VARCHAR(255),
PRIMARY KEY (id)
);

DROP TABLE IF EXISTS menu_food;
CREATE TABLE menu_food (
id INT UNSIGNED NOT NULL AUTO_INCREMENT,
item_name VARCHAR(255),
amount_sold INT unsigned,
price DECIMAL (11,2),
images_name VARCHAR(255),
menu_item_image BLOB,
user_id INT UNSIGNED NOT NULL,
PRIMARY KEY (id),
FOREIGN KEY (user_id) REFERENCES menu_users(id)
);


#A bunch of test inserts to ensure the tables are working as they should. 
#These inserts are for the menu_users table.
INSERT INTO menu_users (user_name, user_pw, resturaunt) VALUES ('test1', '5468bht', 'food4less');
INSERT INTO menu_users (user_name, user_pw, resturaunt) VALUES ('peatie', 'gfp69', 'the wrong way');
INSERT INTO menu_users (user_name, user_pw, resturaunt) VALUES ('svennie', 'gentle viking', 'the wrong way');
INSERT INTO menu_users (user_name, user_pw, resturaunt) VALUES ('shawn', 'hipsterPDX', 'the wrong way');

#These inserts are to populate the menu_food table for testing
INSERT INTO menu_food (item_name, amount_sold, price, user_id) VALUES ('cheese burger', 5, 3.99, 1);
INSERT INTO menu_food (item_name, amount_sold, price, user_id) VALUES ('veggie burger', 2, 4.99, 1);

INSERT INTO menu_food (item_name, amount_sold, price, user_id) VALUES ('cheese burger', 5, 3.99, 3);
INSERT INTO menu_food (item_name, amount_sold, price, user_id) VALUES ('veggie burger', 2, 4.99, 3);

INSERT INTO menu_food (item_name, amount_sold, price, user_id) VALUES ('chicken wings', 99, 3.25, 4);
INSERT INTO menu_food (item_name, amount_sold, price, user_id) VALUES ('fries small', 34, 1.25, 4);
INSERT INTO menu_food (item_name, amount_sold, price, user_id) VALUES ('fries large', 9999999, 2.25, 4);
INSERT INTO menu_food (item_name, amount_sold, price, user_id) VALUES ('fries medium', 8987, 2.00, 4);
INSERT INTO menu_food (item_name, amount_sold, price, user_id) VALUES ('fires american sized', 1000000, 2.99, 2);
INSERT INTO menu_food (item_name, amount_sold, price, user_id) VALUES ('salad green', 0, 1.00, 2);
INSERT INTO menu_food (item_name, amount_sold, price, user_id) VALUES ('salad greek', 0, 4.00, 2);
INSERT INTO menu_food (item_name, amount_sold, price, user_id) VALUES ('Soda Small', 99, 2.25, 2);
INSERT INTO menu_food (item_name, amount_sold, price, user_id) VALUES ('Soda Medium', 100, 2.50, 2);
INSERT INTO menu_food (item_name, amount_sold, price, user_id) VALUES ('Soda Large', 1000, 3.00, 2);
INSERT INTO menu_food (item_name, amount_sold, price, user_id) VALUES ('Princess Cake', 3, 21.67, 3);


SELECT user_name, user_pw, resturaunt, item_name, amount_sold, price, user_id FROM menu_users 
INNER JOIN menu_food on menu_users.id = menu_food.user_id;
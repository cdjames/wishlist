/*  
	** Author: Collin James, CS 340
	** Date: 5/23/16
	** Description: Activity: Final Project - Selections
*/

/******* SELECTS *******/

/* select all product and related info for specified product */
SELECT DISTINCT p.name, p.photo_url, lp.bought, FLOOR(ps.price) AS dollars, FLOOR(((ps.price - FLOOR(ps.price)) * 100)) AS cents, ps.product_url, m.mfct_id, s.store_id FROM product p 
LEFT JOIN list_product lp ON lp.fk_product_id = p.product_id
LEFT JOIN product_store ps ON ps.fk_product_id = p.product_id
LEFT JOIN stores s ON s.store_id = ps.fk_store_id
LEFT JOIN mfct_product mp ON p.product_id = mp.fk_product_id
LEFT JOIN manufacturer m ON m.mfct_id = mp.fk_mfct_id
WHERE p.product_id = [product_id] and lp.fk_list_id = [listid] GROUP BY p.name

/* select all manufacturers*/
SELECT m.name, m.country, m.mfct_id FROM manufacturer m GROUP BY m.name, m.country, m.mfct_id

/* select all stores */
SELECT s.store_name, s.store_url, s.store_id FROM stores s GROUP BY s.store_name

/* select all user info given a list id */
SELECT fname, lname FROM users WHERE user_id = (SELECT fk_user_id FROM list WHERE list_id = [listid])

/* select formatted dates */
SELECT DATE_FORMAT(l.created, '%M %D, %Y'), DATE_FORMAT(l.updated, '%M %D, %Y') FROM list l WHERE l.list_id = [listid]

/* get all product info given a list id */
SELECT p.name, p.photo_url, lp.bought, ps.price, m.name, m.country, s.store_name, ps.product_url, p.product_id, m.mfct_id, s.store_id FROM users u 
LEFT JOIN list l ON l.fk_user_id = u.user_id 
LEFT JOIN list_product lp ON lp.fk_list_id = l.list_id 
LEFT JOIN product p ON p.product_id = lp.fk_product_id 
LEFT JOIN product_store ps ON ps.fk_product_id = p.product_id 
LEFT JOIN stores s ON s.store_id = ps.fk_store_id 
LEFT JOIN mfct_product mp ON p.product_id = mp.fk_product_id 
LEFT JOIN manufacturer m ON m.mfct_id = mp.fk_mfct_id 
WHERE l.list_id = [listid] 
GROUP BY p.name

/* get a list of all users */
SELECT u.fname, u.lname, u.dob, l.list_id, u.user_id FROM users u 
INNER JOIN list l ON l.fk_user_id = u.user_id 
GROUP BY u.lname, u.fname

/* find a product with a certain name*/
SELECT product_id FROM product WHERE name=[name]

/* select a manufacturer */
SELECT mfct_id FROM manufacturer WHERE name=[name] and country=[country]

/* select a produt_store link */
SELECT fk_store_id FROM product_store WHERE fk_product_id=[product_id]

/* select a mfct_product link */
SELECT fk_mfct_id FROM mfct_product WHERE fk_product_id=[product_id]

/* check for list_product link */
SELECT fk_product_id FROM list_product WHERE fk_product_id=[product_id] and fk_list_id=[listid]

/* look for a store */
SELECT store_id FROM stores WHERE store_name=[name] and store_url=[url]

/* select all products on a list less than a certain price */
SELECT p.name, p.photo_url, lp.bought, ps.price, m.name, m.country, s.store_name, ps.product_url, p.product_id, m.mfct_id, s.store_id FROM users u 
LEFT JOIN list l ON l.fk_user_id = u.user_id 
LEFT JOIN list_product lp ON lp.fk_list_id = l.list_id 
LEFT JOIN product p ON p.product_id = lp.fk_product_id 
LEFT JOIN product_store ps ON ps.fk_product_id = p.product_id 
LEFT JOIN stores s ON s.store_id = ps.fk_store_id 
LEFT JOIN mfct_product mp ON p.product_id = mp.fk_product_id 
LEFT JOIN manufacturer m ON m.mfct_id = mp.fk_mfct_id 
WHERE l.list_id = [listid]  and ps.price < [price] 
GROUP BY ps.price


/******* INSERTS *******/

/* add a manufacturer */
INSERT INTO manufacturer(country, name) VALUES ([country], [name])

/* a mfct link*/
INSERT INTO mfct_product VALUES([product_id], [mfct_id])

/* add a mfct link*/
INSERT INTO mfct_product(fk_product_id, fk_mfct_id) VALUES([product_id],[mfct_id])

/* add a product_store link */
INSERT INTO product_store(fk_product_id, fk_store_id, price, product_url) VALUES([product_id],[store_id],[price],[product_url])

/* add a product */
INSERT INTO product(name, photo_url) VALUES ([name], [photo_url])

/* add a list_product link*/
INSERT INTO list_product(fk_product_id, fk_list_id) VALUES([product_id],[listid])

/* add a store */
INSERT INTO stores(store_url, store_name) VALUES ([url], [name])

/* add a user */
INSERT INTO users(fname, lname, dob) VALUES ([first_name], [last_name], [date_of_birth])

/* create a list for a user */
INSERT INTO list(updated, fk_user_id) VALUES (CURRENT_TIMESTAMP, [userid])

/******* UPDATES *******/

/* update bought status */
UPDATE list_product SET bought=[status] WHERE fk_product_id = [product_id] and fk_list_id = [listid]

/* update product name, photo url, bought status, update stamp, price, and product_url*/
UPDATE product p 
LEFT JOIN list_product lp ON lp.fk_product_id = p.product_id 
LEFT JOIN list l ON l.list_id = lp.fk_list_id 
LEFT JOIN product_store ps ON ps.fk_product_id = p.product_id 
LEFT JOIN stores s ON s.store_id = ps.fk_store_id 
LEFT JOIN mfct_product mp ON p.product_id = mp.fk_product_id 
LEFT JOIN manufacturer m ON m.mfct_id = mp.fk_mfct_id 
SET p.name=[name], p.photo_url=[photo_url], lp.bought=[status], l.updated=CURRENT_TIMESTAMP, ps.price=[price], ps.product_url=[url] 
WHERE lp.fk_list_id = [listid] and p.product_id = [product_id] and m.mfct_id = [mfct_id] and s.store_id = [store_id]

/* update a mfct link */
UPDATE mfct_product SET fk_mfct_id=[mfct_id] WHERE fk_product_id=[product_id]

/* update a product_store link */
UPDATE product_store SET fk_store_id=[store_id] WHERE fk_product_id=[product_id]

/******* DELETES *******/

/* remove a product link from a list */
DELETE FROM list_product WHERE fk_product_id = [product_id] and fk_list_id=[listid]



/* TESTING THE DATABASE */

-- # Get all users sorted by last name
-- SELECT u.fname, u.lname, u.dob, l.list_id FROM users u 
-- INNER JOIN list l ON l.fk_user_id = u.user_id 
-- GROUP BY u.lname

-- # Get a user's list
-- SELECT DATE_FORMAT(l.created, '%M %D, %Y'), DATE_FORMAT(l.updated, '%M %D, %Y') FROM list l 
-- WHERE l.list_id = [id]

-- # Get a total of all items in a user's list.  
-- SELECT u.user_id, SUM(ps.price) FROM users u 
-- INNER JOIN list l ON l.fk_user_id = u.user_id
-- INNER JOIN list_product lp ON lp.fk_list_id = l.list_id
-- INNER JOIN product p ON p.product_id = lp.fk_product_id
-- INNER JOIN product_store ps ON ps.fk_product_id = p.product_id
-- GROUP BY u.user_id

-- # Get all items and prices in a user's list
-- SELECT u.lname, p.name, ps.price FROM users u 
-- INNER JOIN list l ON l.fk_user_id = u.user_id
-- INNER JOIN list_product lp ON lp.fk_list_id = l.list_id
-- INNER JOIN product p ON p.product_id = lp.fk_product_id
-- INNER JOIN product_store ps ON ps.fk_product_id = p.product_id
-- WHERE u.user_id = [userid]

-- # Get all product info a user's list
-- SELECT p.name, p.photo_url, lp.bought, ps.price, m.name, m.country, s.store_name, ps.product_url FROM users u 
-- INNER JOIN list l ON l.fk_user_id = u.user_id
-- INNER JOIN list_product lp ON lp.fk_list_id = l.list_id
-- INNER JOIN product p ON p.product_id = lp.fk_product_id
-- INNER JOIN product_store ps ON ps.fk_product_id = p.product_id
-- INNER JOIN stores s ON s.store_id = ps.fk_store_id
-- INNER JOIN mfct_product mp ON p.product_id = mp.fk_product_id
-- INNER JOIN manufacturer m ON m.mfct_id = mp.fk_mfct_id
-- WHERE u.user_id = [userid]

-- # Get all product info
-- SELECT DISTINCT p.name, p.photo_url, lp.bought, FLOOR(ps.price) AS dollars, FLOOR(((ps.price - FLOOR(ps.price)) * 100)) AS cents, m.name, m.country, s.store_name, s.store_url, ps.product_url FROM product p 
-- LEFT JOIN list_product lp ON lp.fk_product_id = p.product_id
-- LEFT JOIN product_store ps ON ps.fk_product_id = p.product_id
-- LEFT JOIN stores s ON s.store_id = ps.fk_store_id
-- LEFT JOIN mfct_product mp ON p.product_id = mp.fk_product_id
-- LEFT JOIN manufacturer m ON m.mfct_id = mp.fk_mfct_id
-- WHERE p.product_id = [pid] GROUP BY p.name

-- SELECT p.name, p.photo_url, lp.bought, ps.price, m.name, m.country, s.store_name, ps.product_url FROM users u 
-- LEFT JOIN list l ON l.fk_user_id = u.user_id
-- LEFT JOIN list_product lp ON lp.fk_list_id = l.list_id
-- LEFT JOIN product p ON p.product_id = lp.fk_product_id
-- LEFT JOIN product_store ps ON ps.fk_product_id = p.product_id
-- LEFT JOIN stores s ON s.store_id = ps.fk_store_id
-- LEFT JOIN mfct_product mp ON p.product_id = mp.fk_product_id
-- LEFT JOIN manufacturer m ON m.mfct_id = mp.fk_mfct_id
-- WHERE l.list_id = 

-- # Update all tables
-- UPDATE product p 
-- LEFT JOIN list_product lp ON lp.fk_product_id = p.product_id
-- LEFT JOIN list l ON l.list_id = lp.fk_list_id
-- LEFT JOIN product_store ps ON ps.fk_product_id = p.product_id
-- LEFT JOIN stores s ON s.store_id = ps.fk_store_id
-- LEFT JOIN mfct_product mp ON p.product_id = mp.fk_product_id
-- LEFT JOIN manufacturer m ON m.mfct_id = mp.fk_mfct_id
-- SET p.name=?, p.photo_url=?, lp.bought=?, lp.updated=CURRENT_TIMESTAMP, ps.fk_store_id=?, ps.price=?, ps.product_url=?, mp.fk_mfct_id
-- WHERE lp.fk_list_id = [listid] and p.product_id = [pid] and m.mfct_id = [mid] and s.store_id = [sid]

-- UPDATE product p LEFT JOIN list_product lp ON lp.fk_product_id = p.product_id LEFT JOIN list l ON l.list_id = lp.fk_list_id LEFT JOIN product_store ps ON ps.fk_product_id = p.product_id LEFT JOIN stores s ON s.store_id = ps.fk_store_id LEFT JOIN mfct_product mp ON p.product_id = mp.fk_product_id LEFT JOIN manufacturer m ON m.mfct_id = mp.fk_mfct_id SET p.name='hi', p.photo_url='', lp.bought=0, l.updated=CURRENT_TIMESTAMP, ps.fk_store_id=1, ps.price=20.11, ps.product_url='', mp.fk_mfct_id=1 WHERE lp.fk_list_id = 3 and p.product_id = 3 and m.mfct_id = 1 and s.store_id = 1

-- # Get all unbought items and prices in a user's list
-- SELECT p.name, ps.price FROM users u
-- INNER JOIN list l ON l.fk_user_id = u.user_id
-- INNER JOIN list_product lp ON lp.fk_list_id = l.list_id
-- INNER JOIN product p ON p.product_id = lp.fk_product_id
-- INNER JOIN product_store ps ON ps.fk_product_id = p.product_id
-- WHERE u.lname = 'Jensen' and lp.bought = 0

-- # Get all item names and prices
-- SELECT p.name, ps.price FROM product p
-- INNER JOIN product_store ps ON ps.fk_product_id = p.product_id;

-- # Get all empty lists
-- SELECT l.list_id, l.created FROM list l
-- LEFT JOIN list_product lp ON lp.fk_list_id = l.list_id
-- GROUP BY l.list_id -- must group for having to work!
-- HAVING COUNT(lp.fk_product_id) < 1

-- # Get all manufacturers and their products
-- SELECT m.name, p.name FROM manufacturer m
-- INNER JOIN mfct_product mp ON m.mfct_id = mp.fk_mfct_id
-- INNER JOIN product p ON p.product_id = mp.fk_product_id
-- -- WHERE m.name = 'Yimby' -- target one manufacturer

-- # Get a product's mfct
-- SELECT mp.fk_mfct_id FROM mfct_product mp WHERE mp.fk_product_id=[]

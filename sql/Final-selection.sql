/*  
	** Author: Collin James, CS 340
	** Date: 5/23/16
	** Description: Activity: Final Project - Selections
*/

# Get a total of all items in a user's list. 
 
SELECT u.user_id, SUM(ps.price) FROM users u 
INNER JOIN list l ON l.fk_user_id = u.user_id
INNER JOIN list_product lp ON lp.fk_list_id = l.list_id
INNER JOIN product p ON p.product_id = lp.fk_product_id
INNER JOIN product_store ps ON ps.fk_product_id = p.product_id
GROUP BY u.user_id

# Get all items and prices in a user's list
SELECT u.lname, p.name, ps.price FROM users u 
INNER JOIN list l ON l.fk_user_id = u.user_id
INNER JOIN list_product lp ON lp.fk_list_id = l.list_id
INNER JOIN product p ON p.product_id = lp.fk_product_id
INNER JOIN product_store ps ON ps.fk_product_id = p.product_id

# Get all unbought items and prices in a user's list
SELECT p.name, ps.price FROM users u
INNER JOIN list l ON l.fk_user_id = u.user_id
INNER JOIN list_product lp ON lp.fk_list_id = l.list_id
INNER JOIN product p ON p.product_id = lp.fk_product_id
INNER JOIN product_store ps ON ps.fk_product_id = p.product_id
WHERE u.lname = 'Jensen' and lp.bought = 0

# Get all item names and prices
SELECT p.name, ps.price FROM product p
INNER JOIN product_store ps ON ps.fk_product_id = p.product_id;

# Get all empty lists
SELECT l.list_id, l.created FROM list l
LEFT JOIN list_product lp ON lp.fk_list_id = l.list_id
GROUP BY l.list_id -- must group for having to work!
HAVING COUNT(lp.fk_product_id) < 1

# Get all manufacturers and their products
SELECT m.name, p.name FROM manufacturer m
INNER JOIN mfct_product mp ON m.mfct_id = mp.fk_mfct_id
INNER JOIN product p ON p.product_id = mp.fk_product_id
-- WHERE m.name = 'Yimby' -- target one manufacturer

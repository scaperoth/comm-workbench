/*
 * modification commands
 * creating, updating, and deleting a user
 */
INSERT INTO `pos`.`user` (`user_id`, `f_name`, `l_name`, `username`, `pass`) VALUES ('41', 'Test', 'Tuple', 'ttuple', '12345');
UPDATE `pos`.`user` SET `username`='ttupler' WHERE `user_id`='41';
DELETE FROM `pos`.`user` WHERE `user_id`='41';

/*VIEWS*/
/*
 * number of employees at each store
 */
CREATE OR REPLACE VIEW `number_of_employees` AS
    select 
        `s`.`store_id` AS `store_id`,
        `s`.`city` AS `city`,
        `s`.`state` AS `state`,
        count(`e`.`user_id`) AS `number_of_employees`
    from
        ((`user` `e`
        join `store` `s`)
        join `works` `w`)
    where
        ((`e`.`user_id` = `w`.`store_emp_id`)
            and (`s`.`store_id` = `w`.`employee_store_id`))
    group by `s`.`store_id`;

/*
 * Calculate and show all user reward points based on sales
 */
CREATE  OR REPLACE VIEW `customer_reward_points` AS
select 
        `c`.`user_id` AS `user_id`,
        `c`.`f_name` AS `f_name`,
        `c`.`l_name` AS `l_name`,
        sum(`i`.`price`) AS `reward_points`
    from
        ((`user` `c`
        join `item` `i`)
        join `sale` `s`)
    where
        ((`c`.`user_id` = `s`.`sale_cust_id`)
            and (`s`.`sale_item_id` = `i`.`item_id`))
    group by `c`.`user_id`
    order by sum(`i`.`price`) desc;

/* 
 * sale infomration view
 * Gets the names of all customers and employees involved in a sale 
 */ 
select c.f_name, c.l_name, e.f_name, e.l_name, i.name, i.price, s.street_address, s.zip_code
from user c,user e, item i, store s, sale
where c.user_id = sale.sale_cust_id and e.user_id = sale.sale_emp_id and i.item_id = sale.sale_item_id and s.store_id = sale.sale_store_id; 

/* 
 * All employees for each store
 * returns first name, last name, and the id of the store 
 * each employee works at
 */ 
select e.f_name,e.l_name, s.store_id
from  user e, store s, works w 
where e.user_id = w.store_emp_id and s.store_id = w.employee_store_id;

/* 
 * List all products
 * List all products product information
 */ 
select name, item_id, price, release_date, sale_price
from item;

/* 
 * List all preorders
 * Show all customers with a preorder, what they ordered
 * and what the price was for their order 
 */ 
select f_name, l_name, i.name, i.price
from user c, pre_order p, item i
where c.user_id = p.preorder_cust_id and i.item_id = p.preorder_item_id; 


/* every user that's made a preorder DISTINCT */
SELECT DISTINCT f_name, l_name 
FROM user, customer, pre_order p 
WHERE preorder_cust_id = cust_id 
      AND cust_id = user_id;

/* 
 * List all employees who aren't a manager
 */  
select e.user_id, e.f_name, e.l_name
from user e left join manages m
on e.user_id = m.store_mgr_id
where m.store_mgr_id is null; 

/* 
 * List the highest reward point customers
 * Gets the names of all customers with the
 * highest reward points
 */ 
select u.f_name, u.l_name, c.reward_points
from user u, customer_reward_points c 
where u.user_id = c.user_id AND c.reward_points = 
	(select max(reward_points) from customer_reward_points);

/* items with quantity lower than 5 */ 
select i.name, s.quantity
from item i, store_item s
where s.item_store_id = i.item_id AND quantity < 5
group by s.item_store_id;

/* managers view of items that need to be restocked 
 * shows view of all items, their quantities and 
 * the manufacturer
 
select i.name, i.quantity, man.name "Manufacturer"
from item i, manufacturer man, produces p 
where i.item_id = p.production_item_id AND man.manu_id = p.item_manufacturer_id; 
*/ 

/*
 * lists all items and their distributors
 */ 
select i.name, d.name
from item i ,distributor d, produces p 
where i.item_id = p.production_item_id and p.item_distributor_id = d.dist_id
order by d.name; 

/* 
 * list items on sale 
 */ 
select name, price, i.sale_price, s.quantity
from item i, store_item s
where i.sale_price is not null AND i.item_id = s.store_item_id
group by  s.store_item_id; 

/* 
 * List all states with store
 */ 
select state
from store
Group By state;

/*
 * stores by zipcode
 * list all zip codes our stores
 * are located in
 */
select zip_code, city, state
from store
Group By zip_code
order by zip_code; 

/* stores by size */ 
select * 
from store
Order By store.size; 

/* nested subquery 

/*
 * all the customers that have made a sale but not a preorder 
 */
select f_name, l_name
from user u
where exists (SELECT * from sale
				where sale.sale_cust_id = u.user_id)
AND NOT EXISTS(SELECT * FROM pre_order
				where preorder_cust_id = u.user_id);


/* average sales for each employee  AGGREGATE */
select  e.f_name , e.l_name, avg(i.price) average
from user e, item i, sale s 
where s.sale_emp_id = e.user_id 
	AND i.item_id = s.sale_item_id
Group By s.sale_emp_id
Order By average; 


/* view all the manufacturers or distributors*/
SELECT DISTINCT name 
FROM manufacturer
UNION
SELECT DISTINCT name
FROM distributor;

/*View all customers and employees but no managers*/
SELECT f_name, l_name 
FROM user, customer 
WHERE user_id = cust_id
UNION 
SELECT f_name, l_name
FROM user, employee
WHERE user_id = emp_id AND role_id = 1;

/* Select all employees that have also been customers */ 
SELECT DISTINCT f_name, l_name 
FROM employee, user, customer
where emp_id = user_id and user_id = cust_id;

/* Count # of employees */
SELECT COUNT(emp_id)
FROM employee e, employee_role er
WHERE e.emp_id = er.role_id AND
      type = "Employee";

/* Find an employee's total sales */
SELECT f_name, l_name, SUM(price) total_sales 
FROM user u, employee e, sale s, item i
WHERE u.user_id = e.emp_id AND
      e.emp_id = s.sale_emp_id AND
      i.item_id = s.sale_item_id
group by u.user_id;

/* Find the average sale amongst all employees */
SELECT AVG(total_sales) 
FROM(   
	SELECT f_name, l_name, SUM(price) total_sales 
	FROM user u, employee e, sale s, item i
	WHERE u.user_id = e.emp_id AND
      e.emp_id = s.sale_emp_id AND
      i.item_id = s.sale_item_id
	group by u.user_id) as total;


/* select employees that are a standard deviation or more below the sale average */
SELECT f_name, l_name
FROM user, employee, item, sale
WHERE     (SELECT SUM(Price) total_sales
	   FROM employee, sale, item
	   WHERE emp_id = sale_emp_id AND
	   item_id = sale_item_id )
	   <
         ((SELECT AVG(total_sales)
           FROM(
	   	SELECT SUM(price) total_sales
		FROM employee, sale, item
		WHERE emp_id = sale_emp_id AND
		      item_id = sale_item_id ) as s
       ) -(SELECT STDDEV(total_sales)
	  FROM (
	     	 SELECT SUM(price) total_sales
		FROM employee, sale, item
		WHERE emp_id = sale_emp_id AND
		      item_id = sale_item_id 
		) as i
      ) 
);  
      

/*all the sales made by cities not in New York */
SELECT sale_cust_id, item.name,  city, state
FROM sale, user AS a, user AS b, store, employee, customer, item
WHERE sale_cust_id = cust_id AND
      sale_item_id=item_id AND
      sale_store_id = store_id AND
      sale_emp_id = emp_id AND
      a.user_id = sale_cust_id AND
      b.user_id = sale_emp_id and
	  state != "New York";


/* total sales from each store */
SELECT sale_store_id, SUM(price) 
FROM sale, item, store
WHERE sale_item_id=item_id AND
      sale_store_id = store_id
group by store_id;


/* select all employees that have made a sale */ 
SELECT DISTINCT f_name, l_name
FROM user, employee, sale
WHERE user_id = emp_id
      AND sale_emp_id = emp_id;






POSproject
==========

Project using [Bootstrap 3.0](http://getbootstrap.com/) and the [Yii Framework](http://www.yiiframework.com/).   
   
To learn how to use the bootstrap widgets with Yii, visit the [Yii-Bootstrap-3 Module Page](http://bootstrap3.pascal-brewing.de/) and browse through the Class Reference page.

###Other useful Links:   
- [Font Awesome](http://fontawesome.io/)
- [Google Fonts](http://www.google.com/fonts/)
- [REST API](http://code.tutsplus.com/tutorials/creating-an-api-centric-web-application--net-23417)    

> Note: a REST API may not be needed in this project

###Item   
-	Item detail page      
        -	/item?id={item_id}     
-	Catalog         
        -	/catalog   
     
###Manager      
-	Store statistics      
        -	/stats    
-	Item control form(s)      
        -	/inventory      
-	Employee form        
        -	/hr    
     
###Employee
-	Personal statistics    
        -	/employee      
        -	 {$_POST[“{employee_id}”]}     
-	Checkout || item sale page    
        -	/checkout    
     
###User     
-	Account    
        -	/account     
        -	{$_POST[“{user_id}”]}    
-	Order history     
        -	/orders     
        -	{$_POST[“{user_id}”]}    
-	Preorder     
        -	/preorders     
        -	{$_POST[“{user_id}”]}    
   
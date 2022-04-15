# testfeelunique
Change localhost connection from config->connection.php file

feelunique.sql is database export file for import database

order.xml file is xml which is given by team as example

data_load_from_xml.php file is used for display loaded data from the xml file of perticular order_id



customer_id we can use when more customers in our database for now its 1

using cakephp

for change database credential config/app_local.php
check composer if not exist then install and php version



Below are the api and parameter for api:

order data 

url: http://localhost/testfeelunique/orders

method: get 

reponse: {"orders":[{"id":3,"customer_id":1,"currency":"GBP","date":"2022-04-15T00:00:00+00:00","price":104.98,"status":1,"products":[{"id":3,"title":"Rimmel
Lasting Finish Lipstick 4g\"","price":4.99,"_joinData":{"id":15,"order_id":3,"product_id":3}},{"id":1,"title":"GHD Hair
Straighteners","price":99.99,"_joinData":{"id":16,"order_id":3,"product_id":1}}]},{"id":4,"customer_id":1,"currency":"GBP","date":"2022-04-15T00:00:00+00:00","price":0,"status":1,"products":[]},{"id":5,"customer_id":1,"currency":"GBP","date":"2022-04-15T00:00:00+00:00","price":99.99,"status":1,"products":[{"id":1,"title":"GHD
Hair
Straighteners","price":99.99,"_joinData":{"id":13,"order_id":5,"product_id":1}}]},{"id":6,"customer_id":1,"currency":"GBP","date":"2022-04-15T00:00:00+00:00","price":119.98,"status":1,"products":[{"id":2,"title":"Redken
Shampure Shampoo","price":19.99,"_joinData":{"id":17,"order_id":6,"product_id":2}},{"id":1,"title":"GHD Hair
Straighteners","price":99.99,"_joinData":{"id":18,"order_id":6,"product_id":1}}]}]}


Order data from order_id 

url: http://localhost/testfeelunique/orders/4

method: get 

reponse: {"order":[{"id":6,"customer_id":1,"currency":"GBP","date":"2022-04-15T00:00:00+00:00","price":119.98,"status":1,"products":[{"id":2,"title":"Redken
Shampure Shampoo","price":19.99,"_joinData":{"id":17,"order_id":6,"product_id":2}},{"id":1,"title":"GHD Hair
Straighteners","price":99.99,"_joinData":{"id":18,"order_id":6,"product_id":1}}]}]}

Delete order using order_id (I have not hard delete order except used status for not saw deleted order)

url: http://localhost/testfeelunique/orders/2

method: delete

reponse: {"message":"order deleted"}

Add order

url: http://localhost/testfeelunique/orders/

method: post

body:form-data

product_id:3,4

reponse: {"order_detail":[{"id":6,"customer_id":1,"currency":"GBP","date":"2022-04-15T00:00:00+00:00","price":119.98,"status":1,"products":[{"id":2,"title":"Redken Shampure Shampoo","price":19.99,"_joinData":{"id":17,"order_id":6,"product_id":2}},{"id":1,"title":"GHD Hair Straighteners","price":99.99,"_joinData":{"id":18,"order_id":6,"product_id":1}}]}]}

update order using order_id 

url: http://localhost/testfeelunique/orders/4

method: put

body:x-www-form-urlencoded

product_id:3,2

reponse: {"message":"Order updated"}

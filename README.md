# yii2-micro-api
yii2 micro for rest api
Using Yii as a Micro-framework
Yii can be easily used without the features included in basic and advanced templates. 
In other words, Yii is already a micro-framework. It is not required to have the directory structure provided by templates to work with Yii.<br>
use composer<br>
composer create-project --prefer-dist --stability=dev wibiesana/yii2-micro-api yii2-micro-api<br>
or<br>
download then run composer update<br>

yii2-micro-api does not create database for you, you have to create yourself<br>

at this point our API will provide the following URLs:<br>

http://localhost/yii2-micro-api/user/ - list all user - get <br>
http://localhost/yii2-micro-api/user/create - create data user - post <br>
http://localhost/yii2-micro-api/user/update?id=1 - update data user- post <br>
http://localhost/yii2-micro-api/user/view?id=1 - view data user with id 1 - get <br>
http://localhost/yii2-micro-api/user/delete?id=1 delete data user with id 1 - delete <br>

this template already include register user and login

http://localhost/yii2-micro-api/site/login - login - post <br>
http://localhost/yii2-micro-api/site/signup - signup - post

this template also include debug toolbar and gii 

you can acces gii using http://localhost/yii2-micro-api/gii <br>
you can acces debug using http://localhost/yii2-micro-api/debug

![alt text](./toolbar.png)



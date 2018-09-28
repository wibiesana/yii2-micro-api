# yii2-micro-api
yii2 micro for rest api
Using Yii as a Micro-framework
Yii can be easily used without the features included in basic and advanced templates. 
In other words, Yii is already a micro-framework. It is not required to have the directory structure provided by templates to work with Yii.

download then run composer update

yii2-micro-api does not create database you you have to create yourself

at this point our API will provide the following URLs:

http://localhost/micro-api/api/user/ - list all user - get <br>
http://localhost/micro-api/api/user/create - create data user - post <br>
http://localhost/micro-api/api/user/update?id=1 - create data user- post <br>
http://localhost/micro/api/user/view?id=1 - view data user with id 1 - get <br>
http://localhost/micro/api/user/delete?id=1 delete data user with id 1 - delete <br>

this template already include register user and login keep in mind

http://localhost/micro/api/user/login - login - post
http://localhost/micro/api/user/register - login - register

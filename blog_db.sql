create database blog_db;

create table blog(
id int auto_increment,
usuario varchar (15) not null,
mensaje varchar (200) not null,
primary key (id)
);
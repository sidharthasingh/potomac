create database potomac;
use potomac;
create table session(ID int,user_id int not null);
alter table session modify column ID int primary key;
alter table session drop primary key;
alter table session modify column ID int primary key auto_increment;
create table session_meta(ID int primary key auto_increment,session_id int not null,user_id int not null,meta_key varchar(100) not null,meta_value varchar(1000));
create table users(ID int primary key auto_increment,user_login varchar(60) not null,user_pass varchar(255) not null,user_email varchar(100) not null,user_registered datetime not null,first_name varchar(150) not null,last_name varchar(100),user_status varchar(50) not null);
create table user_meta(ID int primary key not null,user_id int not null,meta_key varchar(100) not null,meta_value varchar(1000));
alter table user_meta drop primary key;
alter table user_meta modify column ID int primary key auto_increment;
create table log(ID bigint primary key auto_increment, date_time datetime, data varchar(5000));
alter table users modify column user_email varchar(100) unique not null;

create table terms(ID int primary key auto_increment, name varchar(50) not null);
create table term_relationship(ID int primary key auto_increment, term_id int not null, file_id int not null);
create table file(ID int primary key auto_increment, file_name varchar(100) not null, file_location varchar(100) not null);
create table file_meta(ID int primary key auto_increment, file_id int not null, meta_key varchar(100) not null, meta_value varchar(1000));

	create table login_tokens(id int primary key auto_increment, token varchar(128) UNIQUE not null, user_id int not null);
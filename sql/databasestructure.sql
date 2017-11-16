create database monitoring;

use monitoring;

create table machineType
(
	id int primary key auto_increment,
    name varchar(30) not null
);

insert into machineType(name) value('electrical'),('thermal'),('hydraulics');

create table employee
(
	id int primary key auto_increment,
	firstname varchar(50) not null,
	lastname varchar(50) not null,
	email varchar(30) unique not null,
	phone char(10) not null unique,
	position varchar(15) check ((position like 'Supervisor') or (position like 'Technical')),
	password varchar(20) not null
);

insert into employee(firstname,lastname,email,phone,position,password)
	values
	('Pablo','Alvarez','pablo@industry.com','664123456','Supervisor','1234567'),
	('David','Martinez','david@industry.com','664654321','Supervisor','1234567'),
    ('Andres','Flores','andres@industry.com','664123321','Technical','1234567'),
    ('Tilo','Wolf','tilo@industry.com','664321123','Technical','1234567');

create table area
(
	id int primary key auto_increment,
	name varchar(30) not null,
	supervisor int references employee(id)
);

insert into area(name,Supervisor) values('Molding',1);

create table machine
(
	id int primary key auto_increment,
	model varchar(50) not null,
	type int references machineType(id),
	description varchar(250) not null,
	area int references area(id),
	status varchar(30) not null
);

insert into machine(model,type,description,area,status)
	values('ENGEL e-duo',1,'Compact injection molding machine',1,'very good');

create table sensorType
(
	id int primary key auto_increment,
	name varchar(30) not null
);

create table sensor
(
	id int auto_increment,
	machine_id int references machine(id),
	model varchar(30) not null,
	description varchar(250) not null,
	max_rank int not null,
	mid_rank int not null,
	min_rank int not null,
	type int references sensorType(id),
	primary key(id,machine_id)
);

create table logs
(
	id int auto_increment,
	machine_id int references machine(id),
	technical int references employee(id),
	repaired char(1) check (repaired like 'N' or repaired like 'Y'),
	date_start datetime default NOW(),
	date_finish datetime,
	primary key(id,machine_id)
);

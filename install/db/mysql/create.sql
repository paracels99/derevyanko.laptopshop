create table if not exists b_derevyanko_laptopshop_manufacturer
(
    ID int(11) not null auto_increment,
    NAME varchar(255) not null,
    primary key (ID)
);

create table if not exists b_derevyanko_laptopshop_model
(
    ID int(11) not null auto_increment,
    NAME varchar(255) not null,
    MANUFACTURER_ID int(11) not null references b_derevyanko_laptopshop_manufacturer(ID),
    primary key (ID)
);

create table if not exists b_derevyanko_laptopshop_laptop
(
    ID int(11) not null auto_increment,
    NAME varchar(255) not null,
    YEAR int(4) not null,
    PRICE decimal(18, 4) not null,
    MODEL_ID int(11) not null references b_derevyanko_laptopshop_model(ID),
    primary key (ID)
);

create table if not exists b_derevyanko_laptopshop_option
(
    ID int(11) not null auto_increment,
    NAME varchar(255) not null,
    primary key (ID)
);

create table if not exists b_derevyanko_laptopshop_laptop_option
(
    ID int(11) not null auto_increment,
    OPTION_ID int(11) not null references b_derevyanko_laptopshop_option(ID),
    LAPTOP_ID int(11) not null references b_derevyanko_laptopshop_laptop(ID),
    primary key (ID)
);
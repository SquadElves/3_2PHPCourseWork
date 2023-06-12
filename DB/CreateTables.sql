create table users(
    ID serial primary key,
    Name varchar(20),
    Surname varchar(20),
    UserName varchar(20) unique,
    DateRegistration timestamp
);
create table auctions(
    ID serial primary key,
    IDCreator int references users(ID),
    Title varchar(100),
    TimeStart timestamp,
    TimeEnd timestamp,
    PriceBuyNow int,
    Price int
);
create table bids(
     IDAuction int not null references auctions(ID),
     IDUser int not null references users(ID),
     Amount int,
     Date timestamp,
     primary key (IDAuction, IDUser)
);

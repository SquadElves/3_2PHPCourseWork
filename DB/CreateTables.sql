create table Users(
    ID serial primary key,
    Name varchar(20),
    Surname varchar(20),
    Password varchar(32),
    Email varchar(255) unique,
    RegistrationDate timestamp
);

create table Auction(
    ID serial primary key,
    UserID int references users(ID),
    StateID numeric(1) default 0,

    Title varchar(100),
    Description text,

    StartDate timestamp,
    EndDate timestamp,

    StartPrice int,
    BuyNowPrice int,
    NowPrice int,

    BidID int,
    BidStep int,
    BidCount int default 0
);

create table Bid(
    ID serial primary key,
    AuctionID int not null references Auction(ID),
    UserID int not null references Users(ID),
    Amount int
);
ALTER table auction add constraint fk_Auction_Bid_ID foreign key (BidID) references Bid(ID);
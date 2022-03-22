CREATE TABLE Customer(
    account_username varchar(20),
    email varchar(20),
    address varchar(20),
    customer_name varchar(20),
    PRIMARY KEY(account_username),
);

INSERT INTO Customer VALUES (“Ellen123”, “ellen123@gmail.com”,”2366 Main Mall”, “Ellen”,”Lon:49.25,Lat:-123.16”);

CREATE TABLE FoodProvider(
    food_provider_name varchar(30),
    food_provider_location varchar(30),
    phone_number char(10),
    PRIMARY KEY(food_provider_name, food_provider_location)
);

INSERT INTO FoodProvider VALUES ('McDonalds', '5728 University Blvd #101, Vancouver','6042212570');
INSERT INTO FoodProvider VALUES ('The Keg Steakhouse + Bar', '688 Dunsmuir St., Vancouver','6046857502');
INSERT INTO FoodProvider VALUES ('The Home Depot', '3950 Henning Dr, Burnaby', '6042943030');
INSERT INTO FoodProvider VALUES ('Pho 37','8328 Capstan Way #1101, Richmond','6044475158');
INSERT INTO FoodProvider VALUES ('Cactus Club Richmond Centre','6511 No.3 Rd #1666, Richmond','6042449969');

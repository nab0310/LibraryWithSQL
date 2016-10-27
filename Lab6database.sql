-- DROP TABLE shelves;
-- DROP TABLE loanHistory;
-- DROP TABLE books;
-- DROP TABLE users;

create table users(
UserName char (255),
Password char (255),
Email char (255),
Phone char (10),
Librarian tinyint (1),
FirstName char (255),
LastName char (255),
PRIMARY KEY (UserName));

create table books(
BookID int (10),
BookTitle char (255),
Author char (255),
Availability tinyint (1),
PRIMARY KEY (BookID));

create table loanHistory(
UserName char (255),
BookID int (10),
DueDate date,
ReturnedDate date,
FOREIGN KEY (UserName) references users(UserName),
FOREIGN KEY (BookID) references books(BookID));

create table bookLocations(
BookID int(10),
ShelfID int(10),
PRIMARY KEY (BookID,ShelfID));

create table shelves(
ShelfID int (10),
ShelfName char (255),		-- art, science, sport, literature
PRIMARY KEY (ShelfID,ShelfName));

-- insert into shelves		values (1, 'Art', bookID);
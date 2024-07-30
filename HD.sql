-- Create Users table
CREATE TABLE Users (
    UserID INT AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(50) UNIQUE NOT NULL,
    Password VARCHAR(255) NOT NULL,
    Role ENUM('student', 'staff', 'faculty') NOT NULL
);

-- Create Complaints table
CREATE TABLE Complaints (
    ComplaintID INT AUTO_INCREMENT PRIMARY KEY,
    UserID INT,
    FOREIGN KEY (UserID) REFERENCES Users(UserID),
    ComplaintType VARCHAR(50) NOT NULL,
    Description TEXT NOT NULL,
    Building VARCHAR(50) NOT NULL,
    Room VARCHAR(20) NOT NULL,
    Status ENUM('Open', 'Assigned', 'Completed') NOT NULL DEFAULT 'Open'
    TicektID VARCHAR(50);
);

-- Create Personnel table
CREATE TABLE Personnel (
    PersonnelID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(100) NOT NULL,
    ContactNumber VARCHAR(20) NOT NULL,
    Role VARCHAR(50) NOT NULL
);

-- Create Assignment table
CREATE TABLE Assignment (
    AssignmentID INT AUTO_INCREMENT PRIMARY KEY,
    ComplaintID INT,
    FOREIGN KEY (ComplaintID) REFERENCES Complaints(ComplaintID),
    PersonnelID INT,
    FOREIGN KEY (PersonnelID) REFERENCES Personnel(PersonnelID),
    AssignedDate DATE NOT NULL,
    CompletionDate DATE
);


INSERT INTO Users (Username, Password, Role) VALUES ('iit2022060','ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f','student');
INSERT INTO Users (Username, Password, Role) VALUES ('iit2022061','ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f','student');

INSERT INTO Users (Username, Password, Role) VALUES ('Mohit','ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f','staff');

INSERT INTO Personnel (Name, ContactNumber, Role) VALUES ('Asher', '123-456-7890', 'staff');
INSERT INTO Personnel (Name, ContactNumber, Role) VALUES ('Yashraj', '987-654-3210', 'staff');

INSERT INTO Users (Username, Password, Role) VALUES ('Dr. Anjali','ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f','faculty');


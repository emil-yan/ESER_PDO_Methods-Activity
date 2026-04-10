CREATE DATABASE IF NOT EXISTS rock_concerts;
USE rock_concerts;

CREATE TABLE IF NOT EXISTS attendances (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    event_name VARCHAR(100) NOT NULL,
    venue VARCHAR(100) NOT NULL,
    city VARCHAR(50) NOT NULL,
    attendance_count INT(11) NOT NULL,
    event_date DATE NOT NULL
);

-- Mock Data
INSERT INTO attendances (event_name, venue, city, attendance_count, event_date) VALUES
('Metallica World Tour', 'Wembley Stadium', 'London', 90000, '2023-09-30'),
('AC/DC Power Up', 'The Forum', 'Los Angeles', 17500, '2023-01-30'),
('Foo Fighters Live', 'Madison Square Garden', 'New York', 20000, '2023-05-30'),
('The Rolling Stones', 'Red Rocks Amphitheatre', 'Morrison', 9500, '2023-12-04');

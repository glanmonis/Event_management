
CREATE TABLE customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_email VARCHAR(150) NOT NULL,
    event_date DATE NOT NULL,
    event_time TIME NOT NULL,
    time_period VARCHAR(10),
    event_type VARCHAR(100) NOT NULL,
    decoration_id INT,
    decoration_price DECIMAL(10,2),
    guests INT,
    shamiyana BOOLEAN,
    sheets BOOLEAN,
    speakers INT,
    lighting BOOLEAN,
    chair_cost DECIMAL(10,2),
    shamiyana_cost DECIMAL(10,2),
    sheets_cost DECIMAL(10,2),
    sound_cost DECIMAL(10,2),
    lighting_cost DECIMAL(10,2),
    total_cost DECIMAL(10,2),
    address TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (decoration_id) REFERENCES decorations(id),
    FOREIGN KEY (customer_email) REFERENCES customers(email)
);


CREATE TABLE decorations (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  event_type VARCHAR(255) NOT NULL,
  image_path VARCHAR(255) NOT NULL,
  price DECIMAL(10, 2) NOT NULL
);



-- Inserting new decoration records for each event type

-- Birthday
INSERT INTO decorations (event_type, image_path, price) VALUES
('Birthday', 'images/birthday1.jpg', 500),
('Birthday', 'images/birthday2.jpg', 600),
('Birthday', 'images/birthday3.jpg', 700),
('Birthday', 'images/birthday4.jpg', 800),
('Birthday', 'images/birthday5.jpg', 900),
('Birthday', 'images/birthday6.jpg', 1000),
('Birthday', 'images/birthday7.jpg', 1100),
('Birthday', 'images/birthday8.jpg', 1200),
('Birthday', 'images/birthday9.jpg', 1300),
('Birthday', 'images/birthday10.jpg', 1400);

-- Engagement
INSERT INTO decorations (event_type, image_path, price) VALUES
('Engagement', 'images/engagement1.jpg', 600),
('Engagement', 'images/engagement2.jpg', 650),
('Engagement', 'images/engagement3.jpg', 700),
('Engagement', 'images/engagement4.jpg', 750),
('Engagement', 'images/engagement5.jpg', 800),
('Engagement', 'images/engagement6.jpg', 850),
('Engagement', 'images/engagement7.jpg', 900),
('Engagement', 'images/engagement8.jpg', 950),
('Engagement', 'images/engagement9.jpg', 1000),
('Engagement', 'images/engagement10.jpg', 1050);

-- Roka
INSERT INTO decorations (event_type, image_path, price) VALUES
('Roka', 'images/roka1.jpg', 700),
('Roka', 'images/roka2.jpg', 750),
('Roka', 'images/roka3.jpg', 800),
('Roka', 'images/roka4.jpg', 850),
('Roka', 'images/roka5.jpg', 900),
('Roka', 'images/roka6.jpg', 950),
('Roka', 'images/roka7.jpg', 1000),
('Roka', 'images/roka8.jpg', 1050),
('Roka', 'images/roka9.jpg', 1100),
('Roka', 'images/roka10.jpg', 1150);

-- Marriage
INSERT INTO decorations (event_type, image_path, price) VALUES
('Marriage', 'images/marriage1.jpg', 800),
('Marriage', 'images/marriage2.jpg', 850),
('Marriage', 'images/marriage3.jpg', 900),
('Marriage', 'images/marriage4.jpg', 950),
('Marriage', 'images/marriage5.jpg', 1000),
('Marriage', 'images/marriage6.jpg', 1050),
('Marriage', 'images/marriage7.jpg', 1100),
('Marriage', 'images/marriage8.jpg', 1150),
('Marriage', 'images/marriage9.jpg', 1200),
('Marriage', 'images/marriage10.jpg', 1250);

-- Haldi
INSERT INTO decorations (event_type, image_path, price) VALUES
('Haldi', 'images/haldi1.jpg', 600),
('Haldi', 'images/haldi2.jpg', 650),
('Haldi', 'images/haldi3.jpg', 700),
('Haldi', 'images/haldi4.jpg', 750),
('Haldi', 'images/haldi5.jpg', 800),
('Haldi', 'images/haldi6.jpg', 850),
('Haldi', 'images/haldi7.jpg', 900),
('Haldi', 'images/haldi8.jpg', 950),
('Haldi', 'images/haldi9.jpg', 1000),
('Haldi', 'images/haldi10.jpg', 1050);

-- Madarangi
INSERT INTO decorations (event_type, image_path, price) VALUES
('Madarangi', 'images/madarangi1.jpg', 700),
('Madarangi', 'images/madarangi2.jpg', 750),
('Madarangi', 'images/madarangi3.jpg', 800),
('Madarangi', 'images/madarangi4.jpg', 850),
('Madarangi', 'images/madarangi5.jpg', 900),
('Madarangi', 'images/madarangi6.jpg', 950),
('Madarangi', 'images/madarangi7.jpg', 1000),
('Madarangi', 'images/madarangi8.jpg', 1050),
('Madarangi', 'images/madarangi9.jpg', 1100),
('Madarangi', 'images/madarangi10.jpg', 1150);

-- Reception
INSERT INTO decorations (event_type, image_path, price) VALUES
('Reception', 'images/reception1.jpg', 900),
('Reception', 'images/reception2.jpg', 950),
('Reception', 'images/reception3.jpg', 1000),
('Reception', 'images/reception4.jpg', 1050),
('Reception', 'images/reception5.jpg', 1100),
('Reception', 'images/reception6.jpg', 1150),
('Reception', 'images/reception7.jpg', 1200),
('Reception', 'images/reception8.jpg', 1250),
('Reception', 'images/reception9.jpg', 1300),
('Reception', 'images/reception10.jpg', 1350);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    phone VARCHAR(20),
    password VARCHAR(255) NOT NULL,
    profile_image VARCHAR(255) DEFAULT NULL,
    role ENUM('customer','admin') DEFAULT 'customer',
    status ENUM('active','blocked') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
    ON UPDATE CURRENT_TIMESTAMP
);


CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(150) NOT NULL,
    brand VARCHAR(100),
    car_model VARCHAR(100),
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    stock_quantity INT DEFAULT 0,
    main_image VARCHAR(255),
    status ENUM('available','out_of_stock') 
    DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY(category_id)
    REFERENCES categories(id)
    ON DELETE CASCADE
);


CREATE TABLE product_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    image VARCHAR(255) NOT NULL,

    FOREIGN KEY(product_id)
    REFERENCES products(id)
    ON DELETE CASCADE
);


CREATE TABLE addresses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    country VARCHAR(100),
    city VARCHAR(100),
    district VARCHAR(100),
    street VARCHAR(255),
    is_default BOOLEAN DEFAULT FALSE,

    FOREIGN KEY(user_id)
    REFERENCES users(id)
    ON DELETE CASCADE
);



CREATE TABLE cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE,

    FOREIGN KEY(user_id)
    REFERENCES users(id)
    ON DELETE CASCADE
);



CREATE TABLE cart_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cart_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT DEFAULT 1,

    FOREIGN KEY(cart_id)
    REFERENCES cart(id)
    ON DELETE CASCADE,

    FOREIGN KEY(product_id)
    REFERENCES products(id)
    ON DELETE CASCADE
);


CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,

    status ENUM(
        'pending',
        'processing',
        'shipped',
        'delivered',
        'cancelled'
    ) DEFAULT 'pending',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY(user_id)
    REFERENCES users(id)
    ON DELETE CASCADE
);


CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,

    FOREIGN KEY(order_id)
    REFERENCES orders(id)
    ON DELETE CASCADE,

    FOREIGN KEY(product_id)
    REFERENCES products(id)
    ON DELETE CASCADE
);


CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,

    payment_method ENUM(
        'cash',
        'mobile_money',
        'card'
    ),

    payment_status ENUM(
        'pending',
        'paid',
        'failed'
    ) DEFAULT 'pending',

    transaction_id VARCHAR(255),
    paid_at TIMESTAMP NULL,

    FOREIGN KEY(order_id)
    REFERENCES orders(id)
    ON DELETE CASCADE
);



CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    rating INT CHECK(rating BETWEEN 1 AND 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY(user_id)
    REFERENCES users(id)
    ON DELETE CASCADE,

    FOREIGN KEY(product_id)
    REFERENCES products(id)
    ON DELETE CASCADE
);


CREATE TABLE wishlist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    FOREIGN KEY (product_id)
        REFERENCES products(id)
        ON DELETE CASCADE
);



CREATE TABLE contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100),
    email VARCHAR(150),
    subject VARCHAR(255),
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE inventory_transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,

    product_id INT NOT NULL,

    transaction_type ENUM(
        'stock_in',
        'stock_out',
        'adjustment'
    ) NOT NULL,

    quantity INT NOT NULL,

    previous_stock INT NOT NULL,

    new_stock INT NOT NULL,

    notes VARCHAR(255),

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY(product_id)
    REFERENCES products(id)
    ON DELETE CASCADE
);

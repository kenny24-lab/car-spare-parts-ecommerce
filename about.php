<?php
require_once "config/init.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>About Us | <?= APP_NAME ?></title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial, Helvetica, sans-serif;
}

body{
    background:#f4f6f9;
    color:#333;
    line-height:1.7;
}

.hero{
    background:linear-gradient(135deg,#007bff,#00c6ff);
    color:white;
    text-align:center;
    padding:80px 20px;
}

.hero h1{
    font-size:48px;
    margin-bottom:15px;
}

.hero p{
    font-size:20px;
    max-width:800px;
    margin:auto;
}

.container{
    width:1200px;
    max-width:95%;
    margin:50px auto;
}

.card{
    background:white;
    padding:40px;
    border-radius:15px;
    margin-bottom:30px;
    box-shadow:0 5px 15px rgba(0,0,0,.08);
}

.card h2{
    color:#007bff;
    margin-bottom:20px;
}

.card p{
    color:#555;
    font-size:17px;
}

.grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(300px,1fr));
    gap:25px;
}

.feature{
    background:white;
    padding:30px;
    border-radius:15px;
    text-align:center;
    box-shadow:0 5px 15px rgba(0,0,0,.08);
    transition:.3s;
}

.feature:hover{
    transform:translateY(-8px);
}

.feature h3{
    color:#007bff;
    margin-bottom:15px;
}

.feature p{
    color:#666;
}

.footer-section{
    background:#1f2937;
    color:white;
    text-align:center;
    padding:40px;
    margin-top:50px;
}

.btn{
    display:inline-block;
    background:#007bff;
    color:white;
    text-decoration:none;
    padding:12px 25px;
    border-radius:8px;
    margin-top:20px;
    font-weight:bold;
}

.btn:hover{
    background:#0056b3;
}

</style>

</head>

<body>

<section class="hero">

    <h1>About AutoParts Hub</h1>

    <p>
        Your trusted online destination for high-quality automotive spare parts,
        accessories, and vehicle maintenance solutions.
    </p>

</section>

<div class="container">

    <div class="card">

        <h2>Who We Are</h2>

        <p>
            AutoParts Hub is an innovative online platform designed to simplify
            the process of finding and purchasing genuine vehicle spare parts.
            Our system connects vehicle owners, mechanics, and businesses with
            reliable automotive products at competitive prices.
        </p>

        <p style="margin-top:15px;">
            Whether you need brake pads, filters, batteries, tyres, lighting
            systems, or other essential vehicle components, AutoParts Hub
            provides a convenient and secure shopping experience.
        </p>

    </div>

    <div class="grid">

        <div class="feature">

            <h3>🎯 Our Mission</h3>

            <p>
                To provide customers with easy access to quality automotive
                spare parts while ensuring affordability, reliability,
                convenience, and excellent customer service.
            </p>

        </div>

        <div class="feature">

            <h3>🚀 Our Vision</h3>

            <p>
                To become the leading digital marketplace for automotive
                spare parts in Rwanda and across Africa by leveraging
                technology to improve customer experience.
            </p>

        </div>

        <div class="feature">

            <h3>⭐ Our Values</h3>

            <p>
                Quality, Integrity, Customer Satisfaction, Innovation,
                Transparency, and Continuous Improvement guide every
                decision we make.
            </p>

        </div>

    </div>

    <div class="card">

        <h2>Our Goals</h2>

        <p>
            AutoParts Hub aims to achieve the following goals:
        </p>

        <ul style="margin-top:20px; padding-left:25px; color:#555;">

            <li>Provide genuine and high-quality automotive spare parts.</li>

            <li>Reduce the time customers spend searching for vehicle parts.</li>

            <li>Create a secure and user-friendly online shopping experience.</li>

            <li>Support local vehicle owners and automotive businesses.</li>

            <li>Improve inventory management through digital solutions.</li>

            <li>Increase customer satisfaction through reliable service.</li>

            <li>Expand product availability across different vehicle brands.</li>

            <li>Promote technological innovation in the automotive sector.</li>

        </ul>

    </div>

    <div class="card">

        <h2>What We Offer</h2>

        <p>
            AutoParts Hub offers a wide variety of automotive products including:
        </p>

        <ul style="margin-top:20px; padding-left:25px; color:#555;">

            <li>Brake Pads & Brake Components</li>

            <li>Engine Oil Filters & Air Filters</li>

            <li>LED Headlights & Vehicle Lighting</li>

            <li>Car Batteries & Electrical Parts</li>

            <li>Shock Absorbers & Suspension Components</li>

            <li>Tyres & Wheel Accessories</li>

            <li>Radiator and Cooling System Parts</li>

            <li>Vehicle Maintenance Products</li>

        </ul>

    </div>

    <div class="card" style="text-align:center;">

        <h2>Why Choose AutoParts Hub?</h2>

        <p>
            We combine quality products, competitive pricing,
            secure transactions, and excellent customer support
            to ensure every customer enjoys a smooth shopping experience.
        </p>

        <a href="products.php" class="btn">
            Browse Products
        </a>

    </div>

</div>

<div class="footer-section">

    <h3>AutoParts Hub</h3>

    <p>
        Driving Convenience Through Quality Automotive Solutions.
    </p>

</div>

</body>
</html>
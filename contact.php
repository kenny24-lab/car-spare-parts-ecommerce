<?php

require_once "config/init.php";

include "includes/header.php";
include "includes/navbar.php";

?>

<style>

.contact-container{
    max-width:900px;
    margin:50px auto;
    background:white;
    padding:30px;
    border-radius:10px;
}

.contact-container h1{
    text-align:center;
    margin-bottom:30px;
}

.contact-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:30px;
}

.contact-form input,
.contact-form textarea{

    width:100%;
    padding:12px;
    margin-bottom:15px;
}

.contact-form button{

    background:#007bff;
    color:white;
    border:none;
    padding:12px 25px;
    cursor:pointer;
}

.contact-info p{
    margin-bottom:15px;
}

</style>

<div class="contact-container">

<h1>Contact Us</h1>

<div class="contact-grid">

<div class="contact-info">

<h3>AutoParts Hub</h3>

<p>
📍 Kigali, Rwanda
</p>

<p>
📞 +250 791430404
</p>

<p>
✉️ reberoikuzokennyeduin@gmail.com
</p>

<p>
🕒 Mon - Sat: 08:00 - 18:00
</p>

</div>

<div>

<form class="contact-form">

<input
type="text"
placeholder="Your Name"
required
>

<input
type="email"
placeholder="Your Email"
required
>

<textarea
rows="6"
placeholder="Your Message"
required
></textarea>

<button type="submit">
Send Message
</button>

</form>

</div>

</div>

</div>

<?php include "includes/footer.php"; ?>
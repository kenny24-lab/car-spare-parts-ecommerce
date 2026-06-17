<?php

require_once "config/init.php";

include "includes/header.php";
include "includes/navbar.php";

?>

<style>

.faq-section{
    max-width:900px;
    margin:50px auto;
    padding:20px;
}

.faq-section h1{
    text-align:center;
    margin-bottom:30px;
}

.faq-item{
    border:1px solid #ddd;
    margin-bottom:15px;
    border-radius:8px;
    overflow:hidden;
}

.faq-question{
    background:#111;
    color:#fff;
    padding:15px;
    cursor:pointer;
    font-weight:bold;
}

.faq-answer{
    display:none;
    padding:15px;
    background:#fff;
    line-height:1.6;
}

</style>


<section class="faq-section">

    <h1>Frequently Asked Questions</h1>

    <div class="faq-item">

        <div class="faq-question">
            How do I place an order?
        </div>

        <div class="faq-answer">
            Browse products, add items to your cart and proceed to checkout.
        </div>

    </div>

    <div class="faq-item">

        <div class="faq-question">
            How can I track my order?
        </div>

        <div class="faq-answer">
            Go to the Track Orders page and enter your order number.
        </div>

    </div>

    <div class="faq-item">

        <div class="faq-question">
            What payment methods do you accept?
        </div>

        <div class="faq-answer">
            We accept Mobile Money, Bank Cards and Cash on Delivery.
        </div>

    </div>

    <div class="faq-item">

        <div class="faq-question">
            Do you offer delivery?
        </div>

        <div class="faq-answer">
            Yes. We deliver auto spare parts throughout Rwanda.
        </div>

    </div>

    <div class="faq-item">

        <div class="faq-question">
            Can I return a product?
        </div>

        <div class="faq-answer">
            Yes. Products may be returned within 7 days according to our return policy.
        </div>

    </div>

</section>

<script>

document.querySelectorAll('.faq-question')
.forEach(question => {

    question.addEventListener('click', () => {

        const answer =
        question.nextElementSibling;

        if(answer.style.display === 'block'){

            answer.style.display = 'none';

        } else {

            answer.style.display = 'block';

        }

    });

});

</script>

<?php

include "includes/footer.php";

?>
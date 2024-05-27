
document.getElementById('signup-link').addEventListener('click', function(event) {
    event.preventDefault();
    document.querySelector('.lform-container').style.display = 'none';
    document.querySelector('.sform-container').style.display = 'block';
  });
  
  document.getElementById('Login-link').addEventListener('click', function(event) {
    event.preventDefault();
    document.querySelector('.lform-container').style.display = 'block';
    document.querySelector('.sform-container').style.display = 'none';
  });
  
$(document).ready(function(){

    $(window).scroll(function(){
        //Navbar-bottom scrolling
        if(this.scrollY > 5){
            $('.navbar-bottom').addClass("sticky");
        }else{
            $('.navbar-bottom').removeClass("sticky");
        }

        //Scrolling Button Btn
        if(this.scrollY > 500){
            $('.scroll-up-btn').addClass("show");
        }else{
            $('.scroll-up-btn').removeClass("show");
        }
    });

        //slide up script
        $('.scroll-up-btn').click(function(){
            $('html').animate({scrollTop:0});
        });
        
    //owl owlCarousel
    $('.owl-carousel').owlCarousel({
        margin: 5,
        mavigation: true,
        loop: true,
        autoplay: true,
        autoplayTimeOut: 2000,
        autoplayHoverPause: true,

        responsive: {
            0: {
                items: 1,
                nav: false
            },
            600: {
                items: 2,
                nav: false
            },
            1000: {
                items: 3,
                nav: false
            }
        }
    });
});document.addEventListener("DOMContentLoaded", function() {
    var form = document.getElementById("contactForm");
    form.addEventListener("keyup", function(event) {
        if (event.keyCode === 13) {
            event.preventDefault();
            var inputs = Array.from(form.querySelectorAll("input, textarea"));
            var index = inputs.indexOf(document.activeElement);
            if (index > -1 && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
        }
    });
});

document.getElementById('login').onclick = function(){
    location.href = "dashboard.html";
};

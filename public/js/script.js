/* MENU BURGER */
$(document).ready(function() {
    $(".burger-icon").click(function() {
        $(".menu").toggleClass("menu-abierto");
        $(".lista-menu").toggleClass("lista-abierta");
        $(".fa").toggleClass("fa-bars");
        $(".fa").toggleClass("fa-times");
    });
});

/* HEADER SCROLL */

$(window).scroll(function() {
    var scroll = $(window).scrollTop();

    if (scroll >= 100) {
        $("nav").addClass("scrolling");
    } else {
        $("nav").removeClass("scrolling");
    }
});

/* ALERT DISAPEAR */

function myFunction() {
    $(".alert.alert-success")
        .delay(3000)
        .hide(400);
}

//

/* ANCHOR */

// Select all links with hashes
$('a[href*="#"]')
    // Remove links that don't actually link to anything
    .not('[href="#"]')
    .not('[href="#0"]')
    .click(function(event) {
        // On-page links
        if (
            location.pathname.replace(/^\//, "") ==
            this.pathname.replace(/^\//, "") &&
            location.hostname == this.hostname
        ) {
            // Figure out element to scroll to
            var target = $(this.hash);
            target = target.length ? target : $("[name=" + this.hash.slice(1) + "]");
            // Does a scroll target exist?
            if (target.length) {
                // Only prevent default if animation is actually gonna happen
                event.preventDefault();
                $("html, body").animate({
                        scrollTop: target.offset().top
                    },
                    1000
                );
            }
        }
    });

/* GALLERY */

console.clear();

const elApp = document.querySelector("#app");

const elImages = Array.from(document.querySelectorAll(".gallery-image"));

const elDetail = document.querySelector(".detail");

function flipImages(firstEl, lastEl, change) {
    const firstRect = firstEl.getBoundingClientRect();

    const lastRect = lastEl.getBoundingClientRect();

    // INVERT
    const deltaX = firstRect.left - lastRect.left;
    const deltaY = firstRect.top - lastRect.top;
    const deltaW = firstRect.width / lastRect.width;
    const deltaH = firstRect.height / lastRect.height;

    change();
    lastEl.parentElement.dataset.flipping = true;

    const animation = lastEl.animate(
        [{
                transform: `translateX(${deltaX}px) translateY(${deltaY}px) scaleX(${deltaW}) scaleY(${deltaH})`
            },
            {
                transform: "none"
            }
        ], {
            duration: 600, // milliseconds
            easing: "cubic-bezier(.2, 0, .3, 1)"
        }
    );

    animation.onfinish = () => {
        delete lastEl.parentElement.dataset.flipping;
    };
}

elImages.forEach(figure => {
    figure.addEventListener("click", () => {
        const elImage = figure.querySelector("img");

        elDetail.innerHTML = "";

        const elClone = figure.cloneNode(true);
        elDetail.appendChild(elClone);

        const elCloneImage = elClone.querySelector("img");

        flipImages(elImage, elCloneImage, () => {
            elApp.dataset.state = "detail";
        });

        function revert() {
            flipImages(elCloneImage, elImage, () => {
                elApp.dataset.state = "gallery";
                elDetail.removeEventListener("click", revert);
            });
        }

        elDetail.addEventListener("click", revert);
    });
});
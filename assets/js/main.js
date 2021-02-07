// Glider

new Glider(document.querySelector('.glider'), {
    slidesToShow: 2,
    slidesToScroll: 2,
    scrollLock: true,
    draggable: true,
    rewind: true,
    dots: '#dots',
    arrows: {
        prev: '.prev',
        next: '.next'
    },
    responsive: [
        {
            breakpoint: 768,
            settings: {
                slidesToShow: 'auto',
                slidesToScroll: 'auto',
                itemWidth: 200,
                duration: 0.25
            }
        }, {
            breakpoint: 1024,
            settings: {
                slidesToShow: 4,
                slidesToScroll: 4,
                itemWidth: 150,
                duration: 0.25
            }
        }
    ]
});

const glider = new Glider(document.getElementById('glider'));

function sliderAuto(slider, miliseconds) {
    const slidesCount = slider.track.childElementCount;
    let slideTimeout = null;
    let nextIndex = 1;

    function slide () {
        slideTimeout = setTimeout(
            function () {
                if (nextIndex >= slidesCount ) {
                    nextIndex = 0;
                }
                slider.scrollItem(nextIndex++);
            },
            miliseconds
        );
    }

    slider.ele.addEventListener('glider-animated', function() {
        window.clearInterval(slideTimeout);
        slide();
    });

    slide();
}

sliderAuto(glider, 3000);
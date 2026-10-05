document.addEventListener('DOMContentLoaded', () => {

    const sidebar = document.querySelector('.sidebar');
    const burger = document.getElementById('menuToggle');
    const overlay = document.getElementById('sidebarOverlay');

    if(!sidebar || !burger || !overlay) return;

    burger.addEventListener('click', () => {

        sidebar.classList.toggle('open');
        overlay.classList.toggle('open');

    });

    overlay.addEventListener('click', () => {

        sidebar.classList.remove('open');
        overlay.classList.remove('open');

    });

});

document.querySelectorAll('.sidebar a').forEach(link => {

    link.addEventListener('click', () => {

        sidebar.classList.remove('open');
        overlay.classList.remove('open');

    });

});

let startX = 0;

document.addEventListener('touchstart', e => {

    startX = e.touches[0].clientX;

});

document.addEventListener('touchend', e => {

    let endX = e.changedTouches[0].clientX;

    // ouverture

    if(startX < 30 && endX > 120){

        sidebar.classList.add('open');
        overlay.classList.add('open');

    }

    // fermeture

    if(startX > 150 && endX < 50){

        sidebar.classList.remove('open');
        overlay.classList.remove('open');

    }

});
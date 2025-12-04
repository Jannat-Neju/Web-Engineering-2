let userBox = document.querySelector('.header .flex .account-box');

document.querySelector('#user-btn').onclick = () =>{
    userBox.classList.toggle('active');
    navbar.classList.remove('active');
}

let navbar = document.querySelector('.header .flex .navbar');

document.querySelector('#menu-btn').onclick = () =>{
    navbar.classList.toggle('active');
    userBox.classList.remove('active');
}

window.onscroll = () =>{
    userBox.classList.remove('active');
    navbar.classList.remove('active');
}

// 👉 Step 3: Pause slider on hover

const sliderTrack = document.querySelector('.slider-track');

if(sliderTrack){ // check if it exists to avoid errors on pages without it
    sliderTrack.addEventListener('mouseover', () => {
        sliderTrack.style.animationPlayState = 'paused';
    });

    sliderTrack.addEventListener('mouseout', () => {
        sliderTrack.style.animationPlayState = 'running';
    });
}




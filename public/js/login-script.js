const allPupils = document.querySelectorAll('.pupil');

function moveEyes(event) {
    const mouseX = event.clientX;
    const mouseY = event.clientY;

    allPupils.forEach(pupil => {
        const eyeRect = pupil.parentElement.getBoundingClientRect();
        const eyeCenterX = eyeRect.left + eyeRect.width / 2;
        const eyeCenterY = eyeRect.top + eyeRect.height / 2;

        const deltaX = mouseX - eyeCenterX;
        const deltaY = mouseY - eyeCenterY;
        const angle = Math.atan2(deltaY, deltaX);

        const maxDistance = eyeRect.width * 0.25;
        const distanceToMouse = Math.hypot(deltaX, deltaY);
        const moveDistance = Math.min(distanceToMouse, maxDistance);

        const moveX = Math.cos(angle) * moveDistance;
        const moveY = Math.sin(angle) * moveDistance;

        pupil.style.transform = `translate(-50%, -50%) translate(${moveX}px, ${moveY}px)`;
    });
}
window.addEventListener('mousemove', moveEyes);
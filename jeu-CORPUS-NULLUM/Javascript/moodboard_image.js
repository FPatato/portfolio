const viewer = document.getElementById("viewer");
const img = document.getElementById("image");
const exitBtn = document.getElementById("exitBtn");

let scale = 1;
let posX = 0, posY = 0;
let startX = 0, startY = 0;
let isDragging = false;

function updateTransform() {
    img.style.transform = `translate(calc(-50% + ${posX}px), calc(-50% + ${posY}px)) scale(${scale})`;
}

// ======== Zoom boutons ========//
document.getElementById("zoomIn").onclick = () => {
    scale *= 1.2;
    updateTransform();
};

document.getElementById("zoomOut").onclick = () => {
    scale /= 1.2;
    updateTransform();
};

document.getElementById("reset").onclick = () => {
    scale = 1;
    posX = posY = 0;
    updateTransform();
};

// ======== Zoom molette (PC) ========//
viewer.addEventListener("wheel", e => {
    e.preventDefault();
    scale *= e.deltaY < 0 ? 1.1 : 0.9;
    updateTransform();
});

// ======== Souris : clic maintenu ========//
viewer.addEventListener("mousedown", e => {
    if (e.button !== 0) return;
    isDragging = true;
    viewer.style.cursor = "grabbing";
    startX = e.clientX - posX;
    startY = e.clientY - posY;
});

viewer.addEventListener("mousemove", e => {
    if (!isDragging) return;
    posX = e.clientX - startX;
    posY = e.clientY - startY;
    updateTransform();
});

window.addEventListener("mouseup", () => {
    isDragging = false;
    viewer.style.cursor = "grab";
 });

    // ======== TACTILE ========
let lastTouchDistance = 0;
viewer.addEventListener("touchstart", e => {
    if (e.touches.length === 1) {
        // Un seul doigt → déplacement
        isDragging = true;
        startX = e.touches[0].clientX - posX;
        startY = e.touches[0].clientY - posY;
    }
    else if (e.touches.length === 2) {
        // Deux doigts → zoom
        lastTouchDistance = getTouchDistance(e.touches);
    }
});

viewer.addEventListener("touchmove", e => {
    e.preventDefault();
    if (e.touches.length === 1 && isDragging) {
        posX = e.touches[0].clientX - startX;
        posY = e.touches[0].clientY - startY;
        updateTransform();
    } 
    else if (e.touches.length === 2) {
        const newDistance = getTouchDistance(e.touches);
        const zoomFactor = newDistance / lastTouchDistance;
        scale *= zoomFactor;
        lastTouchDistance = newDistance;
        updateTransform();
    }
});

viewer.addEventListener("touchend", () => {
    isDragging = false;
});

function getTouchDistance(touches) {
    const dx = touches[0].clientX - touches[1].clientX;
    const dy = touches[0].clientY - touches[1].clientY;
    return Math.sqrt(dx * dx + dy * dy);
}

// ======== Bouton Quitter ========
exitBtn.addEventListener("click", () => {
    // Revenir à la page précédente
    window.history.back();
    // Ou aller vers une autre page : window.location.href = "index.html";
});

// ======== Initialisation ========
updateTransform();

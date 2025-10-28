document.addEventListener("DOMContentLoaded", () => {
  const canvas = document.querySelector(".noise-overlay");
  if (!canvas) return;

  const ctx = canvas.getContext("2d", { alpha: true });
  const canvasSize = 1024;
  let frame = 0;
  let animationId;

  const patternRefreshInterval = 2; // toutes les 2 frames
  const patternAlpha = 15; // intensité du bruit (0-255)

  function resize() {
    canvas.width = canvasSize;
    canvas.height = canvasSize;
    canvas.style.width = "100vw";
    canvas.style.height = "100vh";
  }

  function drawGrain() {
    const imageData = ctx.createImageData(canvasSize, canvasSize);
    const data = imageData.data;

    for (let i = 0; i < data.length; i += 4) {
      const value = Math.random() * 255;
      data[i] = value;       // R
      data[i + 1] = value;   // G
      data[i + 2] = value;   // B
      data[i + 3] = patternAlpha; // transparence
    }

    ctx.putImageData(imageData, 0, 0);
  }

  function loop() {
    if (frame % patternRefreshInterval === 0) drawGrain();
    frame++;
    animationId = requestAnimationFrame(loop);
  }

  window.addEventListener("resize", resize);
  resize();
  loop();
});

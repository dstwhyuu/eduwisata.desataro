const hamburger = document.querySelector(".hamburger-menu");
  const overlay = document.getElementById("menuOverlay");

  hamburger.addEventListener("click", () => {
    hamburger.classList.toggle("open");
    overlay.classList.toggle("open");
  });

  // Opsional: Tutup overlay saat mengklik di luar atau pada tautan di dalam overlay
  overlay.addEventListener("click", (event) => {
      // Pastikan klik pada link di dalam overlay atau langsung pada area overlay (bukan konten di dalamnya)
      if (event.target.tagName === 'A' || event.target === overlay) {
          hamburger.classList.remove("open");
          overlay.classList.remove("open");
      }
  });

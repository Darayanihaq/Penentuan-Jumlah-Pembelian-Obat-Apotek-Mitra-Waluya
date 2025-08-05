document.addEventListener("DOMContentLoaded", function () {
  const canvas = document.getElementById("jenisObatPieChart");
  if (canvas) {
    const ctx = canvas.getContext("2d");
    new Chart(ctx, {
      type: "pie",
      data: {
        labels: window.labelJenisObat || [],
        datasets: [
          {
            label: "Jenis Obat",
            data: window.dataJenisObat || [],
            backgroundColor: [
              "#4e73df",
              "#1cc88a",
              "#36b9cc",
              "#f6c23e",
              "#e74a3b",
              "#858796",
            ],
            borderWidth: 1,
          },
        ],
      },
      options: {
        responsive: false,
        maintainAspectRatio: false,
        plugins: {
          legend: { position: "bottom" },
        },
      },
    });
  } else {
    console.warn("Canvas #jenisObatPieChart tidak ditemukan");
  }
});

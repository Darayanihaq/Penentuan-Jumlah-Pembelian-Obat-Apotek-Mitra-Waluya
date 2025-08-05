export function renderChart(labels, data, monthLabel) {
  const ctx = document.getElementById("lineChartObat");

  if (!ctx) return;

  new Chart(ctx, {
    type: "line",
    data: {
      labels: labels,
      datasets: [
        {
          label: "Jumlah Terjual",
          data: data,
          borderColor: "#36a2eb",
          backgroundColor: "#36a2eb",
          fill: false,
          tension: 0.4,
          pointRadius: 5,
          pointHoverRadius: 7,
        },
      ],
    },
    options: {
      responsive: true,
      plugins: {
        title: {
          display: true,
          text: "Grafik Penjualan Obat - Bulan " + monthLabel,
        },
        legend: {
          display: false,
        },
      },
      scales: {
        y: {
          beginAtZero: true,
          title: {
            display: true,
            text: "Jumlah Terjual",
          },
        },
        x: {
          title: {
            display: true,
            text: "Nama Obat",
          },
          ticks: {
            autoSkip: false,
            maxRotation: 45,
            minRotation: 30,
          },
        },
      },
    },
  });
}

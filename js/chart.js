// Grafik Obat Terlaris
new Chart(document.getElementById("chartObatTerlaris"), {
  type: "bar",
  data: {
    labels: labels,
    datasets: [
      {
        label: "Jumlah Terjual",
        data: dataObat,
        backgroundColor: "rgba(13,110,253,0.7)",
      },
    ],
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: { legend: { display: false } },
  },
});

// Grafik Penerimaan & Penjualan per Bulan
new Chart(document.getElementById("chartTransaksiBulanan"), {
  type: "line",
  data: {
    labels: labelBulan,
    datasets: [
      {
        label: "Penerimaan",
        data: dataPenerimaan,
        borderColor: "rgba(25,135,84,1)",
        backgroundColor: "rgba(25,135,84,0.2)",
        fill: true,
      },
      {
        label: "Penjualan",
        data: dataPenjualan,
        borderColor: "rgba(220,53,69,1)",
        backgroundColor: "rgba(220,53,69,0.2)",
        fill: true,
      },
    ],
  },
  options: {
    responsive: true,
  },
});

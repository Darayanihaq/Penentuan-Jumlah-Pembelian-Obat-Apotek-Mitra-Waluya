// File: js/gantchart.js

let chart = null;

function initKadaluwarsaChart(dataAll, defaultBulan = null) {
  const ctx = document.getElementById("chartKadaluwarsa").getContext("2d");
  const picker = document.getElementById("bulanPicker");

  const bulan = defaultBulan || picker.value;
  const dataset = dataAll[bulan] || [];

  chart = new Chart(ctx, {
    type: "bar",
    data: {
      labels: dataset.map((item) => item.obat),
      datasets: [
        {
          label: "Jumlah Kadaluwarsa",
          data: dataset.map((item) => item.jumlah),
          backgroundColor: "#4ea428a4",
        },
      ],
    },
    options: {
      indexAxis: "y",
      responsive: true,
      plugins: {
        legend: { display: false },
        title: {
          display: true,
          text: "Obat Kadaluwarsa Terbanyak - " + bulan,
        },
      },
      scales: {
        x: {
          beginAtZero: true,
          ticks: { precision: 0 },
        },
      },
    },
  });

  picker.addEventListener("change", () => {
    const selected = picker.value;
    const data = dataAll[selected] || [];
    chart.data.labels = data.map((item) => item.obat);
    chart.data.datasets[0].data = data.map((item) => item.jumlah);
    chart.options.plugins.title.text =
      "Obat Kadaluwarsa Terbanyak - " + selected;
    chart.update();
  });
}

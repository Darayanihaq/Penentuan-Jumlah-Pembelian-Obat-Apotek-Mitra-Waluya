function filterTable() {
  const searchInput = document
    .getElementById("searchInput")
    .value.toLowerCase();
  const jenisFilter = document
    .getElementById("jenisFilter")
    .value.toLowerCase();
  const tbody = document.getElementById("obatTableBody");
  const rows = tbody.getElementsByTagName("tr");

  for (let row of rows) {
    const cells = row.getElementsByTagName("td");
    if (cells.length === 1) continue;

    const namaObat = cells[2].textContent.toLowerCase();
    const jenisObat = cells[4].textContent.toLowerCase();
    const kodeObat = cells[1].textContent.toLowerCase();

    const matchSearch =
      namaObat.includes(searchInput) || kodeObat.includes(searchInput);
    const matchJenis = jenisFilter === "" || jenisObat === jenisFilter;

    row.style.display = matchSearch && matchJenis ? "" : "none";
  }
}

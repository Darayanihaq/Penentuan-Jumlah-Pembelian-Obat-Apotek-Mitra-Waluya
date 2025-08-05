function filterTableByStatus() {
  const selectedStatus = document.getElementById("statusFilter").value;
  const rows = document.querySelectorAll("table tbody tr");

  rows.forEach((row) => {
    const status = row.getAttribute("data-status");
    if (!selectedStatus || status === selectedStatus) {
      row.style.display = "";
    } else {
      row.style.display = "none";
    }
  });
}

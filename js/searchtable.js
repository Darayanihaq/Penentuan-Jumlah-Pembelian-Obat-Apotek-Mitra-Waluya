function searchTable(inputId, tableBodyId) {
  const input = document.getElementById(inputId);
  const filter = input.value.toLowerCase();
  const rows = document.querySelectorAll(`#${tableBodyId} tr`);

  rows.forEach((row) => {
    const rowText = row.textContent.toLowerCase();
    row.style.display = rowText.includes(filter) ? "" : "none";
  });
}

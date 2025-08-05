document.addEventListener("DOMContentLoaded", function () {
  const links = document.querySelectorAll(".nav-link");
  const currentPath = window.location.pathname;

  links.forEach((link) => {
    const href = link.getAttribute("href");

    if (href) {
      // Ambil hanya path dari URL absolut
      const linkPath = new URL(href, window.location.origin).pathname;

      // Bandingkan path saat ini dengan href menu
      if (currentPath === linkPath) {
        link.classList.add("active-menu");

        // Jika berada dalam submenu, tampilkan induknya
        const parentCollapse = link.closest(".collapse");
        if (parentCollapse && !parentCollapse.classList.contains("show")) {
          parentCollapse.classList.add("show");
        }
      }
    }
  });

  // Tambahan fallback: buka menu jika path mengandung keyword tertentu
  if (["obat", "supplier", "user"].some((k) => currentPath.includes(k))) {
    const master = document.getElementById("masterData");
    if (master && !master.classList.contains("show")) {
      master.classList.add("show");
    }
  }

  if (
    ["penerimaan", "penjualan", "pembelian"].some((k) =>
      currentPath.includes(k)
    )
  ) {
    const transaksi = document.getElementById("transaksiData");
    if (transaksi && !transaksi.classList.contains("show")) {
      transaksi.classList.add("show");
    }
  }
});

let navbar = document.querySelector(".header .flex .navbar");
let profile = document.querySelector(".header .flex .profile");

document.querySelector("#menu-btn").onclick = () => {
  navbar.classList.toggle("active");
  profile.classList.remove("active");
};

document.querySelector("#user-btn").onclick = () => {
  profile.classList.toggle("active");
  navbar.classList.remove("active");
};

window.onscroll = () => {
  navbar.classList.remove("active");
  profile.classList.remove("active");
};

let mainImage = document.querySelector(
  ".quick-view .box .row .image-container .main-image img"
);
let subImages = document.querySelectorAll(
  ".quick-view .box .row .image-container .sub-image img"
);

subImages.forEach((image) => {
  image.onclick = () => {
    let src = image.getAttribute("src");
    mainImage.src = src;
  };
});

// Fungsi untuk menghitung ongkir
function calculateOngkir(state, total_quantity) {
  let ongkir_jawa_dki = [
    "jawa barat",
    "DKI Jakarta",
    "Jawa Barat",
    "dki jakarta",
  ];
  let base_ongkir = ongkir_jawa_dki.includes(state.toLowerCase())
    ? 10000
    : 28000;
  let total_weight = Math.ceil(total_quantity / 5);
  return base_ongkir * total_weight;
}

// Fungsi untuk memformat harga menjadi Rupiah
function formatRupiah(angka) {
  return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// Ambil nilai total_quantity dan grand_total dari elemen HTML
let totalQuantityElement = document.getElementById("total_quantity");
let grandTotalElement = document.getElementById("grand_total");

// Script untuk mengubah ongkir dan grand total saat provinsi diubah
document.getElementById("state").addEventListener("change", function () {
  let state = this.value;
  let total_quantity = parseInt(totalQuantityElement.value); // Ambil nilai dari elemen input, pastikan di-parse ke integer jika perlu
  let ongkir = calculateOngkir(state, total_quantity);
  document.getElementById("ongkir").textContent =
    "Rp." + formatRupiah(ongkir) + ".";
  let grand_total = parseFloat(grandTotalElement.value); // Ambil nilai dari elemen input, pastikan di-parse ke float jika perlu
  document.getElementById("grand_total").textContent =
    "Rp." + formatRupiah(grand_total + ongkir) + ".";
});

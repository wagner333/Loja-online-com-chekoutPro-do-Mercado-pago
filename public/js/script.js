document.addEventListener("DOMContentLoaded", () => {
  const openCart = document.getElementById("openCart");
  const closeCart = document.getElementById("closeCart");
  const cartDrawer = document.getElementById("cartDrawer");
  const cartOverlay = document.getElementById("cartOverlay");

  if (!openCart || !closeCart || !cartDrawer || !cartOverlay) {
    console.error("Elementos do carrinho não encontrados");
    return;
  }

  openCart.addEventListener("click", () => {
    cartDrawer.classList.add("active");
    cartOverlay.classList.add("active");
    document.body.style.overflow = "hidden"; // trava scroll
  });

  const close = () => {
    cartDrawer.classList.remove("active");
    cartOverlay.classList.remove("active");
    document.body.style.overflow = "";
  };

  closeCart.addEventListener("click", close);
  cartOverlay.addEventListener("click", close);
});
document.addEventListener("DOMContentLoaded", () => {
  const slides = document.querySelectorAll(".banner-slide");
  const dots = document.querySelectorAll(".banner-dots .dot");
  const next = document.querySelector(".banner-next");
  const prev = document.querySelector(".banner-prev");

  let index = 0;
  let interval;

  function showSlide(i) {
    slides.forEach((slide) => slide.classList.remove("active"));
    dots.forEach((dot) => dot.classList.remove("active"));

    slides[i].classList.add("active");
    dots[i].classList.add("active");
    index = i;
  }

  function nextSlide() {
    let i = index + 1;
    if (i >= slides.length) i = 0;
    showSlide(i);
  }

  function prevSlide() {
    let i = index - 1;
    if (i < 0) i = slides.length - 1;
    showSlide(i);
  }

  dots.forEach((dot, i) => {
    dot.addEventListener("click", () => {
      showSlide(i);
      resetInterval();
    });
  });

  next.addEventListener("click", () => {
    nextSlide();
    resetInterval();
  });

  prev.addEventListener("click", () => {
    prevSlide();
    resetInterval();
  });

  function startInterval() {
    interval = setInterval(nextSlide, 5000);
  }

  function resetInterval() {
    clearInterval(interval);
    startInterval();
  }

  startInterval();
});

document.addEventListener("DOMContentLoaded", () => {
  const modal = document.getElementById("produtoModal");
  const closeModal = document.getElementById("closeModal");

  const modalNome = document.getElementById("modalNome");
  const modalPreco = document.getElementById("modalPreco");
  const modalDescricao = document.getElementById("modalDescricao");
  const modalImagem = document.getElementById("modalImagemPrincipal");
  const modalThumbs = document.getElementById("modalThumbs");
  const modalProdutoId = document.getElementById("modalProdutoId");
  const modalCores = document.getElementById("modalCores");
  const modalTamanhos = document.getElementById("modalTamanhos");

  document.querySelectorAll(".produto-card").forEach((card) => {
    card.addEventListener("click", () => {
      const imagens = JSON.parse(card.dataset.imagens || "[]");
      const cores = JSON.parse(card.dataset.cores || "[]");
      const tamanhos = JSON.parse(card.dataset.tamanhos || "[]");

      modalNome.textContent = card.dataset.nome;
      modalPreco.textContent = card.dataset.preco;
      modalDescricao.textContent = card.dataset.descricao;
      modalProdutoId.value = card.dataset.id;

      // ===== Galeria =====
      modalThumbs.innerHTML = "";

      if (imagens.length > 0) {
        modalImagem.src = "/img/" + imagens[0];
      } else {
        modalImagem.src = "/img/placeholder.webp";
      }

      imagens.forEach((img) => {
        const thumb = document.createElement("img");
        thumb.src = "/img/" + img;
        thumb.addEventListener("click", () => {
          modalImagem.src = thumb.src;
        });
        modalThumbs.appendChild(thumb);
      });

      // ===== Cores =====
      modalCores.innerHTML = "";
      modalCores.dataset.selected = "";

      cores.forEach((cor) => {
        const span = document.createElement("span");
        span.classList.add("opcao");
        span.textContent = cor;

        span.addEventListener("click", () => {
          modalCores
            .querySelectorAll(".opcao")
            .forEach((el) => el.classList.remove("active"));

          span.classList.add("active");
          modalCores.dataset.selected = cor;
        });

        modalCores.appendChild(span);
      });

      // ===== Tamanhos =====
      modalTamanhos.innerHTML = "";
      modalTamanhos.dataset.selected = "";

      tamanhos.forEach((tam) => {
        const span = document.createElement("span");
        span.classList.add("opcao");
        span.textContent = tam;

        span.addEventListener("click", () => {
          modalTamanhos
            .querySelectorAll(".opcao")
            .forEach((el) => el.classList.remove("active"));

          span.classList.add("active");
          modalTamanhos.dataset.selected = tam;
        });

        modalTamanhos.appendChild(span);
      });

      modal.classList.add("active");
      document.body.style.overflow = "hidden";
    });
  });

  closeModal.addEventListener("click", fecharModal);
  modal.addEventListener("click", (e) => {
    if (e.target === modal) fecharModal();
  });

  function fecharModal() {
    modal.classList.remove("active");
    document.body.style.overflow = "";
  }
});

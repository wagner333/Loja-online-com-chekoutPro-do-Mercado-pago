<div class="modal-overlay" id="produtoModal">

    <div class="modal-content">

        <button class="modal-close" id="closeModal">✕</button>

        <div class="modal-body">

            <!-- Galeria -->
            <div class="modal-galeria">
                <img id="modalImagemPrincipal" src="" alt="">
                <div class="modal-thumbs" id="modalThumbs"></div>
            </div>

            <!-- Info -->
            <div class="modal-info">
                <h2 id="modalNome"></h2>

                <p class="modal-preco">
                    R$ <span id="modalPreco"></span>
                </p>

                <p class="modal-descricao" id="modalDescricao"></p>

                <!-- Cores -->
                <div class="modal-opcoes">
                    <strong>Cores:</strong>
                    <div id="modalCores"></div>
                </div>

                <div class="modal-opcoes">
                    <strong>Tamanhos:</strong>
                    <div id="modalTamanhos"></div>
                </div>



                <form method="post" action="/add-cart">
                    <input type="hidden" name="id" id="modalProdutoId">
                    <button type="submit" class="btn-comprar">
                        Adicionar ao carrinho
                    </button>
                </form>
            </div>

        </div>

    </div>
</div>
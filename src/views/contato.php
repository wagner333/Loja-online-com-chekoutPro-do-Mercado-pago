<section class="contato-loja">
    <h1>Fale Conosco</h1>

    <p class="contato-texto">
        Tem alguma dúvida, sugestão ou precisa de ajuda com seu pedido?
        Nossa equipe está pronta para te atender e oferecer a melhor experiência.
    </p>

    <form class="contato-form" action="#" method="post">
        <div class="form-group">
            <label for="nome">Nome</label>
            <input type="text" id="nome" name="nome" placeholder="Seu nome completo" required>
        </div>

        <div class="form-group">
            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" placeholder="seu@email.com" required>
        </div>

        <div class="form-group">
            <label for="mensagem">Mensagem</label>
            <textarea id="mensagem" name="mensagem" rows="5" placeholder="Escreva sua mensagem" required></textarea>
        </div>

        <button type="submit" class="btn-enviar">
            Enviar mensagem
        </button>
    </form>
</section>
<style>
.contato-loja {
    max-width: 600px;
    margin: 80px auto;
    padding: 0 20px;
    text-align: center;
}

.contato-loja h1 {
    font-size: 36px;
    font-weight: 700;
    letter-spacing: 1px;
    margin-bottom: 16px;
    text-transform: uppercase;
}

.contato-texto {
    font-size: 17px;
    color: #555;
    line-height: 1.6;
    margin-bottom: 40px;
}

/* Formulário */
.contato-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.form-group {
    text-align: left;
}

.form-group label {
    display: block;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 6px;
    color: #222;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 12px 14px;
    font-size: 15px;
    border-radius: 10px;
    border: 1px solid #ddd;
    outline: none;
    transition: border 0.2s ease, box-shadow 0.2s ease;
}

.form-group input:focus,
.form-group textarea:focus {
    border-color: #000;
    box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.08);
}

/* Botão */
.btn-enviar {
    margin-top: 10px;
    padding: 14px;
    font-size: 15px;
    font-weight: 600;
    border: none;
    border-radius: 12px;
    background: #000;
    color: #fff;
    cursor: pointer;
    transition: transform 0.2s ease, opacity 0.2s ease;
}

.btn-enviar:hover {
    transform: translateY(-2px);
    opacity: 0.9;
}

/* Mobile */
@media (max-width: 600px) {
    .contato-loja h1 {
        font-size: 28px;
    }

    .contato-texto {
        font-size: 16px;
    }
}
</style>
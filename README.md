![Nora_page-0001](https://github.com/user-attachments/assets/646d5a24-75d4-4392-b241-028d6167207b)# Loja online Com o Chekout Pro do Mercado-pago
Ultimamente, eu estava cansado de fazer projetos simples, com base em <strong>CRUDs</strong> ou qualquer coisa aleatória. Resolvi fazer este projeto com o <strong>SDK do Mercado Pago</strong> para conseguir me desenvolver, principalmente com <strong>gateways de pagamento</strong>. Pretendo criar alguns <strong>micro-SaaS</strong> futuramente, e para mim fica muito complicado criar usando <strong>Stripe</strong> ou qualquer outro.

Enfim, a loja <strong>não está completa</strong>, mas está no ponto de <strong>configurar o banco de dados</strong>. Por enquanto, os <strong>produtos estão em um array</strong> em um dos includes. Fiz a parte de <strong>dashboard do admin</strong>, mas não coloquei nada especial.

A única parte que, por enquanto, está usando <strong>SQLite</strong> é a parte do <strong>cadastro da localização do usuário</strong>.

<strong>Não apliquei webhooks</strong> porque ainda não sei utilizá-los; com o tempo vou alterando os dados salvos aqui no <strong>GitHub</strong> no banco. Claro, o ideal seria usar o <strong>.gitignore</strong> para não permitir a entrada dele, mas como o banco <strong>não possui nenhum dado ou credencial privada</strong>, deixei passar. O banco serve apenas para <strong>salvar a localização</strong>.

Tenho que usar <strong>AJAX</strong> na parte do <strong>carrinho</strong>, pois quando adicionamos algo ao carrinho a <strong>página reinicia</strong>.

O <strong>sistema de rotas</strong> é bem simples, usando <strong>include das views</strong>. O certo seria criar uma rota mais organizada, com <strong>métodos HTTP</strong>, etc., mas normalmente, quando faço esse tipo de estrutura, <strong>demoro mais tempo criando ela do que desenvolvendo o projeto</strong>.

Claro, criei algumas estruturas, mas minha falta de <strong>documentação</strong> faz eu acabar <strong>esquecendo como elas funcionam</strong>.

Enfim, vou colocar algumas <strong>imagens do projeto</strong> abaixo, mas creio que ele pode <strong>melhorar muito</strong> depois. Agora é só eu ter <strong>menos preguiça</strong> de trabalhar nele

obss: esse texto foi passado no gpt pra resolver alguns erros de portugues e adicionar a tag html STRONG eu normalmente evito usar isso por que tira a essencia do que eu escrevi mas enfim fotos abaixo:

# parte frontal da loja

![Nora_page-0008](https://github.com/user-attachments/assets/5d69d740-3741-461f-8e04-8df4f9b94560)
![Nora_page-0007](https://github.com/user-attachments/assets/5a871b95-4b8f-46d5-8105-3aab065c8f38)
![Nora_page-0006](https://github.com/user-attachments/assets/884a214f-7261-45e3-ac7c-12c958df12d0)
![Nora_page-0005](https://github.com/user-attachments/assets/7d422ce7-39c1-4c85-b090-0379c3bf79f0)
![Nora_page-0004](https://github.com/user-attachments/assets/62b06739-450c-44f4-8c98-7926c8586fc7)
![Nora_page-0003](https://github.com/user-attachments/assets/2ce744a0-35b9-4ab1-8f48-4d4d9cebf802)
![Nora_page-0002](https://github.com/user-attachments/assets/d7d09818-4dc8-44b6-936b-18a0dcf00ea9)
![Nora_page-0001](https://github.com/user-attachments/assets/1094eb0f-92bf-49e8-bdbc-b158c69328ea)


# produto

<img width="1366" height="606" alt="2026-01-31-102428_1366x606_scrot" src="https://github.com/user-attachments/assets/243bba27-1602-4506-9ab5-658b2db58974" />
<img width="1366" height="659" alt="2025-12-13-195906_1366x659_scrot" src="https://github.com/user-attachments/assets/6866300c-c450-4d4a-b31e-7f5524a2723c" />





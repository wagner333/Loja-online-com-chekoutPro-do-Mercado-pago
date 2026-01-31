# Loja online Com o Chekout Pro do Mercado-pago
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
![Nora_page-0001](https://github.com/user-attachments/assets/1094eb0f-92bf-49e8-bdbc-b158c69328ea)


# produto

<img width="1366" height="606" alt="2026-01-31-102428_1366x606_scrot" src="https://github.com/user-attachments/assets/243bba27-1602-4506-9ab5-658b2db58974" />

<img width="769" height="632" alt="2026-01-31-102523_769x632_scrot" src="https://github.com/user-attachments/assets/863de30b-31c4-4a7e-81c4-d449ec07de9f" />


# chekout

<img width="1366" height="617" alt="2026-01-31-102827_1366x617_scrot" src="https://github.com/user-attachments/assets/05425cbe-ec6d-4282-8439-34768cd95bc2" />

<img width="1364" height="608" alt="2026-01-31-102926_1364x608_scrot" src="https://github.com/user-attachments/assets/f8c4897e-ec92-4303-9f31-c2d0de288ce9" />



<img width="1355" height="609" alt="2026-01-31-103130_1355x609_scrot" src="https://github.com/user-attachments/assets/82f3dda6-764b-41d6-a848-397e2171a602" />


<img width="1366" height="628" alt="2026-01-31-103324_1366x628_scrot" src="https://github.com/user-attachments/assets/c9709677-125f-40f2-82ce-0012e62ce273" />





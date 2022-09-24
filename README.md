# Api-Credit-Analysis

## Do que se trata?
Este projeto se trata de uma API RESTfull desenvolvida em Laravel com funcionalidade de analise de crédito para liberação de aluguel.

### Como executar

Baixar repositório
```sh
git clone https://github.com/KelvinSeverino/api-credit-analysis.git
```

Acessar diretório do projeto
```sh
cd api-credit-analysis
```

Iniciar os containers
```sh
docker-compose up -d
```

Acessar o container do projeto
```sh
docker-compose exec app bash
```

Executar comando para gerar tabelas no banco
```sh
php artisan migrate
```

Feito os passos acima, você já poderá consumir os endpoints abaixo:

#### Realiza analise de crédito

```http
  POST /api/analise-credito
```

| Parâmetro   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `name` | `string` | **Obrigatório**. Nome do Cliente |
| `cpf` | `string` | **Obrigatório**. XXX.XXX.XXX-XX |
| `negative` | `boolean` | **Obrigatório**. 0 ou 1 |
| `salary` | `float` | **Obrigatório**. Valor do Salário (1500.00) |
| `limit_card` | `float` | **Obrigatório**. Limite do Cartão de Crédito (1500.00) |
| `rent_value` | `float` | **Obrigatório**. Valor do Aluguel (1500.00) |
| `street` | `string` | **Obrigatório**. Logradouro |
| `street_number` | `int` | **Obrigatório**. Número |
| `county` | `string` | **Obrigatório**. Município |
| `state` | `string` | **Obrigatório**. Sigla do Estado (SP) |
| `cep` | `string` | **Obrigatório**. CEP |

#### Retorna a ultima avaliação de crédito do CPF

```http
  POST /api/analise-credito/consulta
```

| Parâmetro   | Tipo       | Descrição                                   |
| :---------- | :--------- | :------------------------------------------ |
| `cpf`      | `string` | **Obrigatório**. XXX.XXX.XXX-XX |


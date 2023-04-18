[comment]: <> "LTeX: language=pt-pt"

# Criar uma aplicação CodeIgniter

Vamos usar o [composer](https://getcomposer.org/) para isto seguindo os passos presentes na [documentação do CI](https://codeigniter.com/user_guide/installation/installing_composer.html).
É uma tarefa fácil:

``` sh
composer create-project codeigniter4/appstarter cidb-example
cd ./cidb-example
```

Estou a usar a versão 4.3.3 do CI.

## O que é o Composer?

O composer é um gerenciador de pacotes/dependências de bibliotecas PHP.
É o que o npm é para o JavaScript.

# Preparar a aplicação

## $baseURL

Editei a variável `$baseURL` no ficheiro [app/Config/App.php](./app/Config/App.php):

``` php
class App extends BaseConfig
{
    /**
     * --------------------------------------------------------------------------
     * Base Site URL
     * --------------------------------------------------------------------------
     *
     * URL to your CodeIgniter root. Typically, this will be your base URL,
     * WITH a trailing slash:
     *
     *    http://example.com/
     */
    public string $baseURL = "http://$_SERVER[SERVER_NAME]/";

    // ...
}
```

## Configuração para base de dados

No ficheiro [app/Config/Database.php](./app/Config/Database.php) é onde vamos
definir as diferentes conexões a bases de dados.

Se abrirem o ficheiro vão encontrar a classe `Database`.

A propriedade `$defaultGroup` é uma string para especificar a propriedade que
define a conexão para usar como padrão.

Depois existem duas propriedades, `$default` e `$tests`, que são exemplos de
configuração a bases de dados, a primeira uma base de dados de base MySQL e a
outra SQLite. Identifico isto facilmente pelo driver especificado.

Aqui vocês têm que saber com que base de dados é que vão querer trabalhar
obviamente (e dentro dos drivers suportados pelo
[PDO](https://www.php.net/manual/en/pdo.drivers.php), e a tua versão do
[CI](https://codeigniter.com/user_guide/intro/requirements.html#requirements-supported-databases))

Usem o array `$default` como base para o que tenham que preencher:
* `'hostname'` - domínio da base de dados;
* `'username'` - username do user que será usado para a conexão;
* `'password'` - password do user acima especificado;
* `'database'` - A base de dados a que se querem conectar;
* `'DBDriver'` - O driver correspondente à DB que vão usar (`MySQLi`, `OCI8`,
  `Postgre`, `SQLSRV`, `SQLite3`);
* `'DBPrefix'` - Pode ser útil ao fazer conexções à mesma DB. Quando o CI
  estiver a criar a SQL query, este prefixo será anexado ao nome da tabela;
* `'pConnect'` - Se quiserem que a conexão à DB seja;
  [persistente](https://www.php.net/manual/en/features.persistent-connections.php);
* `'DBDebug'` - Se queres que `\Exceptions` sejam `throw`ned caso algum erro aconteça;
* `'encrypt'` - Para usar uma conexão encriptada;
* `'port'` - A porta para usar na conexão à base de dados;

Estes não são todas as opções, mas talvez sejam as mais importantes.
As opções estão melhor explicadas [aqui](https://codeigniter.com/user_guide/database/configuration.html#explanation-of-values).

Outra forma de conectar que talvez dê jeito de usar é a opção `'DSN'` onde tu podes especificar o `$dsn` que tu passarias no `\PDO::__constructor`.

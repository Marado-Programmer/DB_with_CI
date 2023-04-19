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
    //public string $baseURL = "http://" . ($_SERVER["SERVER_NAME"] ?? "localhost:8080") . "/";
    public string $baseURL = "http://localhost:8080/";

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

Também é possível usar variáveis de ambiente para configurar as credencias para a base de dados como é mostrado na documentação.

# Migrations

Migrations servem para facielmente gerir schemas das tuas DB de versões para
versões.

Os nomes dos ficheiros vêm com a data e hora anexadas para o CI conseguir saber
a ordem de execução das migrações ou em caso de ser necessário dar downgrade a
DB saber fazê-lo corretamente.

Obviamente que por si o CI não faz magia e por isso é preciso o programador
saber o que está a fazer.

Para começar podemos usar o `spark` para facilitar a criação do ficheiro de
Migrations:

``` sh
php spark make:migration InitDB
```

Agora vamos criar o schema para a nossa DB:

``` sh
xdg_open ./app/Database/Migrations/YYYY-MM-DD-HHIISS_InitDB.php
```

Exemplo de um [`Migration`](./app/Database/Migrations/2023-04-18175000_InitDB.php)

No exemplo acima podemos ver uma class que dá extends ao
`\CodeIgniter\Database\Migration` e por isso tivemos the implementar o
`Migration::up()` e o `Migration::down()`.

O nome deve explicar por si, mas, o método `up` serve para dar upgrade à DB e o
método `down` para dar downgrade assim tornando fácil migrar entre versões de
esquemas de DB.

Por herança uma class `Migration` têm acesso ao `$this->forge` do tipo
`\CodeIgniter\Database\Forge` e ao `$this->db` do tipo
`\CodeIgniter\Database\ConnectionInterface`.

## Migrar para a última versão com o PHP

``` php
/** @var \CodeIgniter\Database\MigrationRunner $migrate */
$migrate = \Config\Services::migrations();

try {
    $migrate->latest();
} catch (\Throwable $e) {
    // Do something with the error here...
}
```

# Seeders

Depois de criares o teu schema talvez tu querias populá-la.

É para isso que a class `\CodeIgniter\Database\Seeder` serve. O método
`Seeder::run()` é o que vai ser chamado quando quiseres usar um `Seeder`.

Um `Seeder` também dá herança às mesmas propriedades que foram mencionadas no
`\CodeIgniter\Database\Migration`.

O método `Seeder::call()` premite executar um `Seeder` dentro de outro.

``` sh
php spark make:seeder foo
xdg_open ./app/Database/Seeds/Foo.php
```
Exemplo de um [`Seeder`](./app/Database/Seeds/CatFactsSeeder.php)

## Usar `Seeder` com o PHP

``` php
<?php

$seeder = \Config\Database::seeder();
$seeder->call('TestSeeder');
```

# Class `\CodeIgniter\Database\BaseConnection`

Para conseguires uma instância podes usar o `\Config\Database::connect()`. Ele
recebe alguns parâmetros, mas o mais importante será o primeiro onde podes
especificar qual conexão é que queres usar daquelas especificadas na
configuração do inicio.

``` php
<?php

$db = \Config\Database::connect(); // default DB
```

## Métodos

### `BaseConnection::query()`

Executa raw SQL e retorna uma `\CodeIgniter\Database\BaseResult` ou uma `\CodeIgniter\Database\Query`

### `BaseConnection::escape()`

Adiciona quotes ao valor passado. Pode dar jeito já que diferentes DB podem ter
diferentes formas de fazer escape aos valores

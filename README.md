# Arquivei Download Manager

Essa biblioteca é responsável por receber informações do google storage (bucket, path e storage path) e realizar
o download do objeto por meio de um link gerado publicamente, que expira após uma quantidade de tempo configurável,
permitindo que o cliente execute o download diretamente do storage e de modo seguro.

### Instalação

Você pode adicionar a biblioteca ao seu projeto via composer, com o comando:

`composer require arquivei/download-manager`
___

### Configurações

Para que a conexão ao Google Storage seja possível seu projeto deve possuir as seguintes variáveis de ambiente:

```dotenv
GOOGLE_CLOUD_PROJECT_ID=
GOOGLE_CLOUD_KEY_FILE=
```
`GOOGLE_CLOUD_PROJECT_ID` representa o projeto do GCS que conterá o objeto a ser baixado
`GOOGLE_CLOUD_KEY_FILE` representa o path do arquivo de configuração para autenticação ao google apis

___

### Utilização

O downloader é extremamente simples de ser instanciado e executado. Segue exemplo de como realizar
o download de um objeto:

```php
$bucket = 'my-bucket';
$basePath = 'files';
$storagePath = 'my-company/my-file.xml';

$downloader = new \Arquivei\DownloadManager\Download\Downloader();
$downloader->download($bucket, $basePath, $storagePath);
```

Caso você queira obter apenas o link de download, ao invés de realizar o download do objeto diretamente, 
basta assinar o objeto e ter o link publico como retorno:
```php
$bucket = 'my-bucket';
$basePath = 'files';
$storagePath = 'my-company/my-file.xml';
$expireSeconds = 60;

$downloader = new \Arquivei\DownloadManager\Download\Downloader();
$signedUrl = $downloader->signObject($bucket, $basePath, $storagePath, $expireSeconds);
```

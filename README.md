# Web API Base SDK PHP

For use when creating either project specific SDKs or for use in our [Web API Base SDK Bundle](https://github.com/northamericanbancard/web-api-base-sdk-bundle).

## Getting Started

Follow the below instructions in order to successfully include and utilize this library within your project.

### Prerequisites

Ensure you are running PHP >= 5.5.

If any endpoints you are calling are to be secured via IAM, ensure you have the IAM user's 
access key and secret key. If required, you should also obtain the `x-api-key` header's API key (found in API Gateway).

If you are accessing api endpoints that are secured via JWT, then ensure you obtain the proper JWT for your project.

*_NOTE_*: Regardless if you are using AWS as your client, you will still have the ability to utilize the `x-api-key` header
to supply an API key, if you wish.

### Installing

#### Composer

1. Include this library in your project by running `composer require nab/web-api-base-sdk-php`.

#### API Calls:

The allowed calls you can make on the service are noted as:

1. httpGet(string $url, array $queryParams = [], array $headers = [], $body = null)
2. httpPost(string $url, array $queryParams = [], array $headers = [], $body = null)
2. httpPut(string $url, array $queryParams = [], array $headers = [], $body = null)

#### IAM Secured Endpoints

Example Calls:

```php
// SignatureV4 defaults to use-east-1, execute-api in the constructor.
$service = new AwsApiGatewayClient('http://example.com', new SignatureV4(), new Credentials('access_key', 'secret_key'), 'x-api-key'|null, [] /*optional guzzle config*/);
 
$service->httpGet('/some/path', ['foo' => 'bar', 'baz' => 'bing'], ['Accept' => 'application/json'])
$service->httpPost('/some/path', ['foo' => 'bar', 'baz' => 'bing'], ['Accept' => 'application/json'], '{"a": "b"}')
```

**_NOTE_** The client services are an extension of `GuzzleHttp\Client`, and therefore returns an instance of
`\Psr\Http\Message\ResponseInterface`. These classes do NOT throw exceptions on non-200 responses, but rather
suppress exceptions with the intent for you to retrieve error data from the status code and response body.

#### JWT Secured Endpoints

*_Note_*: JWT Tokens will exist in each request under the header `Authorization: Bearer` as `Authorization: Bearer <MY_TOKEN>`

Example Calls:

```php
$service = new JwtClient('http://example.com', 'my.jwt.token', 'x-api-key'|null, [] /*optional guzzle config*/);
 
$service->httpGet('/some/path', ['foo' => 'bar', 'baz' => 'bing'], ['Accept' => 'application/json'])
$service->httpPost('/some/path', ['foo' => 'bar', 'baz' => 'bing'], ['Accept' => 'application/json'], '{"a": "b"}')
```

#### Basic Auth Secured Endpoints

*_Note_*: Auth will exist in each request under the header `Authorization: Bearer` as `Authorization: Bearer <MY_HASH>`

Example Calls:

```php
$service = new JwtClient('http://example.com', 'username', 'password', 'x-api-key'|null, [] /*optional guzzle config*/);
 
$service->httpGet('/some/path', ['foo' => 'bar', 'baz' => 'bing'], ['Accept' => 'application/json'])
$service->httpPost('/some/path', ['foo' => 'bar', 'baz' => 'bing'], ['Accept' => 'application/json'], '{"a": "b"}')
```

#### Unsecured Endpoints

Example Calls:

```php
$service = new SimpleClient('http://example.com', 'x-api-key'|null, [] /*optional guzzle config*/);
 
$service->httpGet('/some/path', ['foo' => 'bar', 'baz' => 'bing'], ['Accept' => 'application/json'])
$service->httpPost('/some/path', ['foo' => 'bar', 'baz' => 'bing'], ['Accept' => 'application/json'], '{"a": "b"}')
```

**_NOTE_** The client services are an extension of `GuzzleHttp\Client`, and therefore returns an instance of
`\Psr\Http\Message\ResponseInterface`. These classes do NOT throw exceptions on non-200 responses, but rather
supress exceptions with the intent for you to retrieve error data from the status code and response body.

## Development

**_First Install/Make Introduction_**

1.  Ensure you have autoconf (v2.69 at min) installed.
2.  In the project root, run `make` to install your new project.
3.  View the Makefile, or Makefile.in for further commands and required inputs.
4.  Any edits to Makefile should be done in Makefile.in and then run `autoconf && ./configure && make`

## Testing and Maintenance

The following command should be run often, and should be run during every code review:

```bash
make test
```

OR

```bash
make docker-test
```

OR

```bash
make phpcs
```

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/northamericanbancard/web-api-base-sdk/tags). 

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

# Common / HTTP

HTTP methods and IANA status codes.

Namespace prefix: `BensonDevs\SuperchargedEnums\Common\Http\`

## Enums

### `HttpMethod`

`BensonDevs\SuperchargedEnums\Common\Http\HttpMethod` · backing: `string` · default: `Get` (`get`)

Common HTTP request methods. Backing values are lowercase English slugs (HTTP verbs).

| Case | Backing |
|------|--------|
| `Get` | `get` |
| `Head` | `head` |
| `Post` | `post` |
| `Put` | `put` |
| `Patch` | `patch` |
| `Delete` | `delete` |
| `Options` | `options` |
| `Trace` | `trace` |
| `Connect` | `connect` |

### `HttpStatusCode`

`BensonDevs\SuperchargedEnums\Common\Http\HttpStatusCode` · backing: `int` · default: `Continue` (`100`)

IANA-assigned HTTP response status codes (RFC 9110 registry). Case declaration order is numeric ascending; `default()` is `Continue` (100). Backing values are the numeric status codes. Use `series()` and the `is*()` helpers for RFC status classes (1xx–5xx).

| Case | Backing |
|------|--------|
| `Continue` | `100` |
| `SwitchingProtocols` | `101` |
| `Processing` | `102` |
| `EarlyHints` | `103` |
| `Ok` | `200` |
| `Created` | `201` |
| `Accepted` | `202` |
| `NonAuthoritativeInformation` | `203` |
| `NoContent` | `204` |
| `ResetContent` | `205` |
| `PartialContent` | `206` |
| `MultiStatus` | `207` |
| `AlreadyReported` | `208` |
| `ImUsed` | `226` |
| `MultipleChoices` | `300` |
| `MovedPermanently` | `301` |
| `Found` | `302` |
| `SeeOther` | `303` |
| `NotModified` | `304` |
| `UseProxy` | `305` |
| `Unused306` | `306` |
| `TemporaryRedirect` | `307` |
| `PermanentRedirect` | `308` |
| `BadRequest` | `400` |
| `Unauthorized` | `401` |
| `PaymentRequired` | `402` |
| `Forbidden` | `403` |
| `NotFound` | `404` |
| `MethodNotAllowed` | `405` |
| `NotAcceptable` | `406` |
| `ProxyAuthenticationRequired` | `407` |
| `RequestTimeout` | `408` |
| `Conflict` | `409` |
| `Gone` | `410` |
| `LengthRequired` | `411` |
| `PreconditionFailed` | `412` |
| `ContentTooLarge` | `413` |
| `UriTooLong` | `414` |
| `UnsupportedMediaType` | `415` |
| `RangeNotSatisfiable` | `416` |
| `ExpectationFailed` | `417` |
| `Unused418` | `418` |
| `MisdirectedRequest` | `421` |
| `UnprocessableContent` | `422` |
| `Locked` | `423` |
| `FailedDependency` | `424` |
| `TooEarly` | `425` |
| `UpgradeRequired` | `426` |
| `PreconditionRequired` | `428` |
| `TooManyRequests` | `429` |
| `RequestHeaderFieldsTooLarge` | `431` |
| `UnavailableForLegalReasons` | `451` |
| `InternalServerError` | `500` |
| `NotImplemented` | `501` |
| `BadGateway` | `502` |
| `ServiceUnavailable` | `503` |
| `GatewayTimeout` | `504` |
| `HttpVersionNotSupported` | `505` |
| `VariantAlsoNegotiates` | `506` |
| `InsufficientStorage` | `507` |
| `LoopDetected` | `508` |
| `NetworkAuthenticationRequired` | `511` |

**Enum-specific methods**

```php
use BensonDevs\SuperchargedEnums\Common\Http\HttpStatusCode;

$status = HttpStatusCode::NotFound;

$status->series();              // 4 — HTTP status series (1–5) from the numeric code
$status->isInformational();     // false — true when series is 1xx
$status->isSuccess();           // false — true when series is 2xx
$status->isRedirect();          // false — true when series is 3xx
$status->isClientError();       // true — true when series is 4xx
$status->isServerError();       // false — true when series is 5xx
$status->isError();             // true — true for 4xx or 5xx
```


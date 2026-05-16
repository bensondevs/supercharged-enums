<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Common\Http;

use BensonDevs\SuperchargedEnums\EnumExtension;

/**
 * IANA-assigned HTTP response status codes (RFC 9110 registry).
 *
 * Case declaration order is numeric ascending; {@see EnumExtension::default()} is Continue (100).
 * Backing values are the numeric status codes. Use {@see series()} and the `is*()` helpers for RFC status classes (1xx–5xx).
 */
enum HttpStatusCode: int
{
    use EnumExtension;

    case Continue = 100;

    case SwitchingProtocols = 101;

    case Processing = 102;

    case EarlyHints = 103;

    case Ok = 200;

    case Created = 201;

    case Accepted = 202;

    case NonAuthoritativeInformation = 203;

    case NoContent = 204;

    case ResetContent = 205;

    case PartialContent = 206;

    case MultiStatus = 207;

    case AlreadyReported = 208;

    case ImUsed = 226;

    case MultipleChoices = 300;

    case MovedPermanently = 301;

    case Found = 302;

    case SeeOther = 303;

    case NotModified = 304;

    case UseProxy = 305;

    case Unused306 = 306;

    case TemporaryRedirect = 307;

    case PermanentRedirect = 308;

    case BadRequest = 400;

    case Unauthorized = 401;

    case PaymentRequired = 402;

    case Forbidden = 403;

    case NotFound = 404;

    case MethodNotAllowed = 405;

    case NotAcceptable = 406;

    case ProxyAuthenticationRequired = 407;

    case RequestTimeout = 408;

    case Conflict = 409;

    case Gone = 410;

    case LengthRequired = 411;

    case PreconditionFailed = 412;

    case ContentTooLarge = 413;

    case UriTooLong = 414;

    case UnsupportedMediaType = 415;

    case RangeNotSatisfiable = 416;

    case ExpectationFailed = 417;

    case Unused418 = 418;

    case MisdirectedRequest = 421;

    case UnprocessableContent = 422;

    case Locked = 423;

    case FailedDependency = 424;

    case TooEarly = 425;

    case UpgradeRequired = 426;

    case PreconditionRequired = 428;

    case TooManyRequests = 429;

    case RequestHeaderFieldsTooLarge = 431;

    case UnavailableForLegalReasons = 451;

    case InternalServerError = 500;

    case NotImplemented = 501;

    case BadGateway = 502;

    case ServiceUnavailable = 503;

    case GatewayTimeout = 504;

    case HttpVersionNotSupported = 505;

    case VariantAlsoNegotiates = 506;

    case InsufficientStorage = 507;

    case LoopDetected = 508;

    case NetworkAuthenticationRequired = 511;

    public function series(): int
    {
        return intdiv($this->value, 100);
    }

    public function isInformational(): bool
    {
        return $this->series() === 1;
    }

    public function isSuccess(): bool
    {
        return $this->series() === 2;
    }

    public function isRedirect(): bool
    {
        return $this->series() === 3;
    }

    public function isClientError(): bool
    {
        return $this->series() === 4;
    }

    public function isServerError(): bool
    {
        return $this->series() === 5;
    }

    public function isError(): bool
    {
        return $this->isClientError() || $this->isServerError();
    }
}

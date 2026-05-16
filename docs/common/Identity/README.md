# Common / Identity

Government and travel identity document kinds.

Namespace prefix: `BensonDevs\SuperchargedEnums\Common\Identity\`

## Enums

### `IdentityDocumentType`

`BensonDevs\SuperchargedEnums\Common\Identity\IdentityDocumentType` · backing: `string` · default: `Passport` (`passport`)

Government-issued or travel identity document kinds used in verification. `NationalId` and `IdCard` may overlap by region; pick the slug that best matches local practice. Backing values are lowercase English slugs.

| Case | Backing |
|------|--------|
| `Passport` | `passport` |
| `NationalId` | `national_id` |
| `IdCard` | `id_card` |
| `DriversLicense` | `drivers_license` |
| `ResidencePermit` | `residence_permit` |
| `Visa` | `visa` |


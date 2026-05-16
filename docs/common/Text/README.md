# Common / Text

Text casing and identifier naming transforms.

Namespace prefix: `BensonDevs\SuperchargedEnums\Common\Text\`

## Enums

### `TextCasing`

`BensonDevs\SuperchargedEnums\Common\Text\TextCasing` · backing: `string` · default: `Lower` (`lower`)

Text letter-casing styles. Backing values are lowercase English slugs.

| Case | Backing |
|------|--------|
| `Lower` | `lower` |
| `Upper` | `upper` |
| `Title` | `title` |
| `Sentence` | `sentence` |

### `TextTransform`

`BensonDevs\SuperchargedEnums\Common\Text\TextTransform` · backing: `string` · default: `None` (`none`)

Identifier / string naming transforms. Backing values are lowercase English slugs.

| Case | Backing |
|------|--------|
| `None` | `none` |
| `Snake` | `snake` |
| `Kebab` | `kebab` |
| `Camel` | `camel` |
| `Pascal` | `pascal` |


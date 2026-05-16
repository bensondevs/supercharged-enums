# Common / Platform

Operating system families and CPU architectures.

Namespace prefix: `BensonDevs\SuperchargedEnums\Common\Platform\`

## Enums

### `CpuArchitecture`

`BensonDevs\SuperchargedEnums\Common\Platform\CpuArchitecture` · backing: `string` · default: `X86_64` (`x86_64`)

Common CPU architectures for builds and binaries. `X86_64` is amd64. Backing values are lowercase English slugs.

| Case | Backing |
|------|--------|
| `X86_64` | `x86_64` |
| `Arm64` | `arm64` |
| `Arm` | `arm` |
| `I686` | `i686` |

### `OperatingSystemFamily`

`BensonDevs\SuperchargedEnums\Common\Platform\OperatingSystemFamily` · backing: `string` · default: `Linux` (`linux`)

Common operating system families. Backing values are lowercase English slugs.

| Case | Backing |
|------|--------|
| `Linux` | `linux` |
| `Windows` | `windows` |
| `Macos` | `macos` |
| `Bsd` | `bsd` |

